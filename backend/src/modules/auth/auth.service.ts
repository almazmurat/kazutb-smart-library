import { Injectable, UnauthorizedException } from "@nestjs/common";
import { JwtService } from "@nestjs/jwt";
import { ConfigService } from "@nestjs/config";
import * as ldap from "ldapjs";

import { UserRole } from "@common/types/user-role.enum";
import { UsersService } from "@modules/users/users.service";

interface DemoCredential {
  username: string;
  password: string;
  role: UserRole;
  fullName: string;
  email: string;
}

const DEMO_CREDENTIALS: DemoCredential[] = [
  {
    username: "student_demo",
    password: "Student123!",
    role: UserRole.STUDENT,
    fullName: "Demo Student",
    email: "student_demo@kazutb.edu.kz",
  },
  {
    username: "librarian_demo",
    password: "Librarian123!",
    role: UserRole.LIBRARIAN,
    fullName: "Demo Librarian",
    email: "librarian_demo@kazutb.edu.kz",
  },
  {
    username: "admin_demo",
    password: "Admin123!",
    role: UserRole.ADMIN,
    fullName: "Demo Administrator",
    email: "admin_demo@kazutb.edu.kz",
  },
  {
    username: "analyst_demo",
    password: "Analyst123!",
    role: UserRole.ANALYST,
    fullName: "Demo Analyst",
    email: "analyst_demo@kazutb.edu.kz",
  },
];

export interface LoginResult {
  accessToken: string;
  refreshToken: string;
  user: {
    id: string;
    universityId: string;
    email: string;
    fullName: string;
    role: string;
  };
}

@Injectable()
export class AuthService {
  constructor(
    private readonly jwtService: JwtService,
    private readonly configService: ConfigService,
    private readonly usersService: UsersService,
  ) {}

  private createTokenPayload(user: {
    id: string;
    universityId: string;
    email: string;
    fullName: string;
    role: string;
  }) {
    return {
      sub: user.id,
      universityId: user.universityId,
      email: user.email,
      fullName: user.fullName,
      role: user.role,
    };
  }

  private async issueAuthTokens(user: {
    id: string;
    universityId: string;
    email: string;
    fullName: string;
    role: string;
  }): Promise<LoginResult> {
    const payload = this.createTokenPayload(user);

    const accessToken = await this.jwtService.signAsync(payload, {
      secret: this.configService.get<string>("jwt.secret"),
      expiresIn: this.configService.get<string>("jwt.expiresIn", "1d"),
    });

    const refreshToken = await this.jwtService.signAsync(
      { sub: user.id, type: "refresh" },
      {
        secret: this.configService.get<string>("jwt.secret"),
        expiresIn: this.configService.get<string>("jwt.refreshExpiresIn", "7d"),
      },
    );

    return {
      accessToken,
      refreshToken,
      user: {
        id: user.id,
        universityId: user.universityId,
        email: user.email,
        fullName: user.fullName,
        role: user.role,
      },
    };
  }

  private escapeLdapFilterValue(value: string) {
    return value
      .replace(/\\/g, "\\5c")
      .replace(/\*/g, "\\2a")
      .replace(/\(/g, "\\28")
      .replace(/\)/g, "\\29")
      .replace(/\u0000/g, "\\00");
  }

  private getLdapAttribute(entry: ldap.SearchEntry, key: string): string | null {
    const source = entry.object as Record<string, unknown>;
    const value = source[key];

    if (typeof value === "string" && value.trim()) {
      return value.trim();
    }

    if (Array.isArray(value) && value.length > 0) {
      const first = value[0];
      if (typeof first === "string" && first.trim()) {
        return first.trim();
      }
    }

    return null;
  }

  private getLdapClient() {
    const url = this.configService.get<string>("ldap.url");
    if (!url) {
      throw new UnauthorizedException("LDAP is not configured");
    }

    const tlsRejectUnauthorized =
      this.configService.get<boolean>("ldap.tlsRejectUnauthorized", true) !==
      false;

    const client = ldap.createClient({
      url,
      timeout: 10_000,
      connectTimeout: 10_000,
      tlsOptions: {
        rejectUnauthorized: tlsRejectUnauthorized,
      },
    });

    client.on("error", () => {
      // ldapjs may emit async socket errors during connect/rebind.
      // They are converted to Unauthorized in authenticateViaLdap().
    });

    return client;
  }

  private bindAsync(
    client: ldap.Client,
    dn: string,
    password: string,
  ): Promise<void> {
    return new Promise((resolve, reject) => {
      client.bind(dn, password, (error) => {
        if (error) {
          reject(error);
          return;
        }
        resolve();
      });
    });
  }

  private searchAsync(
    client: ldap.Client,
    baseDn: string,
    options: ldap.SearchOptions,
  ): Promise<ldap.SearchEntry[]> {
    return new Promise((resolve, reject) => {
      client.search(baseDn, options, (error, res) => {
        if (error) {
          reject(error);
          return;
        }

        const entries: ldap.SearchEntry[] = [];

        res.on("searchEntry", (entry) => {
          entries.push(entry);
        });

        res.on("error", (eventError) => {
          reject(eventError);
        });

        res.on("end", () => {
          resolve(entries);
        });
      });
    });
  }

  private unbindSafe(client: ldap.Client) {
    try {
      client.unbind();
    } catch {
      // noop
    }
  }

  private async authenticateViaLdap(username: string, password: string) {
    const bindDn = this.configService.get<string>("ldap.bindDn");
    const bindPassword = this.configService.get<string>("ldap.bindPassword");
    const baseDn = this.configService.get<string>("ldap.baseDn");
    const usersOu = this.configService.get<string>("ldap.usersOu");
    const searchFilterTemplate = this.configService.get<string>(
      "ldap.searchFilter",
      "(sAMAccountName={{username}})",
    );

    if (!bindDn || !bindPassword || !baseDn) {
      throw new UnauthorizedException("LDAP connection settings are incomplete");
    }

    const client = this.getLdapClient();
    const escapedUsername = this.escapeLdapFilterValue(username);
    const searchFilter = searchFilterTemplate.replace(
      /\{\{\s*username\s*\}\}/g,
      escapedUsername,
    );

    try {
      await this.bindAsync(client, bindDn, bindPassword);

      const searchBase = usersOu || baseDn;
      const entries = await this.searchAsync(client, searchBase, {
        scope: "sub",
        filter: searchFilter,
        attributes: [
          "dn",
          "mail",
          "displayName",
          "cn",
          "sAMAccountName",
          "userPrincipalName",
        ],
      });

      const userEntry = entries[0];
      if (!userEntry) {
        throw new UnauthorizedException("Invalid credentials");
      }

      const userDn = userEntry.dn.toString();
      await this.bindAsync(client, userDn, password);

      const samAccountName = this.getLdapAttribute(userEntry, "sAMAccountName");
      const upn = this.getLdapAttribute(userEntry, "userPrincipalName");
      const mail = this.getLdapAttribute(userEntry, "mail");
      const fullName =
        this.getLdapAttribute(userEntry, "displayName") ||
        this.getLdapAttribute(userEntry, "cn") ||
        samAccountName ||
        username;

      return {
        universityId: samAccountName || username,
        email: mail || upn || `${username}@kaztbu.edu.kz`,
        fullName,
      };
    } catch {
      throw new UnauthorizedException("Invalid credentials");
    } finally {
      this.unbindSafe(client);
    }
  }

  private buildDemoUser(credential: DemoCredential) {
    return {
      id: `demo:${credential.username}`,
      universityId: credential.username,
      email: credential.email,
      fullName: credential.fullName,
      role: credential.role,
    };
  }

  private findDemoCredentialByUsername(username: string) {
    return DEMO_CREDENTIALS.find((item) => item.username === username);
  }

  async login(username: string, password: string): Promise<LoginResult> {
    const ldapDevMock = this.configService.get<boolean>("ldap.devMock", true);

    if (!username || !password) {
      throw new UnauthorizedException("Invalid credentials");
    }

    if (!ldapDevMock) {
      const normalizedUsername = username.trim();
      const ldapUser = await this.authenticateViaLdap(
        normalizedUsername,
        password,
      );

      const user = await this.usersService.findOrProvisionByUniversityAccount({
        universityId: ldapUser.universityId,
        email: ldapUser.email,
        fullName: ldapUser.fullName,
        defaultRole: UserRole.STUDENT,
      });

      if (!user.isActive) {
        throw new UnauthorizedException("User is disabled");
      }

      await this.usersService.markLastLogin(user.id);

      return this.issueAuthTokens({
        id: user.id,
        universityId: user.universityId,
        email: user.email,
        fullName: user.fullName,
        role: user.role,
      });
    }

    const credential = this.findDemoCredentialByUsername(username);
    if (!credential || credential.password !== password) {
      throw new UnauthorizedException("Invalid demo credentials");
    }

    const user = this.buildDemoUser(credential);
    return this.issueAuthTokens(user);
  }

  async refresh(refreshToken: string) {
    try {
      const payload = await this.jwtService.verifyAsync(refreshToken, {
        secret: this.configService.get<string>("jwt.secret"),
      });

      if (payload.type !== "refresh" || !payload.sub) {
        throw new UnauthorizedException("Invalid refresh token");
      }

      const username = String(payload.sub).replace(/^demo:/, "");
      if (String(payload.sub).startsWith("demo:")) {
        const credential = this.findDemoCredentialByUsername(username);
        if (!credential) {
          throw new UnauthorizedException("User not found");
        }

        const user = this.buildDemoUser(credential);

        const newAccessToken = await this.jwtService.signAsync(
          this.createTokenPayload(user),
          {
            secret: this.configService.get<string>("jwt.secret"),
            expiresIn: this.configService.get<string>("jwt.expiresIn", "1d"),
          },
        );

        return { accessToken: newAccessToken };
      }

      const user = await this.usersService.findById(String(payload.sub));
      if (!user || !user.isActive) {
        throw new UnauthorizedException("User not found");
      }

      const newAccessToken = await this.jwtService.signAsync(
        this.createTokenPayload({
          id: user.id,
          universityId: user.universityId,
          email: user.email,
          fullName: user.fullName,
          role: user.role,
        }),
        {
          secret: this.configService.get<string>("jwt.secret"),
          expiresIn: this.configService.get<string>("jwt.expiresIn", "1d"),
        },
      );

      return { accessToken: newAccessToken };
    } catch {
      throw new UnauthorizedException("Invalid refresh token");
    }
  }

  getDemoUsers() {
    return DEMO_CREDENTIALS.map(({ username, password, role, fullName }) => ({
      username,
      password,
      role,
      fullName,
    }));
  }
}

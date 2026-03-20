import { Injectable, UnauthorizedException } from "@nestjs/common";
import { JwtService } from "@nestjs/jwt";
import { ConfigService } from "@nestjs/config";

import { UserRole } from "@common/types/user-role.enum";

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
  ) {}

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
    // Demo local auth mode to unblock product demo delivery without LDAP dependency.
    const ldapDevMock = this.configService.get<boolean>("ldap.devMock", true);
    if (!ldapDevMock) {
      throw new UnauthorizedException(
        "Local demo auth is disabled. Enable ldap.devMock to use seeded users.",
      );
    }

    if (!username || !password) {
      throw new UnauthorizedException("Invalid credentials");
    }

    const credential = this.findDemoCredentialByUsername(username);
    if (!credential || credential.password !== password) {
      throw new UnauthorizedException("Invalid demo credentials");
    }

    const user = this.buildDemoUser(credential);

    const payload = {
      sub: user.id,
      universityId: user.universityId,
      email: user.email,
      fullName: user.fullName,
      role: user.role,
    };

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

  async refresh(refreshToken: string) {
    try {
      const payload = await this.jwtService.verifyAsync(refreshToken, {
        secret: this.configService.get<string>("jwt.secret"),
      });

      if (payload.type !== "refresh" || !payload.sub) {
        throw new UnauthorizedException("Invalid refresh token");
      }

      const username = String(payload.sub).replace(/^demo:/, "");
      const credential = this.findDemoCredentialByUsername(username);
      if (!credential) {
        throw new UnauthorizedException("User not found");
      }

      const user = this.buildDemoUser(credential);

      const newAccessToken = await this.jwtService.signAsync(
        {
          sub: user.id,
          universityId: user.universityId,
          email: user.email,
          fullName: user.fullName,
          role: user.role,
        },
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

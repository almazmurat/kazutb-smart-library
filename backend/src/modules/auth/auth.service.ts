import { Injectable, UnauthorizedException } from "@nestjs/common";
import { JwtService } from "@nestjs/jwt";
import { ConfigService } from "@nestjs/config";

import { UsersService } from "@modules/users/users.service";
import { UserRole } from "@common/types/user-role.enum";

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
    private readonly usersService: UsersService,
    private readonly configService: ConfigService,
  ) {}

  async login(username: string, password: string): Promise<LoginResult> {
    // TODO: Replace with real LDAP verification (passport-ldapauth strategy)
    // Dev-mock mode to unblock local development
    const ldapDevMock = this.configService.get<boolean>("ldap.devMock", true);
    if (!ldapDevMock) {
      throw new UnauthorizedException(
        "LDAP integration is not implemented yet",
      );
    }

    if (!username || !password) {
      throw new UnauthorizedException("Invalid credentials");
    }

    const user = await this.usersService.findOrProvisionByUniversityAccount({
      universityId: username,
      email: `${username}@kazutb.edu.kz`,
      fullName: username,
      defaultRole: UserRole.STUDENT,
    });

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

    await this.usersService.markLastLogin(user.id);

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

      const user = await this.usersService.findById(payload.sub);
      if (!user) {
        throw new UnauthorizedException("User not found");
      }

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
}

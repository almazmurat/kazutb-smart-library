import { Injectable } from "@nestjs/common";
import { PassportStrategy } from "@nestjs/passport";
import { ExtractJwt, Strategy } from "passport-jwt";
import { ConfigService } from "@nestjs/config";

import { UserRole } from "@common/types/user-role.enum";
import { RequestUser } from "@common/types/request-user.interface";

interface JwtPayload {
  sub: string;
  universityId: string;
  email: string;
  fullName: string;
  role: UserRole;
}

@Injectable()
export class JwtStrategy extends PassportStrategy(Strategy) {
  constructor(private readonly configService: ConfigService) {
    super({
      jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
      ignoreExpiration: false,
      secretOrKey: configService.get<string>("jwt.secret"),
    });
  }

  validate(payload: JwtPayload): RequestUser {
    return {
      id: payload.sub,
      universityId: payload.universityId,
      email: payload.email,
      fullName: payload.fullName,
      role: payload.role,
    };
  }
}

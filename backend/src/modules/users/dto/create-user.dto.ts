import {
  IsEmail,
  IsEnum,
  IsOptional,
  IsString,
  IsUUID,
  MinLength,
} from "class-validator";

import { UserRole } from "@common/types/user-role.enum";

export class CreateUserDto {
  @IsString()
  @MinLength(3)
  universityId!: string;

  @IsEmail()
  email!: string;

  @IsString()
  @MinLength(2)
  fullName!: string;

  @IsOptional()
  @IsEnum(UserRole)
  role?: UserRole;

  @IsOptional()
  @IsUUID()
  institutionScopeId?: string;

  @IsOptional()
  @IsUUID()
  libraryBranchId?: string;
}

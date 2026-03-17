import { IsEnum } from "class-validator";

import { UserRole } from "@common/types/user-role.enum";

export class UpdateRoleDto {
  @IsEnum(UserRole)
  role!: UserRole;
}

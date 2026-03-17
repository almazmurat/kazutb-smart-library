import { Injectable } from "@nestjs/common";
import { UserRole } from "@common/types/user-role.enum";

@Injectable()
export class RolesService {
  getAllRoles() {
    return Object.values(UserRole);
  }
}

import { Controller, Get } from "@nestjs/common";
import { ApiTags } from "@nestjs/swagger";

import { RolesService } from "./roles.service";
import { Public } from "@common/decorators/public.decorator";

@ApiTags("roles")
@Controller({ path: "roles", version: "1" })
export class RolesController {
  constructor(private readonly rolesService: RolesService) {}

  @Public()
  @Get()
  getRoles() {
    return { data: this.rolesService.getAllRoles() };
  }
}

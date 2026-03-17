import { Controller, Get } from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";

import { Roles } from "@common/decorators/roles.decorator";
import { UserRole } from "@common/types/user-role.enum";
import { MigrationService } from "./migration.service";

@ApiTags("migration")
@ApiBearerAuth()
@Controller({ path: "migration", version: "1" })
export class MigrationController {
  constructor(private readonly service: MigrationService) {}

  @Roles(UserRole.ADMIN)
  @Get()
  list() {
    return { data: this.service.list() };
  }
}

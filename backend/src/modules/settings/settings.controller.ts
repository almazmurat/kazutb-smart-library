import { Controller, Get } from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";

import { Roles } from "@common/decorators/roles.decorator";
import { UserRole } from "@common/types/user-role.enum";
import { SettingsService } from "./settings.service";

@ApiTags("settings")
@ApiBearerAuth()
@Controller({ path: "settings", version: "1" })
export class SettingsController {
  constructor(private readonly service: SettingsService) {}

  @Roles(UserRole.ADMIN)
  @Get()
  list() {
    return { data: this.service.list() };
  }
}

import { Controller, Get } from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";

import { Roles } from "@common/decorators/roles.decorator";
import { UserRole } from "@common/types/user-role.enum";
import { AnalyticsService } from "./analytics.service";

@ApiTags("analytics")
@ApiBearerAuth()
@Controller({ path: "analytics", version: "1" })
export class AnalyticsController {
  constructor(private readonly service: AnalyticsService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get()
  list() {
    return { data: this.service.list() };
  }
}

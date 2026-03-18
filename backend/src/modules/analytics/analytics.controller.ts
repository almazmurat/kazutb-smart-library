import { Controller, Get, Query } from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";

import { Roles } from "@common/decorators/roles.decorator";
import { CurrentUser } from "@common/decorators/current-user.decorator";
import { RequestUser } from "@common/types/request-user.interface";
import { UserRole } from "@common/types/user-role.enum";
import { AnalyticsService } from "./analytics.service";

@ApiTags("analytics")
@ApiBearerAuth()
@Controller({ path: "analytics", version: "1" })
export class AnalyticsController {
  constructor(private readonly service: AnalyticsService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("dashboard")
  getDashboard(@CurrentUser() actor: RequestUser) {
    return this.service.getDashboard(actor);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("popular-books")
  getPopularBooks(
    @CurrentUser() actor: RequestUser,
    @Query("limit") limit?: string,
  ) {
    return this.service.getPopularBooks(
      actor,
      limit ? parseInt(limit, 10) : 10,
    );
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("activity")
  getActivity(@CurrentUser() actor: RequestUser) {
    return this.service.getActivity(actor);
  }
}

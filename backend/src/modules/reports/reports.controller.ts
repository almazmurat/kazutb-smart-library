import { Controller, Get, Query } from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";

import { Roles } from "@common/decorators/roles.decorator";
import { CurrentUser } from "@common/decorators/current-user.decorator";
import { RequestUser } from "@common/types/request-user.interface";
import { UserRole } from "@common/types/user-role.enum";
import { ReportsService } from "./reports.service";

@ApiTags("reports")
@ApiBearerAuth()
@Controller({ path: "reports", version: "1" })
export class ReportsController {
  constructor(private readonly service: ReportsService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("overview")
  getOverview(@CurrentUser() actor: RequestUser, @Query("year") year?: string) {
    return this.service.getOverview(
      actor,
      year ? parseInt(year, 10) : undefined,
    );
  }
}

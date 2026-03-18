import { Controller, Get, Param, Query } from "@nestjs/common";
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

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("data-quality/summary")
  getDataQualitySummary(
    @Query("stage") stage?: string,
    @Query("severity") severity?: string,
    @Query("issueClass") issueClass?: string,
    @Query("status") status?: string,
    @Query("sourceTable") sourceTable?: string,
  ): { data: unknown } {
    return {
      data: this.service.getDataQualitySummary({
        stage,
        severity,
        issueClass,
        status,
        sourceTable,
      }),
    };
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("data-quality/issues")
  getDataQualityIssues(
    @Query("stage") stage?: string,
    @Query("severity") severity?: string,
    @Query("issueClass") issueClass?: string,
    @Query("status") status?: string,
    @Query("sourceTable") sourceTable?: string,
    @Query("limit") limit?: string,
  ): { data: unknown } {
    return {
      data: this.service.getDataQualityIssues({
        stage,
        severity,
        issueClass,
        status,
        sourceTable,
        limit: limit ? Number.parseInt(limit, 10) : undefined,
      }),
    };
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("data-quality/issues/:id")
  getDataQualityIssueById(@Param("id") id: string): unknown {
    return this.service.getDataQualityIssueById(id);
  }
}

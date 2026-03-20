import {
  Body,
  Controller,
  Get,
  Param,
  ParseUUIDPipe,
  Patch,
  Post,
  Query,
} from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";
import { DataQualityReviewStatus } from "@prisma/client";

import { CurrentUser } from "@common/decorators/current-user.decorator";
import { Roles } from "@common/decorators/roles.decorator";
import { RequestUser } from "@common/types/request-user.interface";
import { UserRole } from "@common/types/user-role.enum";
import { ReadLayerService } from "@modules/read-layer/read-layer.service";
import { AppReviewActionDto } from "./dto/app-review-action.dto";
import { AppReviewQueueQueryDto } from "./dto/app-review-queue.query.dto";
import { MigrationService } from "./migration.service";

@ApiTags("migration")
@ApiBearerAuth()
@Controller({ path: "migration", version: "1" })
export class MigrationController {
  constructor(
    private readonly service: MigrationService,
    private readonly readLayerService: ReadLayerService,
  ) {}

  @Roles(UserRole.ADMIN)
  @Get()
  list() {
    return { data: this.service.list() };
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("data-quality/summary")
  getDataQualitySummary(
    @CurrentUser() actor: RequestUser,
    @Query("stage") stage?: string,
    @Query("severity") severity?: string,
    @Query("issueClass") issueClass?: string,
    @Query("status") status?: string,
    @Query("sourceTable") sourceTable?: string,
  ): { data: unknown } {
    return {
      data: this.service.getDataQualitySummary(actor, {
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
    @CurrentUser() actor: RequestUser,
    @Query("stage") stage?: string,
    @Query("severity") severity?: string,
    @Query("issueClass") issueClass?: string,
    @Query("status") status?: string,
    @Query("sourceTable") sourceTable?: string,
    @Query("limit") limit?: string,
  ): { data: unknown } {
    return {
      data: this.service.getDataQualityIssues(actor, {
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
  @Get("app-review/issues")
  getAppReviewQueue(@Query() query: AppReviewQueueQueryDto) {
    return this.readLayerService.getReviewQueue({
      ...query,
      page: query.page ?? 1,
      limit: query.limit ?? 20,
    });
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("app-review/issues/:flagId")
  async getAppReviewIssueDetail(
    @Param("flagId", new ParseUUIDPipe()) flagId: string,
  ) {
    return {
      data: await this.readLayerService.getReviewIssueDetail(flagId),
    };
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post("app-review/issues/:flagId/actions")
  async applyAppReviewAction(
    @CurrentUser() actor: RequestUser,
    @Param("flagId", new ParseUUIDPipe()) flagId: string,
    @Body() body: AppReviewActionDto,
  ) {
    await this.service.applyAppReviewAction(actor, flagId, body);

    return {
      data: await this.readLayerService.getReviewIssueDetail(flagId),
    };
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ANALYST, UserRole.ADMIN)
  @Get("data-quality/issues/:id")
  getDataQualityIssueById(
    @CurrentUser() actor: RequestUser,
    @Param("id") id: string,
  ): unknown {
    return this.service.getDataQualityIssueById(actor, id);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch("data-quality/issues/:id/review")
  updateIssueReviewStatus(
    @CurrentUser() actor: RequestUser,
    @Param("id") id: string,
    @Body() body: { status: string; note?: string },
  ): Promise<unknown> {
    return this.service.updateIssueReviewStatus(
      actor,
      id,
      body.status as DataQualityReviewStatus,
      body.note,
    );
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post("data-quality/issues/:id/notes")
  addIssueNote(
    @CurrentUser() actor: RequestUser,
    @Param("id") id: string,
    @Body() body: { note: string },
  ): Promise<unknown> {
    return this.service.addIssueNote(actor, id, body.note);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch("data-quality/issues/:id/assign")
  assignIssue(
    @CurrentUser() actor: RequestUser,
    @Param("id") id: string,
    @Body() body: { assigneeUserId?: string },
  ): Promise<unknown> {
    return this.service.assignIssue(actor, id, body.assigneeUserId);
  }
}

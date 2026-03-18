import {
  Body,
  Controller,
  Get,
  Param,
  Patch,
  Post,
  Query,
  Req,
} from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";
import { Request } from "express";

import { Roles } from "@common/decorators/roles.decorator";
import { CurrentUser } from "@common/decorators/current-user.decorator";
import { RequestUser } from "@common/types/request-user.interface";
import { UserRole } from "@common/types/user-role.enum";

import { ReservationsService } from "./reservations.service";
import { CreateReservationDto } from "./dto/create-reservation.dto";
import { ListReservationsQueryDto } from "./dto/list-reservations.query.dto";
import { UpdateReservationStatusDto } from "./dto/update-reservation-status.dto";

@ApiTags("reservations")
@ApiBearerAuth()
@Controller({ path: "reservations", version: "1" })
export class ReservationsController {
  constructor(private readonly service: ReservationsService) {}

  @Roles(UserRole.STUDENT, UserRole.TEACHER, UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post()
  create(
    @Body() dto: CreateReservationDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.create(dto, actor, {
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.STUDENT, UserRole.TEACHER, UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get("my")
  listMy(
    @Query() query: ListReservationsQueryDto,
    @CurrentUser() actor: RequestUser,
  ) {
    return this.service.getMyReservations(actor, query);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get()
  list(
    @Query() query: ListReservationsQueryDto,
    @CurrentUser() actor: RequestUser,
  ) {
    return this.service.list(actor, query);
  }

  @Roles(UserRole.STUDENT, UserRole.TEACHER, UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get(":id")
  findOne(@Param("id") id: string, @CurrentUser() actor: RequestUser) {
    return this.service.getById(id, actor);
  }

  @Roles(UserRole.STUDENT, UserRole.TEACHER, UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch(":id/cancel")
  cancel(
    @Param("id") id: string,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.cancel(id, actor, {
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch(":id/status")
  updateStatus(
    @Param("id") id: string,
    @Body() dto: UpdateReservationStatusDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.updateStatus(id, dto, actor, {
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }
}

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

import { CirculationService } from "./circulation.service";
import { CreateLoanDto } from "./dto/create-loan.dto";
import { ReturnLoanDto } from "./dto/return-loan.dto";
import { ListLoansQueryDto } from "./dto/list-loans.query.dto";

@ApiTags("circulation")
@ApiBearerAuth()
@Controller({ path: "circulation", version: "1" })
export class CirculationController {
  constructor(private readonly service: CirculationService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post("loans")
  issueLoan(
    @Body() dto: CreateLoanDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.issueLoan(dto, actor, {
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch("loans/:id/return")
  returnLoan(
    @Param("id") id: string,
    @Body() dto: ReturnLoanDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.returnLoan(id, dto, actor, {
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get("loans")
  list(@Query() query: ListLoansQueryDto, @CurrentUser() actor: RequestUser) {
    return this.service.list(actor, query);
  }

  @Roles(UserRole.STUDENT, UserRole.TEACHER, UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get("my")
  listMy(@Query() query: ListLoansQueryDto, @CurrentUser() actor: RequestUser) {
    return this.service.getMyLoans(actor, query);
  }

  @Roles(UserRole.STUDENT, UserRole.TEACHER, UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get("loans/:id")
  findOne(@Param("id") id: string, @CurrentUser() actor: RequestUser) {
    return this.service.getById(id, actor);
  }
}

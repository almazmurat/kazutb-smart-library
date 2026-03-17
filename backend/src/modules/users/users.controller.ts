import {
  Controller,
  Get,
  Param,
  Patch,
  Body,
  Post,
  Delete,
  Query,
  Req,
} from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";
import { Request } from "express";

import { UsersService } from "./users.service";
import { Roles } from "@common/decorators/roles.decorator";
import { UserRole } from "@common/types/user-role.enum";
import { CreateUserDto } from "./dto/create-user.dto";
import { UpdateUserDto } from "./dto/update-user.dto";
import { UpdateRoleDto } from "./dto/update-role.dto";
import { CurrentUser } from "@common/decorators/current-user.decorator";
import { RequestUser } from "@common/types/request-user.interface";

@ApiTags("users")
@ApiBearerAuth()
@Controller({ path: "users", version: "1" })
export class UsersController {
  constructor(private readonly usersService: UsersService) {}

  @Roles(UserRole.ADMIN)
  @Post()
  create(@Body() dto: CreateUserDto) {
    return this.usersService.create(dto);
  }

  @Roles(UserRole.ADMIN)
  @Get()
  findAll(
    @Query("search") search?: string,
    @Query("role") role?: UserRole,
    @Query("page") page?: string,
    @Query("limit") limit?: string,
  ) {
    return this.usersService.findAll({
      search,
      role,
      page: page ? Number(page) : 1,
      limit: limit ? Number(limit) : 20,
    });
  }

  @Roles(UserRole.ADMIN)
  @Get(":id")
  findOne(@Param("id") id: string) {
    return { data: this.usersService.findById(id) };
  }

  @Roles(UserRole.ADMIN)
  @Patch(":id")
  update(@Param("id") id: string, @Body() dto: UpdateUserDto) {
    return this.usersService.update(id, dto);
  }

  @Roles(UserRole.ADMIN)
  @Patch(":id/role")
  updateRole(
    @Param("id") id: string,
    @Body() dto: UpdateRoleDto,
    @CurrentUser() user: RequestUser,
    @Req() req: Request,
  ) {
    return this.usersService.updateRole(id, dto.role, {
      actorUserId: user.id,
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.ADMIN)
  @Delete(":id")
  deactivate(@Param("id") id: string) {
    return this.usersService.deactivate(id);
  }
}

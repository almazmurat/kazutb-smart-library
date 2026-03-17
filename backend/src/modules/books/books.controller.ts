import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
  Query,
  Req,
} from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";
import { Request } from "express";

import { Public } from "@common/decorators/public.decorator";
import { Roles } from "@common/decorators/roles.decorator";
import { CurrentUser } from "@common/decorators/current-user.decorator";
import { RequestUser } from "@common/types/request-user.interface";
import { UserRole } from "@common/types/user-role.enum";

import { BooksService } from "./books.service";
import { CreateBookCopyDto } from "./dto/create-book-copy.dto";
import { CreateBookDto } from "./dto/create-book.dto";
import { ListBooksQueryDto } from "./dto/list-books.query.dto";
import { UpdateBookCopyDto } from "./dto/update-book-copy.dto";
import { UpdateBookDto } from "./dto/update-book.dto";

@ApiTags("books")
@ApiBearerAuth()
@Controller({ path: "books", version: "1" })
export class BooksController {
  constructor(private readonly service: BooksService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post()
  create(
    @Body() dto: CreateBookDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.create(dto, {
      actor,
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Public()
  @Get()
  list(@Query() query: ListBooksQueryDto) {
    return this.service.list(query);
  }

  @Public()
  @Get(":id")
  findOne(@Param("id") id: string) {
    return this.service.findOne(id);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch(":id")
  update(
    @Param("id") id: string,
    @Body() dto: UpdateBookDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.update(id, dto, {
      actor,
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Delete(":id")
  deactivate(
    @Param("id") id: string,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.deactivate(id, {
      actor,
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post(":bookId/copies")
  createCopy(
    @Param("bookId") bookId: string,
    @Body() dto: CreateBookCopyDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.createCopy(bookId, dto, {
      actor,
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get(":bookId/copies")
  listCopies(@Param("bookId") bookId: string) {
    return this.service.listCopies(bookId);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch(":bookId/copies/:copyId")
  updateCopy(
    @Param("bookId") bookId: string,
    @Param("copyId") copyId: string,
    @Body() dto: UpdateBookCopyDto,
    @CurrentUser() actor: RequestUser,
    @Req() req: Request,
  ) {
    return this.service.updateCopy(bookId, copyId, dto, {
      actor,
      ipAddress: req.ip,
      userAgent:
        typeof req.headers["user-agent"] === "string"
          ? req.headers["user-agent"]
          : undefined,
    });
  }
}

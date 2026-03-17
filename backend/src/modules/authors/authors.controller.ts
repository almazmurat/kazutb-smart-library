import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
  Query,
} from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";

import { Public } from "@common/decorators/public.decorator";
import { Roles } from "@common/decorators/roles.decorator";
import { UserRole } from "@common/types/user-role.enum";

import { AuthorsService } from "./authors.service";
import { CreateAuthorDto } from "./dto/create-author.dto";
import { ListAuthorsQueryDto } from "./dto/list-authors.query.dto";
import { UpdateAuthorDto } from "./dto/update-author.dto";

@ApiTags("authors")
@ApiBearerAuth()
@Controller({ path: "authors", version: "1" })
export class AuthorsController {
  constructor(private readonly service: AuthorsService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post()
  create(@Body() dto: CreateAuthorDto) {
    return this.service.create(dto);
  }

  @Public()
  @Get()
  list(@Query() query: ListAuthorsQueryDto) {
    return this.service.list(query);
  }

  @Public()
  @Get(":id")
  findOne(@Param("id") id: string) {
    return this.service.findOne(id);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch(":id")
  update(@Param("id") id: string, @Body() dto: UpdateAuthorDto) {
    return this.service.update(id, dto);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Delete(":id")
  deactivate(@Param("id") id: string) {
    return this.service.deactivate(id);
  }
}

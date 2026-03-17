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

import { CategoriesService } from "./categories.service";
import { CreateCategoryDto } from "./dto/create-category.dto";
import { ListCategoriesQueryDto } from "./dto/list-categories.query.dto";
import { UpdateCategoryDto } from "./dto/update-category.dto";

@ApiTags("categories")
@ApiBearerAuth()
@Controller({ path: "categories", version: "1" })
export class CategoriesController {
  constructor(private readonly service: CategoriesService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Post()
  create(@Body() dto: CreateCategoryDto) {
    return this.service.create(dto);
  }

  @Public()
  @Get()
  list(@Query() query: ListCategoriesQueryDto) {
    return this.service.list(query);
  }

  @Public()
  @Get(":id")
  findOne(@Param("id") id: string) {
    return this.service.findOne(id);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Patch(":id")
  update(@Param("id") id: string, @Body() dto: UpdateCategoryDto) {
    return this.service.update(id, dto);
  }

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Delete(":id")
  deactivate(@Param("id") id: string) {
    return this.service.deactivate(id);
  }
}

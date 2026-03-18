import { Controller, Get, Param, Query } from "@nestjs/common";
import { ApiTags } from "@nestjs/swagger";

import { Public } from "@common/decorators/public.decorator";

import { BooksService } from "./books.service";
import { PublicBooksQueryDto } from "./dto/public-books.query.dto";

@ApiTags("public-catalog")
@Public()
@Controller({ path: "public", version: "1" })
export class PublicBooksController {
  constructor(private readonly booksService: BooksService) {}

  @Get("books")
  list(@Query() query: PublicBooksQueryDto) {
    return this.booksService.listPublic(query);
  }

  @Get("books/:id")
  findOne(@Param("id") id: string) {
    return this.booksService.findPublicById(id);
  }

  @Get("filters")
  getFilters() {
    return this.booksService.getPublicFilters();
  }
}

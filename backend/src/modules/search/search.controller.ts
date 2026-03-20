import { Controller, Get, Query } from "@nestjs/common";
import { ApiTags } from "@nestjs/swagger";

import { Public } from "@common/decorators/public.decorator";
import { SearchService } from "./search.service";
import { CatalogSearchQueryDto } from "./dto/catalog-search.query.dto";

@ApiTags("search")
@Controller({ path: "search", version: "1" })
export class SearchController {
  constructor(private readonly service: SearchService) {}

  @Public()
  @Get()
  list(@Query() query: CatalogSearchQueryDto) {
    return this.service.list(query);
  }
}

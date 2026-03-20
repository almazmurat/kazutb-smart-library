import { Injectable } from "@nestjs/common";

import { ReadLayerService } from "@modules/read-layer/read-layer.service";

import { CatalogSearchQueryDto } from "./dto/catalog-search.query.dto";

@Injectable()
export class SearchService {
  constructor(private readonly readLayerService: ReadLayerService) {}

  list(query: CatalogSearchQueryDto) {
    return this.readLayerService.searchCatalog({
      ...query,
      page: query.page ?? 1,
      limit: query.limit ?? 20,
    });
  }
}

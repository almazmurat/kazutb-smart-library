import { Controller, Get, Param, ParseUUIDPipe, Query } from "@nestjs/common";
import { ApiTags } from "@nestjs/swagger";

import { Public } from "@common/decorators/public.decorator";
import { ReadLayerService } from "@modules/read-layer/read-layer.service";

import { CatalogAvailabilityQueryDto } from "./dto/catalog-availability.query.dto";
import { CatalogSearchQueryDto } from "./dto/catalog-search.query.dto";

@ApiTags("catalog")
@Public()
@Controller({ path: "catalog", version: "1" })
export class CatalogController {
  constructor(private readonly readLayerService: ReadLayerService) {}

  @Get()
  list(@Query() query: CatalogSearchQueryDto) {
    return this.readLayerService.searchCatalog({
      ...query,
      page: query.page ?? 1,
      limit: query.limit ?? 20,
    });
  }

  @Get("facets")
  async getFacets() {
    return {
      data: await this.readLayerService.getCatalogFacets(),
    };
  }

  @Get("locations/summary")
  async getLocationSummary(@Query() query: CatalogAvailabilityQueryDto) {
    return {
      data: await this.readLayerService.getLocationInventorySummary(query),
    };
  }

  @Get(":documentId/availability")
  async getAvailability(
    @Param("documentId", new ParseUUIDPipe()) documentId: string,
    @Query() query: CatalogAvailabilityQueryDto,
  ) {
    return {
      data: await this.readLayerService.getDocumentAvailability(
        documentId,
        query,
      ),
    };
  }

  @Get(":documentId")
  async findOne(@Param("documentId", new ParseUUIDPipe()) documentId: string) {
    return {
      data: await this.readLayerService.getPublicDocumentDetail(documentId),
    };
  }
}

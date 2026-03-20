import { Module } from "@nestjs/common";

import { ReadLayerModule } from "@modules/read-layer/read-layer.module";

import { CatalogController } from "./catalog.controller";
import { SearchController } from "./search.controller";
import { SearchService } from "./search.service";

@Module({
  imports: [ReadLayerModule],
  controllers: [SearchController, CatalogController],
  providers: [SearchService],
  exports: [SearchService],
})
export class SearchModule {}

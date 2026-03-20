import { Module } from "@nestjs/common";

import { PrismaModule } from "@prisma/prisma.module";

import { ReadLayerRepository } from "./read-layer.repository";
import { ReadLayerService } from "./read-layer.service";

@Module({
  imports: [PrismaModule],
  providers: [ReadLayerRepository, ReadLayerService],
  exports: [ReadLayerRepository, ReadLayerService],
})
export class ReadLayerModule {}

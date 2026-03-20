import { Module } from "@nestjs/common";

import { ReadLayerModule } from "@modules/read-layer/read-layer.module";
import { PrismaModule } from "../../prisma/prisma.module";
import { AuditModule } from "../audit/audit.module";
import { MigrationController } from "./migration.controller";
import { MigrationService } from "./migration.service";

@Module({
  imports: [PrismaModule, AuditModule, ReadLayerModule],
  controllers: [MigrationController],
  providers: [MigrationService],
  exports: [MigrationService],
})
export class MigrationModule {}

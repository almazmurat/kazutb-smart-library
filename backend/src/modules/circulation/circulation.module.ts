import { Module } from "@nestjs/common";

import { AuditModule } from "@modules/audit/audit.module";

import { CirculationController } from "./circulation.controller";
import { CirculationService } from "./circulation.service";

@Module({
  imports: [AuditModule],
  controllers: [CirculationController],
  providers: [CirculationService],
  exports: [CirculationService],
})
export class CirculationModule {}
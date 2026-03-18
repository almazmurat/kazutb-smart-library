import { Module } from "@nestjs/common";

import { CatalogOwnershipPolicy } from "@common/policies/catalog-ownership.policy";
import { AuditModule } from "@modules/audit/audit.module";

import { ReservationsController } from "./reservations.controller";
import { ReservationsService } from "./reservations.service";

@Module({
  imports: [AuditModule],
  controllers: [ReservationsController],
  providers: [ReservationsService, CatalogOwnershipPolicy],
  exports: [ReservationsService],
})
export class ReservationsModule {}

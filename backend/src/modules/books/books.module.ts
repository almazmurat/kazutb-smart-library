import { Module } from "@nestjs/common";

import { CatalogOwnershipPolicy } from "@common/policies/catalog-ownership.policy";
import { AuditModule } from "@modules/audit/audit.module";

import { BooksController } from "./books.controller";
import { PublicBooksController } from "./public-books.controller";
import { BooksService } from "./books.service";

@Module({
  imports: [AuditModule],
  controllers: [BooksController, PublicBooksController],
  providers: [BooksService, CatalogOwnershipPolicy],
  exports: [BooksService],
})
export class BooksModule {}

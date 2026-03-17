import { Module } from "@nestjs/common";
import { ConfigModule } from "@nestjs/config";
import { ThrottlerModule } from "@nestjs/throttler";
import { APP_GUARD } from "@nestjs/core";

import configuration from "./config/configuration";
import { validateEnv } from "./config/env.validation";
import { PrismaModule } from "./prisma/prisma.module";

import { AuthModule } from "./modules/auth/auth.module";
import { UsersModule } from "./modules/users/users.module";
import { RolesModule } from "./modules/roles/roles.module";
import { BooksModule } from "./modules/books/books.module";
import { AuthorsModule } from "./modules/authors/authors.module";
import { CategoriesModule } from "./modules/categories/categories.module";
import { FilesModule } from "./modules/files/files.module";
import { SearchModule } from "./modules/search/search.module";
import { ReservationsModule } from "./modules/reservations/reservations.module";
import { CirculationModule } from "./modules/circulation/circulation.module";
import { MigrationModule } from "./modules/migration/migration.module";
import { ReportsModule } from "./modules/reports/reports.module";
import { AnalyticsModule } from "./modules/analytics/analytics.module";
import { AuditModule } from "./modules/audit/audit.module";
import { SettingsModule } from "./modules/settings/settings.module";
import { HealthModule } from "./modules/health/health.module";

import { JwtAuthGuard } from "./common/guards/jwt-auth.guard";
import { RolesGuard } from "./common/guards/roles.guard";

@Module({
  imports: [
    // Config — loads .env, available globally via ConfigService
    ConfigModule.forRoot({
      isGlobal: true,
      load: [configuration],
      envFilePath: [".env", ".env.local"],
      validate: validateEnv,
    }),

    // Rate limiting — applies globally
    ThrottlerModule.forRoot([
      {
        ttl: 60000,
        limit: 120,
      },
    ]),

    // Database
    PrismaModule,

    // Feature modules
    AuthModule,
    UsersModule,
    RolesModule,
    BooksModule,
    AuthorsModule,
    CategoriesModule,
    FilesModule,
    SearchModule,
    ReservationsModule,
    CirculationModule,
    MigrationModule,
    ReportsModule,
    AnalyticsModule,
    AuditModule,
    SettingsModule,
    HealthModule,
  ],
  providers: [
    // Apply JWT guard globally — use @Public() decorator to opt out on specific routes
    { provide: APP_GUARD, useClass: JwtAuthGuard },
    // Apply RBAC guard globally — use @Roles() decorator to specify required roles
    { provide: APP_GUARD, useClass: RolesGuard },
  ],
})
export class AppModule {}

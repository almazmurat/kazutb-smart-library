import {
  Injectable,
  OnModuleInit,
  OnModuleDestroy,
  Logger,
} from "@nestjs/common";
import { PrismaClient } from "@prisma/client";

@Injectable()
export class PrismaService
  extends PrismaClient
  implements OnModuleInit, OnModuleDestroy
{
  private readonly logger = new Logger(PrismaService.name);
  private readonly maxConnectAttempts = 5;

  constructor() {
    super({
      log: [
        { emit: "stdout", level: "info" },
        { emit: "stdout", level: "warn" },
        { emit: "stdout", level: "error" },
      ],
    });
  }

  async onModuleInit() {
    await this.connectWithRetry();

    this.logger.log("Database connection established");
  }

  async onModuleDestroy() {
    await this.$disconnect();
    this.logger.log("Database connection closed");
  }

  async isDatabaseHealthy(): Promise<boolean> {
    try {
      await this.$queryRaw`SELECT 1`;
      return true;
    } catch {
      return false;
    }
  }

  private async connectWithRetry(): Promise<void> {
    let attempt = 0;
    while (attempt < this.maxConnectAttempts) {
      try {
        await this.$connect();
        return;
      } catch (error) {
        attempt += 1;
        this.logger.warn(
          `Database connection attempt ${attempt}/${this.maxConnectAttempts} failed`,
        );
        if (attempt >= this.maxConnectAttempts) {
          throw error;
        }
        await new Promise((resolve) => setTimeout(resolve, 1000 * attempt));
      }
    }
  }
}

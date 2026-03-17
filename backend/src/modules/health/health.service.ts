import { Injectable } from "@nestjs/common";
import { PrismaService } from "@prisma/prisma.service";

@Injectable()
export class HealthService {
  constructor(private readonly prisma: PrismaService) {}

  async getHealth() {
    const dbHealthy = await this.prisma.isDatabaseHealthy();

    return {
      status: dbHealthy ? "ok" : "degraded",
      service: "kazutb-library-api",
      database: {
        status: dbHealthy ? "up" : "down",
      },
      timestamp: new Date().toISOString(),
      uptimeSeconds: Math.floor(process.uptime()),
    };
  }
}

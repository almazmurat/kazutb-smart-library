import { Injectable } from "@nestjs/common";
import { UserRole } from "@common/types/user-role.enum";
import { PrismaService } from "@prisma/prisma.service";
import { AuditService } from "@modules/audit/audit.service";

import { CreateUserDto } from "./dto/create-user.dto";
import { UpdateUserDto } from "./dto/update-user.dto";

interface ProvisionUserInput {
  universityId: string;
  email: string;
  fullName: string;
  defaultRole: UserRole;
}

@Injectable()
export class UsersService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly auditService: AuditService,
  ) {}

  async findById(id: string) {
    return this.prisma.user.findUnique({
      where: { id },
      include: {
        institutionScope: true,
        libraryBranch: true,
      },
    });
  }

  async findAll(query?: {
    search?: string;
    role?: UserRole;
    page?: number;
    limit?: number;
  }) {
    const page = Math.max(1, query?.page || 1);
    const limit = Math.min(100, Math.max(1, query?.limit || 20));

    const where = {
      ...(query?.role ? { role: query.role } : {}),
      ...(query?.search
        ? {
            OR: [
              {
                fullName: {
                  contains: query.search,
                  mode: "insensitive" as const,
                },
              },
              {
                email: { contains: query.search, mode: "insensitive" as const },
              },
              {
                universityId: {
                  contains: query.search,
                  mode: "insensitive" as const,
                },
              },
            ],
          }
        : {}),
    };

    const [data, total] = await this.prisma.$transaction([
      this.prisma.user.findMany({
        where,
        include: {
          institutionScope: true,
          libraryBranch: true,
        },
        orderBy: { createdAt: "desc" },
        skip: (page - 1) * limit,
        take: limit,
      }),
      this.prisma.user.count({ where }),
    ]);

    return {
      data,
      meta: {
        page,
        limit,
        total,
        totalPages: Math.ceil(total / limit),
      },
    };
  }

  async create(dto: CreateUserDto) {
    return this.prisma.user.create({
      data: {
        universityId: dto.universityId,
        email: dto.email,
        fullName: dto.fullName,
        role: dto.role || UserRole.STUDENT,
        institutionScopeId: dto.institutionScopeId,
        libraryBranchId: dto.libraryBranchId,
      },
    });
  }

  async update(id: string, dto: UpdateUserDto) {
    return this.prisma.user.update({
      where: { id },
      data: {
        ...(dto.email !== undefined ? { email: dto.email } : {}),
        ...(dto.fullName !== undefined ? { fullName: dto.fullName } : {}),
        ...(dto.isActive !== undefined ? { isActive: dto.isActive } : {}),
        ...(dto.institutionScopeId !== undefined
          ? { institutionScopeId: dto.institutionScopeId }
          : {}),
        ...(dto.libraryBranchId !== undefined
          ? { libraryBranchId: dto.libraryBranchId }
          : {}),
      },
    });
  }

  async findOrProvisionByUniversityAccount(input: ProvisionUserInput) {
    const existing = await this.prisma.user.findUnique({
      where: { universityId: input.universityId },
    });

    if (existing) {
      return existing;
    }

    return this.prisma.user.create({
      data: {
        universityId: input.universityId,
        email: input.email,
        fullName: input.fullName,
        role: input.defaultRole,
      },
    });
  }

  async updateRole(
    id: string,
    role: UserRole,
    context?: { actorUserId?: string; ipAddress?: string; userAgent?: string },
  ) {
    const before = await this.findById(id);
    const updated = await this.prisma.user.update({
      where: { id },
      data: { role },
    });

    await this.auditService.write({
      action: "USER_ROLE_UPDATED",
      entityType: "user",
      entityId: id,
      userId: context?.actorUserId,
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
      metadata: {
        oldRole: before?.role,
        newRole: role,
      },
    });

    return updated;
  }

  async deactivate(id: string) {
    return this.prisma.user.update({
      where: { id },
      data: { isActive: false },
    });
  }

  async markLastLogin(id: string) {
    return this.prisma.user.update({
      where: { id },
      data: { lastLoginAt: new Date() },
    });
  }
}

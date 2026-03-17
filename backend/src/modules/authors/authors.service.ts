import { Injectable, NotFoundException } from "@nestjs/common";

import { PrismaService } from "@prisma/prisma.service";

import { CreateAuthorDto } from "./dto/create-author.dto";
import { ListAuthorsQueryDto } from "./dto/list-authors.query.dto";
import { UpdateAuthorDto } from "./dto/update-author.dto";

@Injectable()
export class AuthorsService {
  constructor(private readonly prisma: PrismaService) {}

  async create(dto: CreateAuthorDto) {
    return this.prisma.author.create({
      data: {
        fullName: dto.fullName,
        isActive: dto.isActive ?? true,
      },
    });
  }

  async list(query: ListAuthorsQueryDto) {
    const page = Math.max(1, query.page || 1);
    const limit = Math.min(100, Math.max(1, query.limit || 20));

    const where = {
      ...(query.search
        ? {
            fullName: {
              contains: query.search,
              mode: "insensitive" as const,
            },
          }
        : {}),
      ...(query.isActive !== undefined ? { isActive: query.isActive } : {}),
    };

    const [data, total] = await this.prisma.$transaction([
      this.prisma.author.findMany({
        where,
        orderBy: [{ fullName: "asc" }],
        skip: (page - 1) * limit,
        take: limit,
      }),
      this.prisma.author.count({ where }),
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

  async findOne(id: string) {
    const author = await this.prisma.author.findUnique({ where: { id } });
    if (!author) {
      throw new NotFoundException("Author not found");
    }
    return author;
  }

  async update(id: string, dto: UpdateAuthorDto) {
    await this.findOne(id);

    return this.prisma.author.update({
      where: { id },
      data: {
        ...(dto.fullName !== undefined ? { fullName: dto.fullName } : {}),
        ...(dto.isActive !== undefined ? { isActive: dto.isActive } : {}),
      },
    });
  }

  async deactivate(id: string) {
    await this.findOne(id);

    return this.prisma.author.update({
      where: { id },
      data: { isActive: false },
    });
  }
}

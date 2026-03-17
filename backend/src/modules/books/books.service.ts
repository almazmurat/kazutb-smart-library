import {
  BadRequestException,
  Injectable,
  NotFoundException,
} from "@nestjs/common";
import { CopyStatus, Prisma } from "@prisma/client";

import { RequestUser } from "../../common/types/request-user.interface";
import { CatalogOwnershipPolicy } from "../../common/policies/catalog-ownership.policy";
import { PrismaService } from "../../prisma/prisma.service";
import { AuditService } from "../audit/audit.service";

import { CreateBookCopyDto } from "./dto/create-book-copy.dto";
import { CreateBookDto } from "./dto/create-book.dto";
import { ListBooksQueryDto } from "./dto/list-books.query.dto";
import { UpdateBookCopyDto } from "./dto/update-book-copy.dto";
import { UpdateBookDto } from "./dto/update-book.dto";

interface MutationContext {
  actor: RequestUser;
  ipAddress?: string;
  userAgent?: string;
}

@Injectable()
export class BooksService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly auditService: AuditService,
    private readonly ownershipPolicy: CatalogOwnershipPolicy,
  ) {}

  private readonly bookInclude = {
    libraryBranch: {
      select: {
        id: true,
        code: true,
        name: true,
        scope: {
          select: {
            id: true,
            code: true,
            name: true,
          },
        },
      },
    },
    authors: {
      include: {
        author: true,
      },
    },
    categories: {
      include: {
        category: true,
      },
    },
    copies: {
      orderBy: {
        createdAt: "desc" as const,
      },
    },
  } satisfies Prisma.BookInclude;

  async create(dto: CreateBookDto, context: MutationContext) {
    await this.ownershipPolicy.assertCanMutateBranch(
      context.actor,
      dto.libraryBranchId,
    );
    await this.ensureRelationIds(dto.authorIds, dto.categoryIds);

    const created = await this.prisma.book.create({
      data: {
        title: dto.title,
        subtitle: dto.subtitle,
        description: dto.description,
        publishYear: dto.publishYear,
        isbn: dto.isbn,
        language: dto.language,
        libraryBranchId: dto.libraryBranchId,
        isActive: dto.isActive ?? true,
        keywords: [],
        authors: dto.authorIds
          ? {
              create: dto.authorIds.map((authorId) => ({
                authorId,
              })),
            }
          : undefined,
        categories: dto.categoryIds
          ? {
              create: dto.categoryIds.map((categoryId) => ({
                categoryId,
              })),
            }
          : undefined,
      },
      include: this.bookInclude,
    });

    await this.auditService.write({
      action: "BOOK_CREATED",
      entityType: "book",
      entityId: created.id,
      userId: context.actor.id,
      ipAddress: context.ipAddress,
      userAgent: context.userAgent,
      metadata: {
        libraryBranchId: created.libraryBranchId,
        title: created.title,
      },
    });

    return created;
  }

  async list(query: ListBooksQueryDto) {
    const page = Math.max(1, query.page || 1);
    const limit = Math.min(100, Math.max(1, query.limit || 20));

    const where: Prisma.BookWhereInput = {
      ...(query.title
        ? {
            title: {
              contains: query.title,
              mode: "insensitive",
            },
          }
        : {}),
      ...(query.authorId
        ? {
            authors: {
              some: {
                authorId: query.authorId,
              },
            },
          }
        : {}),
      ...(query.categoryId
        ? {
            categories: {
              some: {
                categoryId: query.categoryId,
              },
            },
          }
        : {}),
      ...(query.branchId ? { libraryBranchId: query.branchId } : {}),
      ...(query.isActive !== undefined ? { isActive: query.isActive } : {}),
    };

    const [data, total] = await this.prisma.$transaction([
      this.prisma.book.findMany({
        where,
        include: this.bookInclude,
        orderBy: [{ createdAt: "desc" }],
        skip: (page - 1) * limit,
        take: limit,
      }),
      this.prisma.book.count({ where }),
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
    const book = await this.prisma.book.findUnique({
      where: { id },
      include: this.bookInclude,
    });

    if (!book) {
      throw new NotFoundException("Book not found");
    }

    return book;
  }

  async update(id: string, dto: UpdateBookDto, context: MutationContext) {
    const existing = await this.findOne(id);

    await this.ownershipPolicy.assertCanMutateBranch(
      context.actor,
      existing.libraryBranchId,
    );

    if (
      dto.libraryBranchId !== undefined &&
      dto.libraryBranchId !== existing.libraryBranchId
    ) {
      await this.ownershipPolicy.assertCanMutateBranch(
        context.actor,
        dto.libraryBranchId,
      );
    }

    await this.ensureRelationIds(dto.authorIds, dto.categoryIds);

    const updated = await this.prisma.book.update({
      where: { id },
      data: {
        ...(dto.title !== undefined ? { title: dto.title } : {}),
        ...(dto.subtitle !== undefined ? { subtitle: dto.subtitle } : {}),
        ...(dto.description !== undefined
          ? { description: dto.description }
          : {}),
        ...(dto.publishYear !== undefined
          ? { publishYear: dto.publishYear }
          : {}),
        ...(dto.isbn !== undefined ? { isbn: dto.isbn } : {}),
        ...(dto.language !== undefined ? { language: dto.language } : {}),
        ...(dto.libraryBranchId !== undefined
          ? { libraryBranchId: dto.libraryBranchId }
          : {}),
        ...(dto.isActive !== undefined ? { isActive: dto.isActive } : {}),
        ...(dto.authorIds !== undefined
          ? {
              authors: {
                deleteMany: {},
                create: dto.authorIds.map((authorId) => ({ authorId })),
              },
            }
          : {}),
        ...(dto.categoryIds !== undefined
          ? {
              categories: {
                deleteMany: {},
                create: dto.categoryIds.map((categoryId) => ({ categoryId })),
              },
            }
          : {}),
      },
      include: this.bookInclude,
    });

    await this.auditService.write({
      action:
        dto.isActive === false && existing.isActive
          ? "BOOK_DEACTIVATED"
          : "BOOK_UPDATED",
      entityType: "book",
      entityId: updated.id,
      userId: context.actor.id,
      ipAddress: context.ipAddress,
      userAgent: context.userAgent,
      metadata: {
        oldBranchId: existing.libraryBranchId,
        newBranchId: updated.libraryBranchId,
      },
    });

    return updated;
  }

  async deactivate(id: string, context: MutationContext) {
    const existing = await this.findOne(id);
    await this.ownershipPolicy.assertCanMutateBranch(
      context.actor,
      existing.libraryBranchId,
    );

    const updated = await this.prisma.book.update({
      where: { id },
      data: { isActive: false },
      include: this.bookInclude,
    });

    await this.auditService.write({
      action: "BOOK_DEACTIVATED",
      entityType: "book",
      entityId: updated.id,
      userId: context.actor.id,
      ipAddress: context.ipAddress,
      userAgent: context.userAgent,
    });

    return updated;
  }

  async createCopy(
    bookId: string,
    dto: CreateBookCopyDto,
    context: MutationContext,
  ) {
    const book = await this.findOne(bookId);

    if (dto.libraryBranchId !== book.libraryBranchId) {
      throw new BadRequestException(
        "Book copy branch must match the parent book branch",
      );
    }

    await this.ownershipPolicy.assertCanMutateBranch(
      context.actor,
      dto.libraryBranchId,
    );

    const copy = await this.prisma.bookCopy.create({
      data: {
        bookId,
        inventoryNumber: dto.inventoryNumber,
        libraryBranchId: dto.libraryBranchId,
        status: dto.status || CopyStatus.AVAILABLE,
        fund: dto.fund,
        condition: dto.condition,
        acquisitionDate: dto.acquisitionDate
          ? new Date(dto.acquisitionDate)
          : undefined,
      },
    });

    await this.auditService.write({
      action: "BOOK_COPY_CREATED",
      entityType: "book_copy",
      entityId: copy.id,
      userId: context.actor.id,
      ipAddress: context.ipAddress,
      userAgent: context.userAgent,
      metadata: {
        bookId,
        status: copy.status,
      },
    });

    return copy;
  }

  async listCopies(bookId: string) {
    await this.findOne(bookId);
    return this.prisma.bookCopy.findMany({
      where: { bookId },
      orderBy: [{ createdAt: "desc" }],
    });
  }

  async updateCopy(
    bookId: string,
    copyId: string,
    dto: UpdateBookCopyDto,
    context: MutationContext,
  ) {
    const copy = await this.prisma.bookCopy.findFirst({
      where: { id: copyId, bookId },
      include: {
        book: {
          select: {
            id: true,
            libraryBranchId: true,
          },
        },
      },
    });

    if (!copy) {
      throw new NotFoundException("Book copy not found");
    }

    await this.ownershipPolicy.assertCanMutateBranch(
      context.actor,
      copy.libraryBranchId,
    );

    if (
      dto.libraryBranchId !== undefined &&
      dto.libraryBranchId !== copy.libraryBranchId
    ) {
      throw new BadRequestException(
        "Book copy branch must remain aligned with the parent book",
      );
    }

    const updated = await this.prisma.bookCopy.update({
      where: { id: copyId },
      data: {
        ...(dto.inventoryNumber !== undefined
          ? { inventoryNumber: dto.inventoryNumber }
          : {}),
        ...(dto.status !== undefined ? { status: dto.status } : {}),
        ...(dto.fund !== undefined ? { fund: dto.fund } : {}),
        ...(dto.condition !== undefined ? { condition: dto.condition } : {}),
        ...(dto.acquisitionDate !== undefined
          ? {
              acquisitionDate: dto.acquisitionDate
                ? new Date(dto.acquisitionDate)
                : null,
            }
          : {}),
      },
    });

    await this.auditService.write({
      action:
        dto.status !== undefined && dto.status !== copy.status
          ? "BOOK_COPY_STATUS_UPDATED"
          : "BOOK_COPY_UPDATED",
      entityType: "book_copy",
      entityId: updated.id,
      userId: context.actor.id,
      ipAddress: context.ipAddress,
      userAgent: context.userAgent,
      metadata: {
        oldStatus: copy.status,
        newStatus: updated.status,
      },
    });

    return updated;
  }

  private async ensureRelationIds(
    authorIds?: string[],
    categoryIds?: string[],
  ) {
    if (authorIds && authorIds.length > 0) {
      const count = await this.prisma.author.count({
        where: {
          id: { in: authorIds },
          isActive: true,
        },
      });

      if (count !== new Set(authorIds).size) {
        throw new BadRequestException("One or more author IDs are invalid");
      }
    }

    if (categoryIds && categoryIds.length > 0) {
      const count = await this.prisma.category.count({
        where: {
          id: { in: categoryIds },
          isActive: true,
        },
      });

      if (count !== new Set(categoryIds).size) {
        throw new BadRequestException("One or more category IDs are invalid");
      }
    }
  }
}

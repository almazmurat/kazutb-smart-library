import {
  BadRequestException,
  ForbiddenException,
  Injectable,
  NotFoundException,
} from "@nestjs/common";
import {
  Reservation,
  ReservationStatus,
  UserRole,
  Prisma,
} from "@prisma/client";

import { RequestUser } from "../../common/types/request-user.interface";
import { CatalogOwnershipPolicy } from "../../common/policies/catalog-ownership.policy";
import { PrismaService } from "../../prisma/prisma.service";
import { AuditService } from "../audit/audit.service";

import { CreateReservationDto } from "./dto/create-reservation.dto";
import { ListReservationsQueryDto } from "./dto/list-reservations.query.dto";
import { UpdateReservationStatusDto } from "./dto/update-reservation-status.dto";
import {
  ReservationResponseDto,
  ListReservationsResponseDto,
} from "./dto/reservation-response.dto";

interface MutationContext {
  actor: RequestUser;
  ipAddress?: string;
  userAgent?: string;
}

@Injectable()
export class ReservationsService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly auditService: AuditService,
    private readonly ownershipPolicy: CatalogOwnershipPolicy,
  ) {}

  async create(
    dto: CreateReservationDto,
    actor: RequestUser,
    context?: Partial<MutationContext>,
  ): Promise<ReservationResponseDto> {
    // Only authenticated non-guest users can create reservations
    if (!actor.id || actor.role === UserRole.GUEST) {
      throw new ForbiddenException(
        "Only authenticated users can create reservations",
      );
    }

    // Verify book exists and is active
    const book = await this.prisma.book.findUnique({
      where: { id: dto.bookId },
      select: {
        id: true,
        isActive: true,
        libraryBranchId: true,
        copies: {
          where: { status: "AVAILABLE" },
          select: { status: true },
        },
      },
    });

    if (!book) {
      throw new NotFoundException("Book not found");
    }

    if (!book.isActive) {
      throw new BadRequestException("Book is not available");
    }

    // Check if user already has a pending reservation for this book
    const existingReservation = await this.prisma.reservation.findFirst({
      where: {
        userId: actor.id!,
        bookId: dto.bookId,
        status: ReservationStatus.PENDING,
      },
    });

    if (existingReservation) {
      throw new BadRequestException(
        "You already have a pending reservation for this book",
      );
    }

    // Create reservation
    const reservation = await this.prisma.reservation.create({
      data: {
        userId: actor.id!,
        bookId: dto.bookId,
        libraryBranchId: book.libraryBranchId,
        status: ReservationStatus.PENDING,
        expiresAt: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000), // 30 days from now
      },
    });

    // Audit log
    await this.auditService.write({
      action: "RESERVATION_CREATED",
      entityType: "Reservation",
      entityId: reservation.id,
      userId: actor.id,
      metadata: {
        bookId: dto.bookId,
        status: reservation.status,
      },
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
    });

    return this.toResponseDto(reservation);
  }

  async getMyReservations(
    actor: RequestUser,
    query: ListReservationsQueryDto,
  ): Promise<ListReservationsResponseDto> {
    if (!actor.id) {
      throw new ForbiddenException("Authentication required");
    }

    const where: Prisma.ReservationWhereInput = {
      userId: actor.id!,
    };

    if (query.status) {
      where.status = query.status;
    }

    if (query.bookId) {
      where.bookId = query.bookId;
    }

    const skip = ((query.page || 1) - 1) * (query.limit || 20);
    const take = query.limit || 20;

    const [reservations, total] = await Promise.all([
      this.prisma.reservation.findMany({
        where,
        orderBy: { createdAt: "desc" },
        skip,
        take,
      }),
      this.prisma.reservation.count({ where }),
    ]);

    return {
      data: reservations.map((r) => this.toResponseDto(r)),
      meta: {
        total,
        page: query.page || 1,
        limit: query.limit || 20,
        totalPages: Math.ceil(total / (query.limit || 20)),
      },
    };
  }

  async list(
    actor: RequestUser,
    query: ListReservationsQueryDto,
  ): Promise<ListReservationsResponseDto> {
    // Admin can view all reservations
    // Librarian can only view reservations for their branch
    // Other users cannot use this endpoint (checked by controller)

    let where: Prisma.ReservationWhereInput = {};

    if (query.status) {
      where.status = query.status;
    }

    if (query.userId) {
      where.userId = query.userId;
    }

    if (query.bookId) {
      where.bookId = query.bookId;
    }

    // Librarians only see their branch - fetch user to get branch assignment
    if (actor.role === UserRole.LIBRARIAN) {
      const librarianUser = await this.prisma.user.findUnique({
        where: { id: actor.id },
        select: { libraryBranchId: true },
      });
      if (!librarianUser?.libraryBranchId) {
        throw new ForbiddenException(
          "Librarian must be assigned to a library branch",
        );
      }
      where.libraryBranchId = librarianUser.libraryBranchId;
    }

    if (query.branchId) {
      // Librarian cannot filter by other branches
      if (actor.role === UserRole.LIBRARIAN) {
        const librarianUser = await this.prisma.user.findUnique({
          where: { id: actor.id },
          select: { libraryBranchId: true },
        });
        if (query.branchId !== librarianUser?.libraryBranchId) {
          throw new ForbiddenException("Cannot view other branches");
        }
      }
      where.libraryBranchId = query.branchId;
    }

    const skip = ((query.page || 1) - 1) * (query.limit || 20);
    const take = query.limit || 20;

    const [reservations, total] = await Promise.all([
      this.prisma.reservation.findMany({
        where,
        include: {
          user: {
            select: {
              id: true,
              universityId: true,
              fullName: true,
              email: true,
            },
          },
          book: {
            select: {
              id: true,
              title: true,
            },
          },
          libraryBranch: {
            select: {
              id: true,
              name: true,
            },
          },
        },
        orderBy: { createdAt: "desc" },
        skip,
        take,
      }),
      this.prisma.reservation.count({ where }),
    ]);

    return {
      data: reservations.map((r) => this.toResponseDto(r)),
      meta: {
        total,
        page: query.page || 1,
        limit: query.limit || 20,
        totalPages: Math.ceil(total / (query.limit || 20)),
      },
    };
  }

  async getById(
    reservationId: string,
    actor: RequestUser,
  ): Promise<ReservationResponseDto> {
    const reservation = await this.prisma.reservation.findUnique({
      where: { id: reservationId },
    });

    if (!reservation) {
      throw new NotFoundException("Reservation not found");
    }

    // User can view their own reservation
    // Librarian can view any reservation in their branch
    // Admin can view any
    if (actor.role !== UserRole.ADMIN) {
      if (actor.role === UserRole.LIBRARIAN) {
        const librarianUser = await this.prisma.user.findUnique({
          where: { id: actor.id },
          select: { libraryBranchId: true },
        });
        if (reservation.libraryBranchId !== librarianUser?.libraryBranchId) {
          throw new ForbiddenException(
            "Cannot access reservations outside your branch",
          );
        }
      } else {
        if (reservation.userId !== actor.id) {
          throw new ForbiddenException(
            "Cannot access other users reservations",
          );
        }
      }
    }

    return this.toResponseDto(reservation);
  }

  async cancel(
    reservationId: string,
    actor: RequestUser,
    context?: Partial<MutationContext>,
  ): Promise<ReservationResponseDto> {
    const reservation = await this.prisma.reservation.findUnique({
      where: { id: reservationId },
    });

    if (!reservation) {
      throw new NotFoundException("Reservation not found");
    }

    // User can only cancel their own reservations
    if (actor.id !== reservation.userId) {
      throw new ForbiddenException("Cannot cancel another users reservation");
    }

    // Can only cancel if pending or ready
    if (
      reservation.status !== ReservationStatus.PENDING &&
      reservation.status !== ReservationStatus.READY
    ) {
      throw new BadRequestException(
        `Cannot cancel reservation in ${reservation.status} status`,
      );
    }

    const updated = await this.prisma.reservation.update({
      where: { id: reservationId },
      data: {
        status: ReservationStatus.CANCELLED,
        updatedAt: new Date(),
      },
    });

    // Audit log
    await this.auditService.write({
      action: "RESERVATION_CANCELLED",
      entityType: "Reservation",
      entityId: updated.id,
      userId: actor.id,
      metadata: {
        previousStatus: reservation.status,
      },
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
    });

    return this.toResponseDto(updated);
  }

  async updateStatus(
    reservationId: string,
    dto: UpdateReservationStatusDto,
    actor: RequestUser,
    context?: Partial<MutationContext>,
  ): Promise<ReservationResponseDto> {
    const reservation = await this.prisma.reservation.findUnique({
      where: { id: reservationId },
    });

    if (!reservation) {
      throw new NotFoundException("Reservation not found");
    }

    // Only admin and librarians (in their branch) can update status
    if (actor.role !== UserRole.ADMIN) {
      if (actor.role !== UserRole.LIBRARIAN) {
        throw new ForbiddenException(
          "Only librarians can update reservation status",
        );
      }
      const librarianUser = await this.prisma.user.findUnique({
        where: { id: actor.id },
        select: { libraryBranchId: true },
      });
      if (reservation.libraryBranchId !== librarianUser?.libraryBranchId) {
        throw new ForbiddenException(
          "Cannot manage reservations outside your branch",
        );
      }
    }

    const updated = await this.prisma.reservation.update({
      where: { id: reservationId },
      data: {
        status: dto.status,
        notes: dto.notes,
        processedAt: new Date(),
        processedByUserId: actor.id,
      },
    });

    // Audit log
    await this.auditService.write({
      action: "RESERVATION_STATUS_UPDATED",
      entityType: "Reservation",
      entityId: updated.id,
      userId: actor.id,
      metadata: {
        previousStatus: reservation.status,
        newStatus: dto.status,
        notes: dto.notes,
      },
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
    });

    return this.toResponseDto(updated);
  }

  private toResponseDto(reservation: Reservation): ReservationResponseDto {
    return {
      id: reservation.id,
      status: reservation.status,
      reservedAt: reservation.reservedAt,
      expiresAt: reservation.expiresAt ?? undefined,
      processedAt: reservation.processedAt ?? undefined,
      notes: reservation.notes ?? undefined,
      userId: reservation.userId,
      bookId: reservation.bookId,
      libraryBranchId: reservation.libraryBranchId,
      processedByUserId: reservation.processedByUserId ?? undefined,
      createdAt: reservation.createdAt,
      updatedAt: reservation.updatedAt,
    };
  }
}

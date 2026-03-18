/// <reference types="jest" />

import { describe, expect, it, jest } from "@jest/globals";
import { BadRequestException, ForbiddenException } from "@nestjs/common";
import { ReservationStatus } from "@prisma/client";

import { UserRole } from "../../common/types/user-role.enum";
import { ReservationsService } from "./reservations.service";

describe("ReservationsService", () => {
  function createService() {
    const prisma = {
      book: {
        findUnique: jest.fn(),
      },
      reservation: {
        findFirst: jest.fn(),
        create: jest.fn(),
        findUnique: jest.fn(),
        update: jest.fn(),
        findMany: jest.fn(),
        count: jest.fn(),
      },
      user: {
        findUnique: jest.fn(),
      },
    } as any;

    const auditService = { write: jest.fn() } as any;
    const ownershipPolicy = { assertCanMutateBranch: jest.fn() } as any;

    const service = new ReservationsService(
      prisma,
      auditService,
      ownershipPolicy,
    );
    return { service, prisma, auditService };
  }

  function reservationFixture(overrides: Record<string, unknown> = {}) {
    return {
      id: "reservation-1",
      status: ReservationStatus.PENDING,
      reservedAt: new Date("2026-03-18T10:00:00.000Z"),
      expiresAt: new Date("2026-04-17T10:00:00.000Z"),
      processedAt: null,
      notes: null,
      userId: "student-1",
      bookId: "book-1",
      libraryBranchId: "branch-1",
      processedByUserId: null,
      createdAt: new Date("2026-03-18T10:00:00.000Z"),
      updatedAt: new Date("2026-03-18T10:00:00.000Z"),
      ...overrides,
    };
  }

  it("creates reservation successfully for authenticated STUDENT", async () => {
    const { service, prisma, auditService } = createService();

    prisma.book.findUnique.mockResolvedValue({
      id: "book-1",
      isActive: true,
      libraryBranchId: "branch-1",
      copies: [{ status: "AVAILABLE" }],
    });
    prisma.reservation.findFirst.mockResolvedValue(null);
    prisma.reservation.create.mockResolvedValue(reservationFixture());

    const result = await service.create(
      { bookId: "book-1" },
      {
        id: "student-1",
        universityId: "u-1001",
        email: "student@kazutb.edu.kz",
        fullName: "Test Student",
        role: UserRole.STUDENT,
      },
    );

    expect(result.status).toBe(ReservationStatus.PENDING);
    expect(prisma.reservation.create).toHaveBeenCalledWith(
      expect.objectContaining({
        data: expect.objectContaining({
          userId: "student-1",
          bookId: "book-1",
          libraryBranchId: "branch-1",
          status: ReservationStatus.PENDING,
        }),
      }),
    );
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({
        action: "RESERVATION_CREATED",
        entityType: "Reservation",
        entityId: "reservation-1",
      }),
    );
  });

  it("denies reservation creation for GUEST", async () => {
    const { service } = createService();

    await expect(
      service.create(
        { bookId: "book-1" },
        {
          id: "guest-1",
          universityId: "guest",
          email: "guest@kazutb.edu.kz",
          fullName: "Guest",
          role: UserRole.GUEST,
        },
      ),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });

  it("denies duplicate pending reservation for same user and book", async () => {
    const { service, prisma } = createService();

    prisma.book.findUnique.mockResolvedValue({
      id: "book-1",
      isActive: true,
      libraryBranchId: "branch-1",
      copies: [{ status: "AVAILABLE" }],
    });
    prisma.reservation.findFirst.mockResolvedValue(
      reservationFixture({ id: "reservation-existing" }),
    );

    await expect(
      service.create(
        { bookId: "book-1" },
        {
          id: "student-1",
          universityId: "u-1001",
          email: "student@kazutb.edu.kz",
          fullName: "Test Student",
          role: UserRole.STUDENT,
        },
      ),
    ).rejects.toBeInstanceOf(BadRequestException);
  });

  it("denies librarian status management for reservation in another branch", async () => {
    const { service, prisma } = createService();

    prisma.reservation.findUnique.mockResolvedValue(
      reservationFixture({
        id: "reservation-foreign",
        libraryBranchId: "branch-2",
      }),
    );
    prisma.user.findUnique.mockResolvedValue({ libraryBranchId: "branch-1" });

    await expect(
      service.updateStatus(
        "reservation-foreign",
        { status: ReservationStatus.READY },
        {
          id: "librarian-1",
          universityId: "lib-1",
          email: "librarian@kazutb.edu.kz",
          fullName: "Branch Librarian",
          role: UserRole.LIBRARIAN,
        },
      ),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });

  it("forbids user cancellation for finalized reservation status", async () => {
    const { service, prisma } = createService();

    prisma.reservation.findUnique.mockResolvedValue(
      reservationFixture({
        status: ReservationStatus.FULFILLED,
        userId: "student-1",
      }),
    );

    await expect(
      service.cancel("reservation-1", {
        id: "student-1",
        universityId: "u-1001",
        email: "student@kazutb.edu.kz",
        fullName: "Test Student",
        role: UserRole.STUDENT,
      }),
    ).rejects.toBeInstanceOf(BadRequestException);
  });
});

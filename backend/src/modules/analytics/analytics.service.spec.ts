/// <reference types="jest" />

import { describe, expect, it, jest } from "@jest/globals";
import { ForbiddenException } from "@nestjs/common";

import { UserRole } from "../../common/types/user-role.enum";
import { AnalyticsService } from "./analytics.service";

describe("AnalyticsService", () => {
  function createService() {
    const prisma = {
      book: { count: jest.fn(), findMany: jest.fn() },
      bookCopy: { count: jest.fn() },
      reservation: { count: jest.fn() },
      loan: { count: jest.fn() },
      user: { count: jest.fn(), findUnique: jest.fn() },
      libraryBranch: { findMany: jest.fn() },
    } as any;

    const service = new AnalyticsService(prisma);
    return { service, prisma };
  }

  const adminActor = {
    id: "admin-1",
    universityId: "adm-001",
    email: "admin@kazutb.edu.kz",
    fullName: "Admin",
    role: UserRole.ADMIN,
  };

  const librarianActor = {
    id: "librarian-1",
    universityId: "lib-001",
    email: "librarian@kazutb.edu.kz",
    fullName: "Librarian",
    role: UserRole.LIBRARIAN,
  };

  const studentActor = {
    id: "student-1",
    universityId: "stu-001",
    email: "student@kazutb.edu.kz",
    fullName: "Student",
    role: UserRole.STUDENT,
  };

  // ─── getDashboard ─────────────────────────────────────────

  it("returns global dashboard for admin", async () => {
    const { service, prisma } = createService();

    prisma.book.count.mockResolvedValue(100);
    prisma.bookCopy.count.mockResolvedValue(250);
    prisma.reservation.count.mockResolvedValue(5);
    prisma.loan.count
      .mockResolvedValueOnce(12) // active loans
      .mockResolvedValueOnce(3); // overdue loans
    prisma.user.count.mockResolvedValue(80);
    prisma.libraryBranch.findMany.mockResolvedValue([
      { id: "b1", name: "Economic Library", code: "ECONOMIC_LIBRARY" },
    ]);

    const result = await service.getDashboard(adminActor);

    expect(result.scope).toBe("global");
    expect(result.totalBooks).toBe(100);
    expect(result.totalCopies).toBe(250);
    expect(result.activeLoans).toBe(12);
    expect(result.overdueLoans).toBe(3);
    expect(result.totalUsers).toBe(80);
    expect(result.branches).toHaveLength(1);
  });

  it("returns branch-scoped dashboard for librarian", async () => {
    const { service, prisma } = createService();

    prisma.user.findUnique.mockResolvedValue({
      libraryBranchId: "branch-1",
    });
    prisma.book.count.mockResolvedValue(40);
    prisma.bookCopy.count.mockResolvedValue(90);
    prisma.reservation.count.mockResolvedValue(2);
    prisma.loan.count
      .mockResolvedValueOnce(5) // active
      .mockResolvedValueOnce(1); // overdue
    prisma.user.count.mockResolvedValue(10);
    prisma.libraryBranch.findMany.mockResolvedValue([
      { id: "branch-1", name: "Tech Library", code: "TECHNOLOGICAL_LIBRARY" },
    ]);

    const result = await service.getDashboard(librarianActor);

    expect(result.scope).toBe("branch");
    expect(result.totalBooks).toBe(40);
    // Verify branch filter was applied to book count
    expect(prisma.book.count).toHaveBeenCalledWith(
      expect.objectContaining({
        where: expect.objectContaining({ libraryBranchId: "branch-1" }),
      }),
    );
  });

  it("denies dashboard access to student", async () => {
    const { service } = createService();

    await expect(service.getDashboard(studentActor)).rejects.toBeInstanceOf(
      ForbiddenException,
    );
  });

  // ─── getPopularBooks ──────────────────────────────────────

  it("returns scored popular books for admin", async () => {
    const { service, prisma } = createService();

    prisma.book.findMany.mockResolvedValue([
      {
        id: "book-1",
        title: "Algebra",
        publishYear: 2020,
        language: "ru",
        libraryBranch: { id: "b1", name: "Economic" },
        authors: [{ author: { fullName: "Author A" } }],
        _count: { reservations: 3 },
        copies: [{ _count: { loans: 5 } }, { _count: { loans: 2 } }],
      },
      {
        id: "book-2",
        title: "Physics",
        publishYear: 2021,
        language: "kk",
        libraryBranch: { id: "b1", name: "Economic" },
        authors: [],
        _count: { reservations: 0 },
        copies: [{ _count: { loans: 0 } }],
      },
    ]);

    const result = await service.getPopularBooks(adminActor, 10);

    expect(result.data).toHaveLength(1); // Physics filtered out (score=0)
    expect(result.data[0].title).toBe("Algebra");
    expect(result.data[0].loanCount).toBe(7);
    expect(result.data[0].reservationCount).toBe(3);
    expect(result.data[0].score).toBe(7 * 2 + 3); // 17
    expect(result.rankingLogic).toContain("loanCount * 2");
  });

  // ─── getActivity ──────────────────────────────────────────

  it("returns activity summary for admin (global scope)", async () => {
    const { service, prisma } = createService();

    // 9 count calls: 3 for reservations, 3 for loans, 3 for returns
    for (let i = 0; i < 9; i++) {
      if (i < 3) prisma.reservation.count.mockResolvedValueOnce(i + 1);
      else if (i < 6) prisma.loan.count.mockResolvedValueOnce(i + 1);
      else prisma.loan.count.mockResolvedValueOnce(i + 1);
    }

    const result = await service.getActivity(adminActor);

    expect(result.scope).toBe("global");
    expect(result.reservations).toHaveProperty("today");
    expect(result.reservations).toHaveProperty("last7days");
    expect(result.reservations).toHaveProperty("last30days");
    expect(result.loans).toHaveProperty("today");
    expect(result.returns).toHaveProperty("today");
  });
});

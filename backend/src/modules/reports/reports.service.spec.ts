/// <reference types="jest" />

import { describe, expect, it, jest } from "@jest/globals";
import { ForbiddenException } from "@nestjs/common";

import { UserRole } from "../../common/types/user-role.enum";
import { ReportsService } from "./reports.service";

describe("ReportsService", () => {
  function createService() {
    const prisma = {
      loan: { count: jest.fn() },
      reservation: { count: jest.fn() },
      user: { findUnique: jest.fn() },
      libraryBranch: { findMany: jest.fn() },
    } as any;

    const service = new ReportsService(prisma);
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

  it("returns overview with yearly and monthly data for admin (global)", async () => {
    const { service, prisma } = createService();

    // Yearly: 4 counts (loans, returns, reservations, overdue)
    prisma.loan.count.mockResolvedValue(0);
    prisma.reservation.count.mockResolvedValue(0);

    // Branches for global summary
    prisma.libraryBranch.findMany.mockResolvedValue([
      { id: "b1", name: "Economic Library" },
    ]);

    const result = await service.getOverview(adminActor, 2026);

    expect(result.year).toBe(2026);
    expect(result.scope).toBe("global");
    expect(result.yearly).toHaveProperty("loans");
    expect(result.yearly).toHaveProperty("returns");
    expect(result.yearly).toHaveProperty("reservations");
    expect(result.yearly).toHaveProperty("currentOverdue");
    expect(Array.isArray(result.monthly)).toBe(true);
    expect(result.monthly.length).toBeGreaterThan(0);
    // Each monthly entry has the right shape
    expect(result.monthly[0]).toHaveProperty("month");
    expect(result.monthly[0]).toHaveProperty("loans");
    expect(result.monthly[0]).toHaveProperty("returns");
    expect(result.monthly[0]).toHaveProperty("reservations");
    // Branch summary included for global scope
    expect(Array.isArray(result.branchSummary)).toBe(true);
  });

  it("returns branch-scoped report for librarian", async () => {
    const { service, prisma } = createService();

    prisma.user.findUnique.mockResolvedValue({
      libraryBranchId: "branch-1",
    });
    prisma.loan.count.mockResolvedValue(0);
    prisma.reservation.count.mockResolvedValue(0);

    const result = await service.getOverview(librarianActor, 2026);

    expect(result.scope).toBe("branch");
    // Branch summary should be empty for branch-scoped view
    expect(result.branchSummary).toEqual([]);
  });

  it("denies report access to student", async () => {
    const { service } = createService();

    const studentActor = {
      id: "student-1",
      universityId: "stu-001",
      email: "student@kazutb.edu.kz",
      fullName: "Student",
      role: UserRole.STUDENT,
    };

    await expect(
      service.getOverview(studentActor, 2026),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });
});

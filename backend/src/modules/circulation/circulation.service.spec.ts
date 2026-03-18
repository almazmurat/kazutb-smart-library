/// <reference types="jest" />

import { describe, expect, it, jest } from "@jest/globals";
import {
  BadRequestException,
  ForbiddenException,
  NotFoundException,
} from "@nestjs/common";
import { CopyStatus, LoanStatus } from "@prisma/client";

import { UserRole } from "../../common/types/user-role.enum";
import { CirculationService } from "./circulation.service";

describe("CirculationService", () => {
  function createService() {
    const prisma = {
      bookCopy: {
        findUnique: jest.fn(),
        update: jest.fn(),
      },
      loan: {
        create: jest.fn(),
        findUnique: jest.fn(),
        findMany: jest.fn(),
        update: jest.fn(),
        count: jest.fn(),
      },
      user: {
        findUnique: jest.fn(),
      },
      $transaction: jest.fn(),
    } as any;

    const auditService = { write: jest.fn() } as any;

    const service = new CirculationService(prisma, auditService);
    return { service, prisma, auditService };
  }

  const librarianActor = {
    id: "librarian-1",
    universityId: "lib-001",
    email: "librarian@kazutb.edu.kz",
    fullName: "Test Librarian",
    role: UserRole.LIBRARIAN,
  };

  const adminActor = {
    id: "admin-1",
    universityId: "adm-001",
    email: "admin@kazutb.edu.kz",
    fullName: "Test Admin",
    role: UserRole.ADMIN,
  };

  const studentActor = {
    id: "student-1",
    universityId: "stu-001",
    email: "student@kazutb.edu.kz",
    fullName: "Test Student",
    role: UserRole.STUDENT,
  };

  const sampleCopy = {
    id: "copy-1",
    status: CopyStatus.AVAILABLE,
    libraryBranchId: "branch-1",
    book: { id: "book-1", title: "Test Book" },
  };

  const sampleLoan = {
    id: "loan-1",
    userId: "student-1",
    copyId: "copy-1",
    libraryBranchId: "branch-1",
    issuedBy: "librarian-1",
    loanedAt: new Date("2026-03-18T10:00:00.000Z"),
    dueDate: new Date("2026-04-01T10:00:00.000Z"),
    returnedAt: null,
    notes: null,
    status: LoanStatus.ACTIVE,
    createdAt: new Date("2026-03-18T10:00:00.000Z"),
    updatedAt: new Date("2026-03-18T10:00:00.000Z"),
  };

  // ─── issueLoan ───────────────────────────────────────────────

  it("issues loan successfully for librarian with available copy in own branch", async () => {
    const { service, prisma, auditService } = createService();

    prisma.bookCopy.findUnique.mockResolvedValue(sampleCopy);
    prisma.user.findUnique
      .mockResolvedValueOnce({ libraryBranchId: "branch-1" }) // librarian lookup
      .mockResolvedValueOnce({ id: "student-1", isActive: true }); // borrower lookup
    prisma.$transaction.mockResolvedValue([sampleLoan]);

    const result = await service.issueLoan(
      { userId: "student-1", copyId: "copy-1" },
      librarianActor,
    );

    expect(result.id).toBe("loan-1");
    expect(result.status).toBe(LoanStatus.ACTIVE);
    expect(prisma.$transaction).toHaveBeenCalled();
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({
        action: "LOAN_CREATED",
        entityType: "Loan",
        entityId: "loan-1",
      }),
    );
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({
        action: "COPY_STATUS_UPDATED",
        entityType: "BookCopy",
        entityId: "copy-1",
      }),
    );
  });

  it("issues loan successfully for admin (any branch)", async () => {
    const { service, prisma, auditService } = createService();

    prisma.bookCopy.findUnique.mockResolvedValue({
      ...sampleCopy,
      libraryBranchId: "branch-99",
    });
    // Admin — no librarian user lookup triggered, only borrower lookup
    prisma.user.findUnique.mockResolvedValueOnce({
      id: "student-1",
      isActive: true,
    });
    prisma.$transaction.mockResolvedValue([
      { ...sampleLoan, libraryBranchId: "branch-99" },
    ]);

    const result = await service.issueLoan(
      { userId: "student-1", copyId: "copy-1" },
      adminActor,
    );

    expect(result.id).toBe("loan-1");
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({ action: "LOAN_CREATED" }),
    );
  });

  it("denies loan creation when copy is not AVAILABLE", async () => {
    const { service, prisma } = createService();

    prisma.bookCopy.findUnique.mockResolvedValue({
      ...sampleCopy,
      status: CopyStatus.LOANED,
    });
    prisma.user.findUnique.mockResolvedValueOnce({
      libraryBranchId: "branch-1",
    });

    await expect(
      service.issueLoan(
        { userId: "student-1", copyId: "copy-1" },
        librarianActor,
      ),
    ).rejects.toBeInstanceOf(BadRequestException);
  });

  it("denies loan creation for copy in another branch (librarian)", async () => {
    const { service, prisma } = createService();

    prisma.bookCopy.findUnique.mockResolvedValue({
      ...sampleCopy,
      libraryBranchId: "branch-2",
    });
    prisma.user.findUnique.mockResolvedValueOnce({
      libraryBranchId: "branch-1",
    });

    await expect(
      service.issueLoan(
        { userId: "student-1", copyId: "copy-1" },
        librarianActor,
      ),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });

  it("denies loan creation for student role", async () => {
    const { service } = createService();

    await expect(
      service.issueLoan(
        { userId: "student-1", copyId: "copy-1" },
        studentActor,
      ),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });

  // ─── returnLoan ──────────────────────────────────────────────

  it("returns loan successfully and restores copy to AVAILABLE", async () => {
    const { service, prisma, auditService } = createService();

    prisma.loan.findUnique.mockResolvedValue({
      ...sampleLoan,
      copy: { id: "copy-1", status: CopyStatus.LOANED, libraryBranchId: "branch-1" },
    });
    prisma.user.findUnique.mockResolvedValueOnce({
      libraryBranchId: "branch-1",
    });
    prisma.$transaction.mockResolvedValue([
      { ...sampleLoan, status: LoanStatus.RETURNED, returnedAt: new Date() },
    ]);

    const result = await service.returnLoan("loan-1", {}, librarianActor);

    expect(result.status).toBe(LoanStatus.RETURNED);
    expect(prisma.$transaction).toHaveBeenCalled();
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({ action: "LOAN_RETURNED" }),
    );
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({
        action: "COPY_STATUS_UPDATED",
        entityType: "BookCopy",
        entityId: "copy-1",
      }),
    );
  });

  it("allows return of overdue loan", async () => {
    const { service, prisma } = createService();

    prisma.loan.findUnique.mockResolvedValue({
      ...sampleLoan,
      status: LoanStatus.OVERDUE,
      copy: { id: "copy-1", status: CopyStatus.LOANED, libraryBranchId: "branch-1" },
    });
    prisma.user.findUnique.mockResolvedValueOnce({
      libraryBranchId: "branch-1",
    });
    prisma.$transaction.mockResolvedValue([
      { ...sampleLoan, status: LoanStatus.RETURNED, returnedAt: new Date() },
    ]);

    const result = await service.returnLoan("loan-1", {}, librarianActor);
    expect(result.status).toBe(LoanStatus.RETURNED);
  });

  it("denies return for already-returned loan", async () => {
    const { service, prisma } = createService();

    prisma.loan.findUnique.mockResolvedValue({
      ...sampleLoan,
      status: LoanStatus.RETURNED,
      copy: { id: "copy-1", status: CopyStatus.AVAILABLE, libraryBranchId: "branch-1" },
    });
    prisma.user.findUnique.mockResolvedValueOnce({
      libraryBranchId: "branch-1",
    });

    await expect(
      service.returnLoan("loan-1", {}, librarianActor),
    ).rejects.toBeInstanceOf(BadRequestException);
  });

  it("denies return for loan in another branch (librarian)", async () => {
    const { service, prisma } = createService();

    prisma.loan.findUnique.mockResolvedValue({
      ...sampleLoan,
      libraryBranchId: "branch-2",
      copy: { id: "copy-1", status: CopyStatus.LOANED, libraryBranchId: "branch-2" },
    });
    prisma.user.findUnique.mockResolvedValueOnce({
      libraryBranchId: "branch-1",
    });

    await expect(
      service.returnLoan("loan-1", {}, librarianActor),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });

  // ─── getById / visibility ────────────────────────────────────

  it("allows student to view their own loan", async () => {
    const { service, prisma } = createService();

    prisma.loan.findUnique.mockResolvedValue({
      ...sampleLoan,
      userId: "student-1",
    });

    const result = await service.getById("loan-1", studentActor);
    expect(result.id).toBe("loan-1");
  });

  it("denies student access to another user's loan", async () => {
    const { service, prisma } = createService();

    prisma.loan.findUnique.mockResolvedValue({
      ...sampleLoan,
      userId: "other-student",
    });

    await expect(
      service.getById("loan-1", studentActor),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });

  it("allows admin to view any loan", async () => {
    const { service, prisma } = createService();

    prisma.loan.findUnique.mockResolvedValue({
      ...sampleLoan,
      userId: "other-student",
    });

    const result = await service.getById("loan-1", adminActor);
    expect(result.id).toBe("loan-1");
  });
});

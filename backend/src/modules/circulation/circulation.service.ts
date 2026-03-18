import {
  BadRequestException,
  ForbiddenException,
  Injectable,
  NotFoundException,
} from "@nestjs/common";
import { CopyStatus, LoanStatus, Prisma, UserRole } from "@prisma/client";

import { RequestUser } from "../../common/types/request-user.interface";
import { PrismaService } from "../../prisma/prisma.service";
import { AuditService } from "../audit/audit.service";

import { CreateLoanDto } from "./dto/create-loan.dto";
import { ReturnLoanDto } from "./dto/return-loan.dto";
import { ListLoansQueryDto } from "./dto/list-loans.query.dto";
import { LoanResponseDto, ListLoansResponseDto } from "./dto/loan-response.dto";

const DEFAULT_LOAN_DAYS = 14;

interface MutationContext {
  actor: RequestUser;
  ipAddress?: string;
  userAgent?: string;
}

@Injectable()
export class CirculationService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly auditService: AuditService,
  ) {}

  async issueLoan(
    dto: CreateLoanDto,
    actor: RequestUser,
    context?: Partial<MutationContext>,
  ): Promise<LoanResponseDto> {
    if (actor.role !== UserRole.LIBRARIAN && actor.role !== UserRole.ADMIN) {
      throw new ForbiddenException("Only librarians or admins can issue loans");
    }

    const copy = await this.prisma.bookCopy.findUnique({
      where: { id: dto.copyId },
      select: {
        id: true,
        status: true,
        libraryBranchId: true,
        book: { select: { id: true, title: true } },
      },
    });

    if (!copy) {
      throw new NotFoundException("Book copy not found");
    }

    // Branch ownership check for librarians
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

      if (librarianUser.libraryBranchId !== copy.libraryBranchId) {
        throw new ForbiddenException(
          "Cannot issue loans for copies outside your branch",
        );
      }
    }

    if (copy.status !== CopyStatus.AVAILABLE) {
      throw new BadRequestException(
        `Copy is not available for loan (current status: ${copy.status})`,
      );
    }

    const borrower = await this.prisma.user.findUnique({
      where: { id: dto.userId },
      select: { id: true, isActive: true },
    });

    if (!borrower) {
      throw new NotFoundException("Borrower user not found");
    }

    if (!borrower.isActive) {
      throw new BadRequestException("Borrower account is not active");
    }

    const dueDate = dto.dueDate
      ? new Date(dto.dueDate)
      : new Date(Date.now() + DEFAULT_LOAN_DAYS * 24 * 60 * 60 * 1000);

    // Atomically create loan and update copy status
    const [loan] = await this.prisma.$transaction([
      this.prisma.loan.create({
        data: {
          userId: dto.userId,
          copyId: dto.copyId,
          libraryBranchId: copy.libraryBranchId,
          issuedBy: actor.id,
          dueDate,
          notes: dto.notes,
          status: LoanStatus.ACTIVE,
        },
      }),
      this.prisma.bookCopy.update({
        where: { id: dto.copyId },
        data: { status: CopyStatus.LOANED },
      }),
    ]);

    await this.auditService.write({
      action: "LOAN_CREATED",
      entityType: "Loan",
      entityId: loan.id,
      userId: actor.id,
      metadata: {
        borrowerUserId: dto.userId,
        copyId: dto.copyId,
        dueDate: dueDate.toISOString(),
      },
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
    });

    await this.auditService.write({
      action: "COPY_STATUS_UPDATED",
      entityType: "BookCopy",
      entityId: dto.copyId,
      userId: actor.id,
      metadata: {
        previousStatus: CopyStatus.AVAILABLE,
        newStatus: CopyStatus.LOANED,
        reason: "loan_issued",
        loanId: loan.id,
      },
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
    });

    return this.toResponseDto(loan);
  }

  async returnLoan(
    loanId: string,
    dto: ReturnLoanDto,
    actor: RequestUser,
    context?: Partial<MutationContext>,
  ): Promise<LoanResponseDto> {
    if (actor.role !== UserRole.LIBRARIAN && actor.role !== UserRole.ADMIN) {
      throw new ForbiddenException(
        "Only librarians or admins can process returns",
      );
    }

    const loan = await this.prisma.loan.findUnique({
      where: { id: loanId },
      include: {
        copy: { select: { id: true, status: true, libraryBranchId: true } },
      },
    });

    if (!loan) {
      throw new NotFoundException("Loan not found");
    }

    // Branch ownership check for librarians
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

      if (librarianUser.libraryBranchId !== loan.libraryBranchId) {
        throw new ForbiddenException(
          "Cannot process returns for loans outside your branch",
        );
      }
    }

    if (
      loan.status !== LoanStatus.ACTIVE &&
      loan.status !== LoanStatus.OVERDUE
    ) {
      throw new BadRequestException(
        `Cannot return loan in ${loan.status} status`,
      );
    }

    const previousCopyStatus = loan.copy.status;

    const [updatedLoan] = await this.prisma.$transaction([
      this.prisma.loan.update({
        where: { id: loanId },
        data: {
          status: LoanStatus.RETURNED,
          returnedAt: new Date(),
          notes: dto.notes
            ? loan.notes
              ? `${loan.notes}\n---\n${dto.notes}`
              : dto.notes
            : loan.notes,
        },
      }),
      this.prisma.bookCopy.update({
        where: { id: loan.copyId },
        data: { status: CopyStatus.AVAILABLE },
      }),
    ]);

    await this.auditService.write({
      action: "LOAN_RETURNED",
      entityType: "Loan",
      entityId: loanId,
      userId: actor.id,
      metadata: {
        borrowerUserId: loan.userId,
        copyId: loan.copyId,
        previousStatus: loan.status,
        wasOverdue: loan.status === LoanStatus.OVERDUE,
      },
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
    });

    await this.auditService.write({
      action: "COPY_STATUS_UPDATED",
      entityType: "BookCopy",
      entityId: loan.copyId,
      userId: actor.id,
      metadata: {
        previousStatus: previousCopyStatus,
        newStatus: CopyStatus.AVAILABLE,
        reason: "loan_returned",
        loanId,
      },
      ipAddress: context?.ipAddress,
      userAgent: context?.userAgent,
    });

    return this.toResponseDto(updatedLoan);
  }

  async list(
    actor: RequestUser,
    query: ListLoansQueryDto,
  ): Promise<ListLoansResponseDto> {
    const where: Prisma.LoanWhereInput = {};

    if (query.status) {
      where.status = query.status;
    }

    if (query.userId) {
      where.userId = query.userId;
    }

    if (query.copyId) {
      where.copyId = query.copyId;
    }

    if (query.overdueOnly) {
      where.status = LoanStatus.ACTIVE;
      where.dueDate = { lt: new Date() };
    }

    // Librarians only see their branch
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

      if (query.branchId && query.branchId !== librarianUser.libraryBranchId) {
        throw new ForbiddenException("Cannot view loans from other branches");
      }
    } else if (query.branchId) {
      where.libraryBranchId = query.branchId;
    }

    const skip = ((query.page || 1) - 1) * (query.limit || 20);
    const take = query.limit || 20;

    const [loans, total] = await Promise.all([
      this.prisma.loan.findMany({
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
          copy: {
            select: {
              id: true,
              inventoryNumber: true,
              book: { select: { id: true, title: true } },
            },
          },
          libraryBranch: { select: { id: true, name: true } },
        },
        orderBy: { createdAt: "desc" },
        skip,
        take,
      }),
      this.prisma.loan.count({ where }),
    ]);

    return {
      data: loans.map((l) => this.toResponseDto(l)),
      meta: {
        total,
        page: query.page || 1,
        limit: query.limit || 20,
        totalPages: Math.ceil(total / (query.limit || 20)),
      },
    };
  }

  async getById(loanId: string, actor: RequestUser): Promise<LoanResponseDto> {
    const loan = await this.prisma.loan.findUnique({
      where: { id: loanId },
      include: {
        user: {
          select: {
            id: true,
            universityId: true,
            fullName: true,
            email: true,
          },
        },
        copy: {
          select: {
            id: true,
            inventoryNumber: true,
            book: { select: { id: true, title: true } },
          },
        },
        libraryBranch: { select: { id: true, name: true } },
      },
    });

    if (!loan) {
      throw new NotFoundException("Loan not found");
    }

    if (actor.role === UserRole.ADMIN) {
      return this.toResponseDto(loan);
    }

    if (actor.role === UserRole.LIBRARIAN) {
      const librarianUser = await this.prisma.user.findUnique({
        where: { id: actor.id },
        select: { libraryBranchId: true },
      });

      if (loan.libraryBranchId !== librarianUser?.libraryBranchId) {
        throw new ForbiddenException("Cannot access loans outside your branch");
      }

      return this.toResponseDto(loan);
    }

    if (loan.userId !== actor.id) {
      throw new ForbiddenException("Cannot access other users' loans");
    }

    return this.toResponseDto(loan);
  }

  async getMyLoans(
    actor: RequestUser,
    query: ListLoansQueryDto,
  ): Promise<ListLoansResponseDto> {
    if (!actor.id) {
      throw new ForbiddenException("Authentication required");
    }

    const where: Prisma.LoanWhereInput = {
      userId: actor.id,
    };

    if (query.status) {
      where.status = query.status;
    }

    const skip = ((query.page || 1) - 1) * (query.limit || 20);
    const take = query.limit || 20;

    const [loans, total] = await Promise.all([
      this.prisma.loan.findMany({
        where,
        include: {
          copy: {
            select: {
              id: true,
              inventoryNumber: true,
              book: { select: { id: true, title: true } },
            },
          },
          libraryBranch: { select: { id: true, name: true } },
        },
        orderBy: { createdAt: "desc" },
        skip,
        take,
      }),
      this.prisma.loan.count({ where }),
    ]);

    return {
      data: loans.map((l) => this.toResponseDto(l)),
      meta: {
        total,
        page: query.page || 1,
        limit: query.limit || 20,
        totalPages: Math.ceil(total / (query.limit || 20)),
      },
    };
  }

  private toResponseDto(loan: any): LoanResponseDto {
    return {
      id: loan.id,
      status: loan.status,
      loanedAt: loan.loanedAt,
      dueDate: loan.dueDate,
      returnedAt: loan.returnedAt ?? undefined,
      notes: loan.notes ?? undefined,
      issuedBy: loan.issuedBy ?? undefined,
      userId: loan.userId,
      copyId: loan.copyId,
      libraryBranchId: loan.libraryBranchId,
      createdAt: loan.createdAt,
      updatedAt: loan.updatedAt,
      user: loan.user,
      copy: loan.copy,
      libraryBranch: loan.libraryBranch,
    };
  }
}

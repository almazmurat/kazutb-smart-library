import { ForbiddenException, Injectable } from "@nestjs/common";
import {
  LoanStatus,
  Prisma,
  ReservationStatus,
  UserRole,
} from "@prisma/client";

import { RequestUser } from "../../common/types/request-user.interface";
import { PrismaService } from "../../prisma/prisma.service";

@Injectable()
export class AnalyticsService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Dashboard summary: totals for books, copies, reservations, loans, overdue, users, branches.
   * Librarian: scoped to own branch. Admin/Analyst: global.
   */
  async getDashboard(actor: RequestUser) {
    const branchId = await this.resolveBranchScope(actor);
    const branchFilter = branchId ? { libraryBranchId: branchId } : {};

    const [
      totalBooks,
      totalCopies,
      activeReservations,
      activeLoans,
      overdueLoans,
      totalUsers,
      branches,
    ] = await Promise.all([
      this.prisma.book.count({ where: { isActive: true, ...branchFilter } }),
      this.prisma.bookCopy.count({ where: branchFilter }),
      this.prisma.reservation.count({
        where: {
          status: { in: [ReservationStatus.PENDING, ReservationStatus.READY] },
          ...branchFilter,
        },
      }),
      this.prisma.loan.count({
        where: { status: LoanStatus.ACTIVE, ...branchFilter },
      }),
      this.prisma.loan.count({
        where: {
          status: LoanStatus.ACTIVE,
          dueDate: { lt: new Date() },
          ...branchFilter,
        },
      }),
      branchId
        ? this.prisma.user.count({
            where: { libraryBranchId: branchId, isActive: true },
          })
        : this.prisma.user.count({ where: { isActive: true } }),
      branchId
        ? this.prisma.libraryBranch.findMany({
            where: { id: branchId },
            select: { id: true, name: true, code: true },
          })
        : this.prisma.libraryBranch.findMany({
            where: { isActive: true },
            select: { id: true, name: true, code: true },
          }),
    ]);

    return {
      totalBooks,
      totalCopies,
      activeReservations,
      activeLoans,
      overdueLoans,
      totalUsers,
      branches,
      scope: branchId ? "branch" : "global",
    };
  }

  /**
   * Popular books ranked by combined loan + reservation count.
   * Score = loans * 2 + reservations * 1 (loans weighted higher as they represent actual usage).
   */
  async getPopularBooks(actor: RequestUser, limit = 10) {
    const branchId = await this.resolveBranchScope(actor);

    // Use raw aggregation for efficiency
    const books = await this.prisma.book.findMany({
      where: {
        isActive: true,
        ...(branchId ? { libraryBranchId: branchId } : {}),
      },
      select: {
        id: true,
        title: true,
        publishYear: true,
        language: true,
        libraryBranch: { select: { id: true, name: true } },
        authors: {
          select: { author: { select: { fullName: true } } },
        },
        _count: {
          select: {
            reservations: true,
          },
        },
        copies: {
          select: {
            _count: { select: { loans: true } },
          },
        },
      },
      take: 50, // fetch a wider pool, then sort by score
    });

    const scored = books
      .map((book) => {
        const loanCount = book.copies.reduce(
          (sum, copy) => sum + copy._count.loans,
          0,
        );
        const reservationCount = book._count.reservations;
        const score = loanCount * 2 + reservationCount;
        return {
          id: book.id,
          title: book.title,
          publishYear: book.publishYear,
          language: book.language,
          branch: book.libraryBranch,
          authors: book.authors.map((a) => a.author.fullName),
          loanCount,
          reservationCount,
          score,
        };
      })
      .filter((b) => b.score > 0)
      .sort((a, b) => b.score - a.score)
      .slice(0, limit);

    return {
      data: scored,
      rankingLogic:
        "score = loanCount * 2 + reservationCount (loans weighted higher as actual usage)",
    };
  }

  /**
   * Activity summary: counts for reservations, loans, returns over recent periods.
   * Buckets: today, last7days, last30days.
   */
  async getActivity(actor: RequestUser) {
    const branchId = await this.resolveBranchScope(actor);
    const branchFilter = branchId ? { libraryBranchId: branchId } : {};

    const now = new Date();
    const todayStart = new Date(
      now.getFullYear(),
      now.getMonth(),
      now.getDate(),
    );
    const last7 = new Date(todayStart.getTime() - 7 * 24 * 60 * 60 * 1000);
    const last30 = new Date(todayStart.getTime() - 30 * 24 * 60 * 60 * 1000);

    const [
      reservationsToday,
      reservations7d,
      reservations30d,
      loansToday,
      loans7d,
      loans30d,
      returnsToday,
      returns7d,
      returns30d,
    ] = await Promise.all([
      this.prisma.reservation.count({
        where: { reservedAt: { gte: todayStart }, ...branchFilter },
      }),
      this.prisma.reservation.count({
        where: { reservedAt: { gte: last7 }, ...branchFilter },
      }),
      this.prisma.reservation.count({
        where: { reservedAt: { gte: last30 }, ...branchFilter },
      }),
      this.prisma.loan.count({
        where: { loanedAt: { gte: todayStart }, ...branchFilter },
      }),
      this.prisma.loan.count({
        where: { loanedAt: { gte: last7 }, ...branchFilter },
      }),
      this.prisma.loan.count({
        where: { loanedAt: { gte: last30 }, ...branchFilter },
      }),
      this.prisma.loan.count({
        where: {
          returnedAt: { gte: todayStart },
          status: LoanStatus.RETURNED,
          ...branchFilter,
        },
      }),
      this.prisma.loan.count({
        where: {
          returnedAt: { gte: last7 },
          status: LoanStatus.RETURNED,
          ...branchFilter,
        },
      }),
      this.prisma.loan.count({
        where: {
          returnedAt: { gte: last30 },
          status: LoanStatus.RETURNED,
          ...branchFilter,
        },
      }),
    ]);

    return {
      reservations: {
        today: reservationsToday,
        last7days: reservations7d,
        last30days: reservations30d,
      },
      loans: {
        today: loansToday,
        last7days: loans7d,
        last30days: loans30d,
      },
      returns: {
        today: returnsToday,
        last7days: returns7d,
        last30days: returns30d,
      },
      scope: branchId ? "branch" : "global",
    };
  }

  /**
   * Resolve the branch scope for the current actor.
   * Librarians see only their branch. Admin/Analyst see global.
   */
  private async resolveBranchScope(actor: RequestUser): Promise<string | null> {
    if (actor.role === UserRole.ADMIN || actor.role === UserRole.ANALYST) {
      return null; // global
    }
    if (actor.role === UserRole.LIBRARIAN) {
      const user = await this.prisma.user.findUnique({
        where: { id: actor.id },
        select: { libraryBranchId: true },
      });
      if (!user?.libraryBranchId) {
        throw new ForbiddenException(
          "Librarian must be assigned to a library branch",
        );
      }
      return user.libraryBranchId;
    }
    throw new ForbiddenException("Access denied");
  }
}

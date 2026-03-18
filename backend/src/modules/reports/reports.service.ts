import { ForbiddenException, Injectable } from "@nestjs/common";
import { LoanStatus, ReservationStatus, UserRole } from "@prisma/client";

import { RequestUser } from "../../common/types/request-user.interface";
import { PrismaService } from "../../prisma/prisma.service";

@Injectable()
export class ReportsService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Reports overview: monthly + yearly counts for loans, reservations, returns.
   * Branch summary included for global scope.
   */
  async getOverview(actor: RequestUser, year?: number) {
    const branchId = await this.resolveBranchScope(actor);
    const targetYear = year || new Date().getFullYear();

    const yearStart = new Date(targetYear, 0, 1);
    const yearEnd = new Date(targetYear + 1, 0, 1);
    const branchFilter = branchId ? { libraryBranchId: branchId } : {};

    // Yearly totals
    const [yearlyLoans, yearlyReturns, yearlyReservations, yearlyOverdue] =
      await Promise.all([
        this.prisma.loan.count({
          where: { loanedAt: { gte: yearStart, lt: yearEnd }, ...branchFilter },
        }),
        this.prisma.loan.count({
          where: {
            returnedAt: { gte: yearStart, lt: yearEnd },
            status: LoanStatus.RETURNED,
            ...branchFilter,
          },
        }),
        this.prisma.reservation.count({
          where: {
            reservedAt: { gte: yearStart, lt: yearEnd },
            ...branchFilter,
          },
        }),
        this.prisma.loan.count({
          where: {
            loanedAt: { gte: yearStart, lt: yearEnd },
            status: LoanStatus.ACTIVE,
            dueDate: { lt: new Date() },
            ...branchFilter,
          },
        }),
      ]);

    // Monthly breakdown for each month of the target year
    const monthly = await this.buildMonthlyBreakdown(targetYear, branchFilter);

    // Branch summary (global only)
    let branchSummary: Array<{
      branchId: string;
      branchName: string;
      loans: number;
      returns: number;
      reservations: number;
    }> = [];

    if (!branchId) {
      const branches = await this.prisma.libraryBranch.findMany({
        where: { isActive: true },
        select: { id: true, name: true },
      });

      branchSummary = await Promise.all(
        branches.map(async (branch) => {
          const bf = { libraryBranchId: branch.id };
          const [loans, returns, reservations] = await Promise.all([
            this.prisma.loan.count({
              where: { loanedAt: { gte: yearStart, lt: yearEnd }, ...bf },
            }),
            this.prisma.loan.count({
              where: {
                returnedAt: { gte: yearStart, lt: yearEnd },
                status: LoanStatus.RETURNED,
                ...bf,
              },
            }),
            this.prisma.reservation.count({
              where: { reservedAt: { gte: yearStart, lt: yearEnd }, ...bf },
            }),
          ]);
          return {
            branchId: branch.id,
            branchName: branch.name,
            loans,
            returns,
            reservations,
          };
        }),
      );
    }

    return {
      year: targetYear,
      scope: branchId ? "branch" : "global",
      yearly: {
        loans: yearlyLoans,
        returns: yearlyReturns,
        reservations: yearlyReservations,
        currentOverdue: yearlyOverdue,
      },
      monthly,
      branchSummary,
    };
  }

  private async buildMonthlyBreakdown(
    year: number,
    branchFilter: { libraryBranchId?: string },
  ) {
    const months: Array<{
      month: number;
      loans: number;
      returns: number;
      reservations: number;
    }> = [];

    for (let m = 0; m < 12; m++) {
      const start = new Date(year, m, 1);
      const end = new Date(year, m + 1, 1);

      if (start > new Date()) break; // don't show future months

      const [loans, returns, reservations] = await Promise.all([
        this.prisma.loan.count({
          where: { loanedAt: { gte: start, lt: end }, ...branchFilter },
        }),
        this.prisma.loan.count({
          where: {
            returnedAt: { gte: start, lt: end },
            status: LoanStatus.RETURNED,
            ...branchFilter,
          },
        }),
        this.prisma.reservation.count({
          where: { reservedAt: { gte: start, lt: end }, ...branchFilter },
        }),
      ]);

      months.push({ month: m + 1, loans, returns, reservations });
    }

    return months;
  }

  private async resolveBranchScope(actor: RequestUser): Promise<string | null> {
    if (actor.role === UserRole.ADMIN || actor.role === UserRole.ANALYST) {
      return null;
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

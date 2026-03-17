import {
  ForbiddenException,
  Injectable,
  NotFoundException,
} from "@nestjs/common";

import { UserRole } from "../types/user-role.enum";
import { RequestUser } from "../types/request-user.interface";
import { PrismaService } from "../../prisma/prisma.service";

@Injectable()
export class CatalogOwnershipPolicy {
  constructor(private readonly prisma: PrismaService) {}

  async assertCanMutateBranch(actor: RequestUser, targetBranchId: string) {
    if (actor.role === UserRole.ADMIN) {
      return;
    }

    if (actor.role !== UserRole.LIBRARIAN) {
      throw new ForbiddenException(
        "Only librarian or admin can mutate catalog records",
      );
    }

    const actorUser = await this.prisma.user.findUnique({
      where: { id: actor.id },
      select: {
        institutionScopeId: true,
        libraryBranchId: true,
      },
    });

    if (
      !actorUser ||
      !actorUser.libraryBranchId ||
      !actorUser.institutionScopeId
    ) {
      throw new ForbiddenException(
        "Librarian account is not assigned to branch and scope",
      );
    }

    const targetBranch = await this.prisma.libraryBranch.findUnique({
      where: { id: targetBranchId },
      select: { id: true, scopeId: true },
    });

    if (!targetBranch) {
      throw new NotFoundException("Library branch not found");
    }

    const branchAllowed = actorUser.libraryBranchId === targetBranch.id;
    const scopeAllowed = actorUser.institutionScopeId === targetBranch.scopeId;

    if (!branchAllowed || !scopeAllowed) {
      throw new ForbiddenException(
        "Cross-branch or cross-scope catalog mutation is not allowed for librarian",
      );
    }
  }
}

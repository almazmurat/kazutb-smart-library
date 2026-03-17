import { ForbiddenException } from "@nestjs/common";

import { CatalogOwnershipPolicy } from "./catalog-ownership.policy";
import { UserRole } from "../types/user-role.enum";

describe("CatalogOwnershipPolicy", () => {
  function createPolicy() {
    const prisma = {
      user: { findUnique: jest.fn() },
      libraryBranch: { findUnique: jest.fn() },
    } as any;

    return {
      policy: new CatalogOwnershipPolicy(prisma),
      prisma,
    };
  }

  it("denies librarian cross-branch mutation", async () => {
    const { policy, prisma } = createPolicy();

    prisma.user.findUnique.mockResolvedValue({
      institutionScopeId: "scope-university",
      libraryBranchId: "branch-economic",
    });
    prisma.libraryBranch.findUnique.mockResolvedValue({
      id: "branch-college",
      scopeId: "scope-college",
    });

    await expect(
      policy.assertCanMutateBranch(
        {
          id: "user-1",
          universityId: "lib-1",
          email: "librarian@kazutb.edu.kz",
          fullName: "Librarian",
          role: UserRole.LIBRARIAN,
        },
        "branch-college",
      ),
    ).rejects.toBeInstanceOf(ForbiddenException);
  });

  it("allows admin mutation across all branches", async () => {
    const { policy, prisma } = createPolicy();

    await expect(
      policy.assertCanMutateBranch(
        {
          id: "admin-1",
          universityId: "admin",
          email: "admin@kazutb.edu.kz",
          fullName: "Admin",
          role: UserRole.ADMIN,
        },
        "any-branch-id",
      ),
    ).resolves.toBeUndefined();

    expect(prisma.user.findUnique).not.toHaveBeenCalled();
    expect(prisma.libraryBranch.findUnique).not.toHaveBeenCalled();
  });
});

import { BooksService } from "./books.service";
import { UserRole } from "../../common/types/user-role.enum";

describe("BooksService", () => {
  function createService() {
    const prisma = {
      author: { count: jest.fn() },
      category: { count: jest.fn() },
      book: { create: jest.fn(), findMany: jest.fn(), count: jest.fn() },
      $transaction: jest.fn(),
    } as any;

    const auditService = { write: jest.fn() } as any;
    const ownershipPolicy = { assertCanMutateBranch: jest.fn() } as any;

    const service = new BooksService(prisma, auditService, ownershipPolicy);
    return { service, prisma, auditService, ownershipPolicy };
  }

  it("creates a book with relations and writes audit event", async () => {
    const { service, prisma, ownershipPolicy, auditService } = createService();

    prisma.author.count.mockResolvedValue(1);
    prisma.category.count.mockResolvedValue(1);
    prisma.book.create.mockResolvedValue({
      id: "book-1",
      title: "Core Economics",
      libraryBranchId: "branch-1",
      isActive: true,
      authors: [],
      categories: [],
      copies: [],
      libraryBranch: null,
    });

    const result = await service.create(
      {
        title: "Core Economics",
        libraryBranchId: "branch-1",
        authorIds: ["author-1"],
        categoryIds: ["category-1"],
      },
      {
        actor: {
          id: "u-1",
          universityId: "lib-1",
          email: "lib@kazutb.edu.kz",
          fullName: "Lib User",
          role: UserRole.LIBRARIAN,
        },
      },
    );

    expect(result.id).toBe("book-1");
    expect(ownershipPolicy.assertCanMutateBranch).toHaveBeenCalledWith(
      expect.objectContaining({ id: "u-1" }),
      "branch-1",
    );
    expect(prisma.book.create).toHaveBeenCalled();
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({
        action: "BOOK_CREATED",
        entityType: "book",
        entityId: "book-1",
      }),
    );
  });

  it("returns paginated books list", async () => {
    const { service, prisma } = createService();

    prisma.$transaction.mockResolvedValueOnce([[{ id: "book-1" }], 1]);

    const response = await service.list({ title: "core", page: 1, limit: 10 });

    expect(response.meta.total).toBe(1);
    expect(response.data).toEqual([{ id: "book-1" }]);
    expect(prisma.$transaction).toHaveBeenCalled();
  });
});

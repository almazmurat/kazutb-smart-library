-- AlterEnum
ALTER TYPE "CopyStatus" ADD VALUE 'ARCHIVED';

-- DropForeignKey
ALTER TABLE "Book" DROP CONSTRAINT "Book_libraryBranchId_fkey";

-- DropForeignKey
ALTER TABLE "BookCopy" DROP CONSTRAINT "BookCopy_libraryBranchId_fkey";

-- AlterTable
ALTER TABLE "Book" ALTER COLUMN "libraryBranchId" SET NOT NULL;

-- AlterTable
ALTER TABLE "Author" ADD COLUMN     "isActive" BOOLEAN NOT NULL DEFAULT true;

-- AlterTable
ALTER TABLE "Category" ADD COLUMN     "isActive" BOOLEAN NOT NULL DEFAULT true;

-- AlterTable
ALTER TABLE "BookCopy" ALTER COLUMN "libraryBranchId" SET NOT NULL;

-- AddForeignKey
ALTER TABLE "Book" ADD CONSTRAINT "Book_libraryBranchId_fkey" FOREIGN KEY ("libraryBranchId") REFERENCES "LibraryBranch"("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "BookCopy" ADD CONSTRAINT "BookCopy_libraryBranchId_fkey" FOREIGN KEY ("libraryBranchId") REFERENCES "LibraryBranch"("id") ON DELETE RESTRICT ON UPDATE CASCADE;


-- AlterTable
ALTER TABLE "Reservation" ADD COLUMN     "libraryBranchId" UUID NOT NULL DEFAULT gen_random_uuid(),
ADD COLUMN     "processedAt" TIMESTAMP(3),
ADD COLUMN     "notes" TEXT,
ADD COLUMN     "processedByUserId" UUID;

-- Set libraryBranchId from book's libraryBranchId for existing records
UPDATE "Reservation" r
SET "libraryBranchId" = b."libraryBranchId"
FROM "Book" b
WHERE r."bookId" = b.id AND r."libraryBranchId" = gen_random_uuid();

-- DropIndex
DROP INDEX IF EXISTS "Reservation_bookId_idx";

-- CreateIndex
CREATE INDEX "Reservation_libraryBranchId_idx" ON "Reservation"("libraryBranchId");

-- AddForeignKey
ALTER TABLE "Reservation" ADD CONSTRAINT "Reservation_libraryBranchId_fkey" FOREIGN KEY ("libraryBranchId") REFERENCES "LibraryBranch"("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "Reservation" ADD CONSTRAINT "Reservation_processedByUserId_fkey" FOREIGN KEY ("processedByUserId") REFERENCES "User"("id") ON DELETE SET NULL ON UPDATE CASCADE;

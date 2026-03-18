-- AlterTable: Add libraryBranchId, notes, and change issuedBy type on Loan
ALTER TABLE "Loan" ADD COLUMN "libraryBranchId" UUID;
ALTER TABLE "Loan" ADD COLUMN "notes" TEXT;

-- For existing loans without a branch, set from the copy's branch
UPDATE "Loan" SET "libraryBranchId" = (
    SELECT "libraryBranchId" FROM "BookCopy" WHERE "BookCopy"."id" = "Loan"."copyId"
) WHERE "libraryBranchId" IS NULL;

-- Make libraryBranchId NOT NULL after backfill
ALTER TABLE "Loan" ALTER COLUMN "libraryBranchId" SET NOT NULL;

-- Change issuedBy from TEXT to UUID
ALTER TABLE "Loan" ALTER COLUMN "issuedBy" TYPE UUID USING "issuedBy"::uuid;

-- Add foreign key constraints
ALTER TABLE "Loan" ADD CONSTRAINT "Loan_libraryBranchId_fkey" FOREIGN KEY ("libraryBranchId") REFERENCES "LibraryBranch"("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- Add index on libraryBranchId
CREATE INDEX "Loan_libraryBranchId_idx" ON "Loan"("libraryBranchId");

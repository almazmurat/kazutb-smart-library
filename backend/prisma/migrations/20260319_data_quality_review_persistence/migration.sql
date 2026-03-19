-- CreateEnum
CREATE TYPE "DataQualityReviewStatus" AS ENUM (
  'OPEN',
  'IN_REVIEW',
  'NEEDS_METADATA_COMPLETION',
  'DUPLICATE_CANDIDATE',
  'ESCALATED',
  'REVIEWED'
);

-- CreateTable
CREATE TABLE "DataQualityIssueReview" (
  "id" UUID NOT NULL,
  "issueId" TEXT NOT NULL,
  "sourceTable" TEXT NOT NULL,
  "sourceRecordKey" TEXT NOT NULL,
  "issueClass" TEXT NOT NULL,
  "severity" TEXT NOT NULL,
  "status" "DataQualityReviewStatus" NOT NULL DEFAULT 'OPEN',
  "latestNote" TEXT,
  "priority" INTEGER,
  "metadata" JSONB,
  "createdAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updatedAt" TIMESTAMP(3) NOT NULL,
  "assignedToUserId" UUID,
  "lastReviewedByUserId" UUID,

  CONSTRAINT "DataQualityIssueReview_pkey" PRIMARY KEY ("id")
);

-- CreateTable
CREATE TABLE "DataQualityIssueReviewNote" (
  "id" UUID NOT NULL,
  "note" TEXT NOT NULL,
  "createdAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "reviewId" UUID NOT NULL,
  "userId" UUID NOT NULL,

  CONSTRAINT "DataQualityIssueReviewNote_pkey" PRIMARY KEY ("id")
);

-- CreateIndex
CREATE UNIQUE INDEX "DataQualityIssueReview_issueId_key" ON "DataQualityIssueReview"("issueId");

-- CreateIndex
CREATE INDEX "DataQualityIssueReview_status_idx" ON "DataQualityIssueReview"("status");

-- CreateIndex
CREATE INDEX "DataQualityIssueReview_sourceTable_idx" ON "DataQualityIssueReview"("sourceTable");

-- CreateIndex
CREATE INDEX "DataQualityIssueReview_sourceRecordKey_idx" ON "DataQualityIssueReview"("sourceRecordKey");

-- CreateIndex
CREATE INDEX "DataQualityIssueReviewNote_reviewId_idx" ON "DataQualityIssueReviewNote"("reviewId");

-- CreateIndex
CREATE INDEX "DataQualityIssueReviewNote_userId_idx" ON "DataQualityIssueReviewNote"("userId");

-- CreateIndex
CREATE INDEX "DataQualityIssueReviewNote_createdAt_idx" ON "DataQualityIssueReviewNote"("createdAt");

-- AddForeignKey
ALTER TABLE "DataQualityIssueReview"
ADD CONSTRAINT "DataQualityIssueReview_assignedToUserId_fkey"
FOREIGN KEY ("assignedToUserId") REFERENCES "User"("id")
ON DELETE SET NULL ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "DataQualityIssueReview"
ADD CONSTRAINT "DataQualityIssueReview_lastReviewedByUserId_fkey"
FOREIGN KEY ("lastReviewedByUserId") REFERENCES "User"("id")
ON DELETE SET NULL ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "DataQualityIssueReviewNote"
ADD CONSTRAINT "DataQualityIssueReviewNote_reviewId_fkey"
FOREIGN KEY ("reviewId") REFERENCES "DataQualityIssueReview"("id")
ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "DataQualityIssueReviewNote"
ADD CONSTRAINT "DataQualityIssueReviewNote_userId_fkey"
FOREIGN KEY ("userId") REFERENCES "User"("id")
ON DELETE CASCADE ON UPDATE CASCADE;

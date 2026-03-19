/// <reference types="jest" />

import {
  afterAll,
  beforeAll,
  describe,
  expect,
  it,
  jest,
} from "@jest/globals";
import { ForbiddenException } from "@nestjs/common";
import { DataQualityReviewStatus } from "@prisma/client";
import { mkdtempSync, rmSync, writeFileSync } from "node:fs";
import * as os from "node:os";
import * as path from "node:path";

import { UserRole } from "../../common/types/user-role.enum";
import { MigrationService } from "./migration.service";

describe("MigrationService data quality persistence", () => {
  let tempDir = "";
  let previousArtifactsDir: string | undefined;

  beforeAll(() => {
    tempDir = mkdtempSync(path.join(os.tmpdir(), "dq-artifacts-"));

    const us = "\u001f";
    const rs = "\u001e";

    const docViewRows = [
      `1;a;m;${rs}001  ${us}0RU/IS/BASE/900001${rs}020  ${us}a9780306406157${rs}041  ${us}aeng${rs}245  ${us}aQuality Engineering Handbook${rs}260  ${us}aAlmaty${us}bKazUTB Press${us}c2022${rs}952  ${us}aКУР`,
      `2;a;m;${rs}001  ${us}0RU/IS/BASE/900002${rs}020  ${us}a123-ABC-000${rs}041  ${us}arus${rs}1001 ${us}aIvan Petrov${rs}245  ${us}aLegacy Data Cleanup${rs}260  ${us}aAstana${us}c2018`,
      `3;a;m;${rs}001  ${us}0RU/IS/BASE/900003${rs}653  ${us}aMisc`,
    ].join("\n");

    writeFileSync(path.join(tempDir, "dbo_DOC_VIEW.csv"), docViewRows, "utf8");
    writeFileSync(
      path.join(tempDir, "table_row_counts.csv"),
      "DOC;8930\nINV;49620\n",
      "utf8",
    );
    writeFileSync(
      path.join(tempDir, "columns_inventory.csv"),
      "dbo;DOC;DOC_ID;int;NO;NULL\ndbo;DOC;ITEM;text;YES;NULL\ndbo;INV;INV_ID;int;NO;NULL\n",
      "utf8",
    );
    writeFileSync(
      path.join(tempDir, "foreign_keys.csv"),
      "FK_DOC_INV;INV;DOC_ID;DOC;DOC_ID\n",
      "utf8",
    );

    previousArtifactsDir = process.env.LEGACY_DISCOVERY_DIR;
    process.env.LEGACY_DISCOVERY_DIR = tempDir;
  });

  afterAll(() => {
    if (previousArtifactsDir) {
      process.env.LEGACY_DISCOVERY_DIR = previousArtifactsDir;
    } else {
      delete process.env.LEGACY_DISCOVERY_DIR;
    }
    if (tempDir) {
      rmSync(tempDir, { recursive: true, force: true });
    }
  });

  function createService() {
    const persistedReviews = new Map<string, any>();

    const prisma = {
      user: {
        findUnique: jest.fn(),
      },
      dataQualityIssueReview: {
        findMany: jest.fn().mockImplementation(({ where }: { where: { issueId: { in: string[] } } }) => {
          return where.issueId.in
            .map((id) => persistedReviews.get(id))
            .filter(Boolean);
        }),
        findUnique: jest.fn().mockImplementation(({ where }: { where: { issueId: string } }) => {
          const review = persistedReviews.get(where.issueId);
          if (!review) {
            return null;
          }
          return { id: review.id };
        }),
        upsert: jest.fn().mockImplementation(({ where, create, update }: any) => {
          const existing = persistedReviews.get(where.issueId);
          const now = new Date("2026-03-19T10:00:00.000Z");
          const merged = {
            id: existing?.id ?? "review-1",
            issueId: where.issueId,
            sourceTable: existing?.sourceTable ?? create.sourceTable,
            sourceRecordKey: existing?.sourceRecordKey ?? create.sourceRecordKey,
            issueClass: existing?.issueClass ?? create.issueClass,
            severity: existing?.severity ?? create.severity,
            status: existing?.status ?? create.status,
            latestNote: existing?.latestNote ?? create.latestNote,
            assignedToUserId: existing?.assignedToUserId ?? create.assignedToUserId ?? null,
            lastReviewedByUserId:
              update?.lastReviewedByUserId ?? create.lastReviewedByUserId ?? null,
            assignedToUser: null,
            lastReviewedByUser: null,
            _count: { notes: existing?._count?.notes ?? 0 },
            updatedAt: now,
          };

          if (existing) {
            Object.assign(merged, existing, update);
          }

          if (update?.status) {
            merged.status = update.status;
          }
          if (update?.latestNote !== undefined) {
            merged.latestNote = update.latestNote;
          }
          if (update?.assignedToUserId !== undefined) {
            merged.assignedToUserId = update.assignedToUserId;
           }

          persistedReviews.set(where.issueId, merged);
          return merged;
        }),
      },
      dataQualityIssueReviewNote: {
        create: jest.fn(),
        findMany: jest.fn(() => Promise.resolve([] as any[])),
      },
    } as any;

    const auditService = {
      write: jest.fn(() => Promise.resolve({ id: "audit-1" } as any)),
    } as any;

    const service = new MigrationService(prisma, auditService);
    return { service, prisma, auditService };
  }

  const adminActor = {
    id: "admin-1",
    universityId: "adm-001",
    email: "admin@kazutb.edu.kz",
    fullName: "Admin",
    role: UserRole.ADMIN,
  };

  const librarianActor = {
    id: "librarian-1",
    universityId: "lib-001",
    email: "librarian@kazutb.edu.kz",
    fullName: "Librarian",
    role: UserRole.LIBRARIAN,
  };

  const studentActor = {
    id: "student-1",
    universityId: "stu-001",
    email: "student@kazutb.edu.kz",
    fullName: "Student",
    role: UserRole.STUDENT,
  };

  it("merges deterministic issue with persisted review state", async () => {
    const { service, prisma } = createService();

    prisma.user.findUnique.mockResolvedValue({
      libraryBranchId: "branch-1",
      libraryBranch: { code: "ECONOMIC_LIBRARY" },
    });

    const initial = await service.getDataQualityIssues(adminActor, {});
    const targetIssueId = initial.items[0]?.id;
    expect(targetIssueId).toBeTruthy();

    prisma.dataQualityIssueReview.findMany.mockResolvedValueOnce([
      {
        issueId: targetIssueId,
        status: DataQualityReviewStatus.REVIEWED,
        latestNote: "Validated by librarian",
        assignedToUserId: "librarian-1",
        assignedToUser: { id: "librarian-1", fullName: "Librarian" },
        lastReviewedByUserId: "librarian-1",
        lastReviewedByUser: { id: "librarian-1", fullName: "Librarian" },
        _count: { notes: 2 },
        updatedAt: new Date("2026-03-19T09:00:00.000Z"),
      },
    ]);

    const result = await service.getDataQualityIssues(adminActor, {});

    expect(result.total).toBeGreaterThan(0);
    const withReview = result.items.find((item) => item.id === targetIssueId);
    expect(withReview).toBeDefined();
    expect(withReview?.review.status).toBe(DataQualityReviewStatus.REVIEWED);
    expect(withReview?.review.latestNote).toBe("Validated by librarian");
  });

  it("persists review status update and writes audit event", async () => {
    const { service, prisma, auditService } = createService();

    prisma.user.findUnique.mockResolvedValue({
      libraryBranchId: "branch-1",
      libraryBranch: { code: "ECONOMIC_LIBRARY" },
    });

    const initial = await service.getDataQualityIssues(adminActor, {});
    const issueId = initial.items[0]?.id;
    expect(issueId).toBeTruthy();

    await service.updateIssueReviewStatus(
      adminActor,
      issueId!,
      DataQualityReviewStatus.IN_REVIEW,
      "Started review",
    );

    expect(prisma.dataQualityIssueReview.upsert).toHaveBeenCalled();
    expect(prisma.dataQualityIssueReviewNote.create).toHaveBeenCalled();
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({
        action: "DATA_QUALITY_REVIEW_STATUS_CHANGED",
        entityId: issueId,
      }),
    );
  });

  it("creates note and writes audit event", async () => {
    const { service, prisma, auditService } = createService();

    prisma.user.findUnique.mockResolvedValue({
      libraryBranchId: "branch-1",
      libraryBranch: { code: "ECONOMIC_LIBRARY" },
    });

    const initial = await service.getDataQualityIssues(adminActor, {});
    const issueId = initial.items[0]?.id;

    await service.addIssueNote(adminActor, issueId!, "Need metadata cleanup");

    expect(prisma.dataQualityIssueReviewNote.create).toHaveBeenCalled();
    expect(auditService.write).toHaveBeenCalledWith(
      expect.objectContaining({
        action: "DATA_QUALITY_NOTE_ADDED",
        entityId: issueId,
      }),
    );
  });

  it("denies data quality access to student role", async () => {
    const { service } = createService();

    await expect(service.getDataQualitySummary(studentActor, {})).rejects.toBeInstanceOf(
      ForbiddenException,
    );
  });
});

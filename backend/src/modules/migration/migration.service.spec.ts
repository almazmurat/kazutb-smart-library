/// <reference types="jest" />

import { afterAll, beforeAll, describe, expect, it } from "@jest/globals";
import { mkdtempSync, rmSync, writeFileSync } from "node:fs";
import * as os from "node:os";
import * as path from "node:path";

import { MigrationService } from "./migration.service";

describe("MigrationService data quality", () => {
  let tempDir = "";
  let previousArtifactsDir: string | undefined;

  beforeAll(() => {
    tempDir = mkdtempSync(path.join(os.tmpdir(), "dq-artifacts-"));

    const us = "\u001f";
    const rs = "\u001e";

    const docViewRows = [
      `1;a;m;${rs}001  ${us}0RU/IS/BASE/900001${rs}020  ${us}a9780306406157${rs}041  ${us}aeng${rs}245  ${us}aQuality Engineering Handbook${rs}260  ${us}aAlmaty${us}bKazUTB Press${us}c2022`,
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

  it("detects deterministic issues from artifact rows", () => {
    const service = new MigrationService();
    const result = service.getDataQualityIssues({});

    expect(result.total).toBeGreaterThan(0);

    const rules = result.items.map((item) => item.detectionRule);
    expect(rules).toContain("missing_author");
    expect(rules).toContain("malformed_isbn");
    expect(rules).toContain("missing_title");

    const sample = result.items.find((item) => item.detectionRule === "missing_title");
    expect(sample?.id.startsWith("DQ-missing_title-")).toBe(true);
    expect(sample?.sourceRecordKey).toBe("RU/IS/BASE/900003");
  });

  it("returns summary counts and artifact metadata", () => {
    const service = new MigrationService();
    const summary = service.getDataQualitySummary({});

    expect(summary.total).toBeGreaterThan(0);
    expect(summary.bySeverity.CRITICAL).toBeGreaterThan(0);
    expect(summary.byClass.FORMAT).toBeGreaterThan(0);
    expect(summary.artifactStats.docTableRowCount).toBe(8930);
    expect(summary.artifactStats.totalLegacyTables).toBe(2);
    expect(summary.artifactStats.foreignKeyCount).toBe(1);
  });

  it("filters issues by severity", () => {
    const service = new MigrationService();
    const critical = service.getDataQualityIssues({ severity: "CRITICAL" });

    expect(critical.items.length).toBeGreaterThan(0);
    expect(critical.items.every((item) => item.severity === "CRITICAL")).toBe(
      true,
    );
  });
});

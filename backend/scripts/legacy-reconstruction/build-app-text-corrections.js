const fs = require("node:fs");
const path = require("node:path");
const crypto = require("node:crypto");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

const SAFE_KAZAKH_WORD_MAP = new Map([
  ["казак", "қазақ"],
  ["казакша", "қазақша"],
  ["адебиет", "әдебиет"],
  ["эдебиет", "әдебиет"],
  ["андет", "әндет"],
]);

const PLACEHOLDER_PATTERN = /^(?:n\/?a|none|null|---|_+|\?+|без\s+названия)$/i;

const TARGETS = [
  {
    entityType: "document",
    tableName: "app.documents",
    idColumn: "id",
    fieldName: "title_display",
    currentColumn: "title_display",
    rawColumn: "title_raw",
    updateColumn: "title_display",
  },
  {
    entityType: "document",
    tableName: "app.documents",
    idColumn: "id",
    fieldName: "title_normalized",
    currentColumn: "title_normalized",
    rawColumn: "title_raw",
    updateColumn: "title_normalized",
  },
  {
    entityType: "document",
    tableName: "app.documents",
    idColumn: "id",
    fieldName: "subtitle_normalized",
    currentColumn: "subtitle_normalized",
    rawColumn: "subtitle_raw",
    updateColumn: "subtitle_normalized",
  },
  {
    entityType: "author",
    tableName: "app.authors",
    idColumn: "id",
    fieldName: "display_name",
    currentColumn: "display_name",
    rawColumn: "display_name",
    updateColumn: "display_name",
  },
  {
    entityType: "publisher",
    tableName: "app.publishers",
    idColumn: "id",
    fieldName: "display_name",
    currentColumn: "display_name",
    rawColumn: "display_name",
    updateColumn: "display_name",
  },
  {
    entityType: "subject",
    tableName: "app.subjects",
    idColumn: "id",
    fieldName: "display_subject",
    currentColumn: "display_subject",
    rawColumn: "display_subject",
    updateColumn: "display_subject",
  },
  {
    entityType: "keyword",
    tableName: "app.keywords",
    idColumn: "id",
    fieldName: "display_keyword",
    currentColumn: "display_keyword",
    rawColumn: "display_keyword",
    updateColumn: "display_keyword",
  },
  {
    entityType: "reader",
    tableName: "app.readers",
    idColumn: "id",
    fieldName: "full_name_normalized",
    currentColumn: "full_name_normalized",
    rawColumn: "full_name_raw",
    updateColumn: null,
  },
];

function parseArgs(argv) {
  const args = {
    rollbackRunId: null,
  };

  for (let i = 0; i < argv.length; i += 1) {
    if (argv[i] === "--rollback-run" && argv[i + 1]) {
      args.rollbackRunId = argv[i + 1];
      i += 1;
    }
  }

  return args;
}

function applyNormalizationRules(input) {
  if (typeof input !== "string") {
    return null;
  }

  const original = input;
  let value = input;
  const appliedRules = [];

  const trimmed = value.trim();
  if (trimmed !== value) {
    value = trimmed;
    appliedRules.push("trim_whitespace");
  }

  const collapsed = value.replace(/\s{2,}/g, " ");
  if (collapsed !== value) {
    value = collapsed;
    appliedRules.push("collapse_spaces");
  }

  const punctuationSpaced = value
    .replace(/\s+([,;:!?])/g, "$1")
    .replace(/([,;:!?])(\S)/g, "$1 $2")
    .replace(/\(\s+/g, "(")
    .replace(/\s+\)/g, ")")
    .replace(/\s{2,}/g, " ")
    .trim();
  if (punctuationSpaced !== value) {
    value = punctuationSpaced;
    appliedRules.push("normalize_punctuation_spacing");
  }

  if (PLACEHOLDER_PATTERN.test(value)) {
    value = "";
    appliedRules.push("remove_placeholder_junk");
  }

  const words = value.split(/(\s+)/);
  let kazakhReplacements = 0;
  const mappedWords = words.map((token) => {
    const lower = token.toLowerCase();
    const mapped = SAFE_KAZAKH_WORD_MAP.get(lower);
    if (!mapped) {
      return token;
    }
    kazakhReplacements += 1;
    if (token === lower) {
      return mapped;
    }
    if (token[0] && token[0] === token[0].toUpperCase()) {
      return mapped.charAt(0).toUpperCase() + mapped.slice(1);
    }
    return mapped;
  });
  if (kazakhReplacements > 0) {
    value = mappedWords.join("");
    appliedRules.push("kazakh_known_token_map");
  }

  const softPattern = value
    .replace(/\bКазак\b/g, "Қазақ")
    .replace(/\bКазакша\b/g, "Қазақша")
    .replace(/\bАдебиет\b/g, "Әдебиет");
  if (
    softPattern !== value &&
    !appliedRules.includes("kazakh_known_token_map")
  ) {
    value = softPattern;
    appliedRules.push("kazakh_soft_pattern");
  }

  if (value === original) {
    return null;
  }

  const hasHighRiskMarker =
    /[�]/.test(value) || /\b(xxx|asdf|testtest)\b/i.test(value);
  const hasUncertainNoise = /\?{2,}|\.{4,}/.test(original);

  const hasSoftPattern = appliedRules.includes("kazakh_soft_pattern");
  const hasPlaceholderRemoval = appliedRules.includes(
    "remove_placeholder_junk",
  );
  const hasPunctuationSpacing = appliedRules.includes(
    "normalize_punctuation_spacing",
  );

  let actionClass = "auto_apply";
  let confidence = 0.99;

  if (hasHighRiskMarker) {
    actionClass = "flag_only";
    confidence = 0.2;
  } else if (hasUncertainNoise) {
    actionClass = "flag_only";
    confidence = 0.35;
  } else if (hasPunctuationSpacing) {
    actionClass = "suggest_only";
    confidence = 0.88;
  } else if (hasSoftPattern) {
    actionClass = "suggest_only";
    confidence = 0.85;
  } else if (hasPlaceholderRemoval) {
    actionClass = "suggest_only";
    confidence = 0.9;
  } else {
    confidence = appliedRules.includes("kazakh_known_token_map") ? 0.98 : 0.995;
  }

  return {
    original,
    suggested: value,
    rules: appliedRules,
    actionClass,
    confidence,
  };
}

function toActionCounters(items) {
  const counters = {
    auto_apply: 0,
    suggest_only: 0,
    flag_only: 0,
  };

  for (const item of items) {
    counters[item.actionClass] = (counters[item.actionClass] || 0) + 1;
  }

  return counters;
}

async function loadRows(client, target) {
  const sql = `
    SELECT ${target.idColumn} AS entity_id,
           ${target.currentColumn} AS current_value,
           ${target.rawColumn} AS source_raw_value
    FROM ${target.tableName}
    WHERE ${target.currentColumn} IS NOT NULL
      AND btrim(${target.currentColumn}) <> ''
  `;

  const result = await client.query(sql);
  return result.rows;
}

async function upsertSuggestion(client, runId, target, row, correction) {
  const ruleKey = correction.rules.join("+");
  const details = {
    rules: correction.rules,
    policy: {
      autoApply: "very_high_confidence_reversible_low_risk",
      suggestOnly: "medium_confidence_requires_librarian_review",
      flagOnly: "low_confidence_or_risky",
    },
  };

  const result = await client.query(
    `
    INSERT INTO app.text_correction_suggestions (
      normalization_run_id,
      entity_type,
      entity_id,
      field_name,
      source_raw_value,
      current_value,
      suggested_value,
      correction_rule_key,
      confidence_score,
      action_class,
      auto_applied,
      applied_at,
      reviewed,
      review_status,
      details,
      updated_at
    )
    VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,
      CASE WHEN $11 THEN now() ELSE NULL END,
      false,
      CASE WHEN $10 = 'auto_apply' THEN 'NOT_REQUIRED' ELSE 'PENDING' END,
      $12,
      now())
    ON CONFLICT (entity_type, entity_id, field_name, suggested_value, correction_rule_key)
    DO UPDATE SET
      normalization_run_id = EXCLUDED.normalization_run_id,
      source_raw_value = EXCLUDED.source_raw_value,
      current_value = EXCLUDED.current_value,
      confidence_score = EXCLUDED.confidence_score,
      action_class = EXCLUDED.action_class,
      auto_applied = EXCLUDED.auto_applied,
      applied_at = EXCLUDED.applied_at,
      review_status = CASE
        WHEN app.text_correction_suggestions.reviewed THEN app.text_correction_suggestions.review_status
        ELSE EXCLUDED.review_status
      END,
      details = EXCLUDED.details,
      updated_at = now()
    RETURNING id
    `,
    [
      runId,
      target.entityType,
      row.entity_id,
      target.fieldName,
      row.source_raw_value,
      row.current_value,
      correction.suggested,
      ruleKey,
      correction.confidence,
      correction.actionClass,
      correction.actionClass === "auto_apply",
      details,
    ],
  );

  return result.rows[0].id;
}

async function applyAutoUpdate(client, target, row, correction) {
  if (!target.updateColumn) {
    return false;
  }

  const result = await client.query(
    `
    UPDATE ${target.tableName}
    SET ${target.updateColumn} = $1,
        updated_at = now()
    WHERE ${target.idColumn} = $2
      AND ${target.currentColumn} = $3
    `,
    [correction.suggested, row.entity_id, row.current_value],
  );

  return result.rowCount > 0;
}

async function rollbackRun(client, rollbackRunId) {
  const suggestions = await client.query(
    `
    SELECT entity_type, entity_id, field_name, current_value
    FROM app.text_correction_suggestions
    WHERE normalization_run_id = $1
      AND auto_applied = true
    `,
    [rollbackRunId],
  );

  const fieldMap = {
    "document:title_display": {
      tableName: "app.documents",
      idColumn: "id",
      valueColumn: "title_display",
    },
    "document:title_normalized": {
      tableName: "app.documents",
      idColumn: "id",
      valueColumn: "title_normalized",
    },
    "document:subtitle_normalized": {
      tableName: "app.documents",
      idColumn: "id",
      valueColumn: "subtitle_normalized",
    },
    "author:display_name": {
      tableName: "app.authors",
      idColumn: "id",
      valueColumn: "display_name",
    },
    "publisher:display_name": {
      tableName: "app.publishers",
      idColumn: "id",
      valueColumn: "display_name",
    },
    "subject:display_subject": {
      tableName: "app.subjects",
      idColumn: "id",
      valueColumn: "display_subject",
    },
    "keyword:display_keyword": {
      tableName: "app.keywords",
      idColumn: "id",
      valueColumn: "display_keyword",
    },
  };

  let reverted = 0;
  for (const row of suggestions.rows) {
    const key = `${row.entity_type}:${row.field_name}`;
    const target = fieldMap[key];
    if (!target) {
      continue;
    }

    const updateResult = await client.query(
      `
      UPDATE ${target.tableName}
      SET ${target.valueColumn} = $1,
          updated_at = now()
      WHERE ${target.idColumn} = $2
      `,
      [row.current_value, row.entity_id],
    );

    reverted += updateResult.rowCount;
  }

  await client.query(
    `
    UPDATE app.text_correction_suggestions
    SET auto_applied = false,
        applied_at = NULL,
        details = jsonb_set(coalesce(details, '{}'::jsonb), '{rollback}',
          jsonb_build_object('rolledBackAt', now(), 'rollbackRunId', $1), true),
        updated_at = now()
    WHERE normalization_run_id = $1
      AND auto_applied = true
    `,
    [rollbackRunId],
  );

  return reverted;
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  const sqlPath = path.resolve(__dirname, "app-text-correction-layer.sql");
  const sql = fs.readFileSync(sqlPath, "utf8");

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  const runId = crypto.randomUUID();

  try {
    await client.query("BEGIN");
    await client.query(sql);

    if (args.rollbackRunId) {
      const reverted = await rollbackRun(client, args.rollbackRunId);
      await client.query("COMMIT");

      console.log(
        JSON.stringify(
          {
            status: "ROLLED_BACK",
            rollbackRunId: args.rollbackRunId,
            revertedRowUpdates: reverted,
          },
          null,
          2,
        ),
      );
      return;
    }

    await client.query(
      `
      INSERT INTO app.normalization_runs (id, run_type, status, started_at, notes, metrics)
      VALUES ($1, $2, 'IN_PROGRESS', now(), $3, '{}'::jsonb)
      `,
      [
        runId,
        "TEXT_CORRECTION_ASSIST",
        "Safe auto-correction and suggestion workflow for high-confidence text normalization",
      ],
    );

    const allCorrections = [];
    const topRules = new Map();
    let appliedCount = 0;

    for (const target of TARGETS) {
      const rows = await loadRows(client, target);

      for (const row of rows) {
        const correction = applyNormalizationRules(row.current_value);
        if (!correction || correction.suggested === row.current_value) {
          continue;
        }

        await upsertSuggestion(client, runId, target, row, correction);

        if (correction.actionClass === "auto_apply") {
          const wasApplied = await applyAutoUpdate(
            client,
            target,
            row,
            correction,
          );
          if (wasApplied) {
            appliedCount += 1;
          }
        }

        const ruleKey = correction.rules.join("+");
        topRules.set(ruleKey, (topRules.get(ruleKey) || 0) + 1);

        allCorrections.push({
          entityType: target.entityType,
          entityId: row.entity_id,
          fieldName: target.fieldName,
          sourceRawValue: row.source_raw_value,
          currentValue: row.current_value,
          suggestedValue: correction.suggested,
          ruleKey,
          confidence: correction.confidence,
          actionClass: correction.actionClass,
        });
      }
    }

    const counters = toActionCounters(allCorrections);
    const topRuleCategories = Array.from(topRules.entries())
      .map(([rule, count]) => ({ rule, count }))
      .sort((a, b) => b.count - a.count)
      .slice(0, 10);

    const sampleRows = allCorrections.slice(0, 20);

    await client.query(
      `
      UPDATE app.normalization_runs
      SET status = 'COMPLETED',
          completed_at = now(),
          metrics = $2
      WHERE id = $1
      `,
      [
        runId,
        {
          runId,
          totals: {
            found: allCorrections.length,
            autoApplied: counters.auto_apply,
            suggestOnly: counters.suggest_only,
            flagOnly: counters.flag_only,
            autoAppliedRowUpdates: appliedCount,
          },
          topRuleCategories,
        },
      ],
    );

    await client.query("COMMIT");

    console.log(
      JSON.stringify(
        {
          runId,
          status: "COMPLETED",
          totals: {
            found: allCorrections.length,
            autoApplied: counters.auto_apply,
            suggestOnly: counters.suggest_only,
            flagOnly: counters.flag_only,
            autoAppliedRowUpdates: appliedCount,
          },
          topRuleCategories,
          sampleRows,
          sqlPath,
        },
        null,
        2,
      ),
    );
  } catch (error) {
    await client.query("ROLLBACK");

    try {
      await client.query(
        `
        UPDATE app.normalization_runs
        SET status = 'FAILED',
            completed_at = now(),
            notes = coalesce(notes, '') || E'\n' || $2,
            metrics = $3
        WHERE id = $1
        `,
        [runId, String(error.message || error), { stack: error.stack }],
      );
    } catch (_) {
      // no-op best effort
    }

    throw error;
  } finally {
    await client.end();
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});

const fs = require("node:fs");
const path = require("node:path");
const readline = require("node:readline");
const { spawnSync } = require("node:child_process");
const { randomUUID, createHash } = require("node:crypto");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

const SOURCE_TABLES = [
  "DOC",
  "DOC_VIEW",
  "INV",
  "READERS",
  "RDRBP",
  "BOOKPOINTS",
  "SIGLAS",
  "POINTSIGLA",
  "LIBS",
  "LIBS_BOOKPOINTS",
  "LIBS_SIGLAS",
  "PUBLISHER",
  "BOOKSTATES",
  "PREORDERS",
];

function ensureDir(dirPath) {
  fs.mkdirSync(dirPath, { recursive: true });
}

function normalizeText(value) {
  if (value === null || value === undefined) {
    return null;
  }

  const normalized = String(value)
    .replace(/\u0000/g, "")
    .replace(/\s+/g, " ")
    .trim();

  return normalized.length > 0 ? normalized : null;
}

function normalizeName(value) {
  const normalized = normalizeText(value);
  if (!normalized) {
    return null;
  }

  return normalized
    .toLowerCase()
    .replace(/[^\p{L}\p{N}]+/gu, " ")
    .trim();
}

function normalizeCode(value, fallback) {
  const normalized = normalizeText(value);
  if (!normalized) {
    return fallback;
  }

  const ascii = normalized
    .toUpperCase()
    .replace(/[^A-Z0-9]+/g, "_")
    .replace(/^_+|_+$/g, "");

  return ascii || fallback;
}

function normalizeIsbn(value) {
  const normalized = normalizeText(value);
  if (!normalized) {
    return null;
  }

  const cleaned = normalized.replace(/[^0-9Xx-]/g, "").toUpperCase();
  return cleaned || null;
}

function splitTextValues(value) {
  const normalized = normalizeText(value);
  if (!normalized) {
    return [];
  }

  return normalized
    .split(/[;\n\r]+/)
    .map((item) => normalizeText(item))
    .filter(Boolean);
}

function excelDateToIso(value) {
  if (value === null || value === undefined || value === "") {
    return null;
  }

  const numeric = Number(value);
  if (!Number.isFinite(numeric) || numeric <= 0) {
    return null;
  }

  const epoch = Date.UTC(1899, 11, 30);
  const millis = Math.round(numeric * 24 * 60 * 60 * 1000);
  const date = new Date(epoch + millis);
  return Number.isNaN(date.getTime()) ? null : date.toISOString();
}

function extractYear(rawValue) {
  const normalized = normalizeText(rawValue);
  if (!normalized) {
    return null;
  }

  const match = normalized.match(/\b(19|20)\d{2}\b/);
  return match ? Number.parseInt(match[0], 10) : null;
}

function parseMarcRecord(rawMarc) {
  const normalized = rawMarc == null ? "" : String(rawMarc);
  const segments = normalized
    .split("\u001e")
    .map((segment) => segment.trim())
    .filter(Boolean);
  const tags = [];

  for (const segment of segments) {
    const match = segment.match(/^(\d{3,4})\s*(.*)$/su);
    if (!match) {
      continue;
    }

    const [, tag, rest] = match;
    const subfields = new Map();
    const parts = rest.split("\u001f").filter(Boolean);

    for (const part of parts) {
      const code = part[0];
      const value = normalizeText(part.slice(1));
      if (!value) {
        continue;
      }
      const list = subfields.get(code) ?? [];
      list.push(value);
      subfields.set(code, list);
    }

    tags.push({ tag, subfields });
  }

  return tags;
}

function getMarcValues(tags, tagCandidates, code) {
  const values = [];
  for (const field of tags) {
    if (!tagCandidates.includes(field.tag)) {
      continue;
    }
    const items = field.subfields.get(code) ?? [];
    values.push(...items);
  }
  return values.map((value) => normalizeText(value)).filter(Boolean);
}

function getFirstMarcValue(tags, tagCandidates, code) {
  return getMarcValues(tags, tagCandidates, code)[0] ?? null;
}

function stableToken(input) {
  return createHash("sha1").update(String(input)).digest("hex").slice(0, 12);
}

function dedupeBy(items, selector) {
  const seen = new Set();
  const result = [];

  for (const item of items) {
    const key = selector(item);
    if (seen.has(key)) {
      continue;
    }
    seen.add(key);
    result.push(item);
  }

  return result;
}

async function readNdjson(filePath) {
  if (!fs.existsSync(filePath)) {
    return [];
  }

  const rows = [];
  const rl = readline.createInterface({
    input: fs.createReadStream(filePath, { encoding: "utf8" }),
    crlfDelay: Infinity,
  });

  for await (const line of rl) {
    const trimmed = line.trim();
    if (!trimmed) {
      continue;
    }
    rows.push(JSON.parse(trimmed));
  }

  return rows;
}

function buildRawInsertConfig(batchId) {
  return {
    DOC: {
      table: "raw.doc",
      key: "legacy_doc_id",
      columns: [
        "legacy_doc_id",
        "rectype",
        "biblevel",
        "item",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.DOC_ID,
        normalizeText(row.RECTYPE),
        normalizeText(row.BIBLEVEL),
        row.ITEM ?? null,
        row,
        batchId,
      ],
    },
    DOC_VIEW: {
      table: "raw.doc_view",
      key: "legacy_doc_id",
      columns: [
        "legacy_doc_id",
        "control_number",
        "isbn",
        "author",
        "title",
        "publisher",
        "publication_year",
        "storage_sigla",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.DOC_ID,
        normalizeText(row.control_number),
        normalizeText(row.isbn),
        normalizeText(row.author),
        normalizeText(row.title),
        normalizeText(row.publisher),
        row.year ?? extractYear(row.year_digits),
        normalizeText(row.storage_sigla),
        row,
        batchId,
      ],
    },
    INV: {
      table: "raw.inv",
      key: "legacy_inv_id",
      columns: [
        "legacy_inv_id",
        "legacy_doc_id",
        "sigla_id",
        "state",
        "regdate_raw",
        "offdate_raw",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.INV_ID,
        row.DOC_ID ?? null,
        row.SIGLA_ID ?? null,
        row.STATE ?? null,
        row.REGDATE ?? null,
        row.OFFDATE ?? null,
        row,
        batchId,
      ],
    },
    READERS: {
      table: "raw.readers",
      key: "legacy_reader_id",
      columns: [
        "legacy_reader_id",
        "code",
        "name",
        "email",
        "birthday_raw",
        "regdate_raw",
        "reregdate_raw",
        "bookpoints",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        normalizeText(row.RDR_ID),
        normalizeText(row.CODE),
        normalizeText(row.NAME),
        normalizeText(row.EMAIL),
        row.BIRTHDAY ?? null,
        row.REGDATE ?? null,
        row.REREGDATE ?? null,
        normalizeText(row.BOOKPOINTS),
        row,
        batchId,
      ],
    },
    RDRBP: {
      table: "raw.rdrbp",
      key: "legacy_rdrbp_id",
      columns: [
        "legacy_rdrbp_id",
        "legacy_reader_id",
        "legacy_bookpoint_id",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.IDRBP,
        normalizeText(row.RDR_ID),
        row.IDP ?? null,
        row,
        batchId,
      ],
    },
    BOOKPOINTS: {
      table: "raw.bookpoints",
      key: "legacy_bookpoint_id",
      columns: [
        "legacy_bookpoint_id",
        "shortname",
        "status",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.IDP,
        normalizeText(row.SHORTNAME),
        row.STATUS ?? null,
        row,
        batchId,
      ],
    },
    SIGLAS: {
      table: "raw.siglas",
      key: "legacy_sigla_id",
      columns: [
        "legacy_sigla_id",
        "shortname",
        "storage",
        "access_level",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.ID,
        normalizeText(row.SHORTNAME),
        row.STORAGE ?? null,
        row.ACCLEVEL ?? null,
        row,
        batchId,
      ],
    },
    POINTSIGLA: {
      table: "raw.pointsigla",
      key: "legacy_pointsigla_id",
      columns: [
        "legacy_pointsigla_id",
        "legacy_bookpoint_id",
        "legacy_sigla_id",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [row.IDPS, row.IDP ?? null, row.IDS ?? null, row, batchId],
    },
    LIBS: {
      table: "raw.libs",
      key: "legacy_lib_id",
      columns: ["legacy_lib_id", "name", "source_payload", "batch_id"],
      map: (row) => [row.ID, normalizeText(row.NAME), row, batchId],
    },
    LIBS_BOOKPOINTS: {
      table: "raw.libs_bookpoints",
      key: "legacy_link_id",
      columns: [
        "legacy_link_id",
        "legacy_lib_id",
        "legacy_bookpoint_id",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.ID,
        row.LIBS_ID ?? null,
        row.BOOKPOINTS_ID ?? null,
        row,
        batchId,
      ],
    },
    LIBS_SIGLAS: {
      table: "raw.libs_siglas",
      key: "legacy_link_id",
      columns: [
        "legacy_link_id",
        "legacy_lib_id",
        "legacy_sigla_id",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.ID,
        row.LIBS_ID ?? null,
        row.SIGLAS_ID ?? null,
        row,
        batchId,
      ],
    },
    PUBLISHER: {
      table: "raw.publisher",
      key: "legacy_publisher_id",
      columns: ["legacy_publisher_id", "name", "source_payload", "batch_id"],
      map: (row) => [row.ID, normalizeText(row.NAME), row, batchId],
    },
    BOOKSTATES: {
      table: "raw.bookstates",
      key: "legacy_bookstate_id",
      columns: [
        "legacy_bookstate_id",
        "legacy_reader_id",
        "legacy_inv_id",
        "legacy_doc_id",
        "state",
        "legacy_bookpoint_id",
        "legacy_sigla_id",
        "chsdate_raw",
        "retdate_raw",
        "flags",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.IDBS,
        normalizeText(row.RDR_ID),
        row.INV_ID ?? null,
        row.DOC_ID ?? null,
        row.STATE ?? null,
        row.IDP ?? null,
        row.IDS ?? null,
        row.CHSDATE ?? null,
        row.RETDATE ?? null,
        normalizeText(row.FLAGS),
        row,
        batchId,
      ],
    },
    PREORDERS: {
      table: "raw.preorders",
      key: "legacy_preorder_id",
      columns: [
        "legacy_preorder_id",
        "legacy_reader_id",
        "legacy_doc_id",
        "legacy_bookpoint_id",
        "preorddate_raw",
        "source_payload",
        "batch_id",
      ],
      map: (row) => [
        row.IDPO,
        normalizeText(row.RDR_ID),
        row.DOC_ID ?? null,
        row.IDP ?? null,
        row.PREORDDATE ?? null,
        row,
        batchId,
      ],
    },
  };
}

async function batchInsert(client, table, columns, rows, conflictKey) {
  if (rows.length === 0) {
    return;
  }

  const chunkSize = 250;
  const updateColumns = columns.filter((column) => column !== conflictKey);

  for (let index = 0; index < rows.length; index += chunkSize) {
    const chunk = rows.slice(index, index + chunkSize);
    const values = [];
    const tuples = chunk.map((row, rowIndex) => {
      const placeholders = columns.map(
        (_, colIndex) => `$${rowIndex * columns.length + colIndex + 1}`,
      );
      values.push(...row);
      return `(${placeholders.join(", ")})`;
    });

    const sql = `
      INSERT INTO ${table} (${columns.join(", ")})
      VALUES ${tuples.join(",\n")}
      ON CONFLICT (${conflictKey}) DO UPDATE SET
      ${updateColumns.map((column) => `${column} = EXCLUDED.${column}`).join(",\n      ")};
    `;

    await client.query(sql, values);
  }
}

async function replaceTable(client, table, columns, rows) {
  await client.query(`TRUNCATE TABLE ${table} RESTART IDENTITY CASCADE`);
  if (rows.length === 0) {
    return;
  }

  const chunkSize = 250;
  for (let index = 0; index < rows.length; index += chunkSize) {
    const chunk = rows.slice(index, index + chunkSize);
    const values = [];
    const tuples = chunk.map((row, rowIndex) => {
      const placeholders = columns.map(
        (_, colIndex) => `$${rowIndex * columns.length + colIndex + 1}`,
      );
      values.push(...row);
      return `(${placeholders.join(", ")})`;
    });

    await client.query(
      `INSERT INTO ${table} (${columns.join(", ")}) VALUES ${tuples.join(",\n")}`,
      values,
    );
  }
}

function loadSqlServerTables(outputDir) {
  const exporterPath = path.resolve(__dirname, "export-legacy-tables.ps1");
  const result = spawnSync(
    "pwsh",
    [
      "-File",
      exporterPath,
      "-Instance",
      process.env.LEGACY_SQLSERVER_INSTANCE || "localhost\\SQLEXPRESS",
      "-Database",
      process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
      "-OutputDir",
      outputDir,
      "-Tables",
      SOURCE_TABLES.join(","),
    ],
    {
      encoding: "utf8",
      maxBuffer: 1024 * 1024 * 50,
    },
  );

  if (result.status !== 0) {
    throw new Error(
      result.stderr || result.stdout || "Legacy SQL export failed",
    );
  }
}

function buildParsedDocuments(docRows, docViewRows, batchId) {
  const docViewById = new Map(docViewRows.map((row) => [row.DOC_ID, row]));
  const parsedDocuments = [];
  const parsedAuthors = [];
  const parsedKeywords = [];
  const parsedSubjects = [];

  for (const doc of docRows) {
    const helper = docViewById.get(doc.DOC_ID) || {};
    const marc = parseMarcRecord(doc.ITEM);

    const title =
      getFirstMarcValue(marc, ["245"], "a") || normalizeText(helper.title);
    const titleContinuation =
      getFirstMarcValue(marc, ["245"], "b") ||
      normalizeText(helper.title_continuation);
    const physicalMedia =
      getFirstMarcValue(marc, ["245"], "h") ||
      normalizeText(helper.physical_media);
    const typeContentAccess =
      getFirstMarcValue(marc, ["245"], "z") ||
      normalizeText(helper.type_content_access);
    const primaryAuthor =
      getFirstMarcValue(marc, ["100", "1001"], "a") ||
      getFirstMarcValue(marc, ["245"], "c") ||
      normalizeText(helper.author);
    const otherAuthors = [
      ...getMarcValues(marc, ["700", "7001"], "a"),
      ...splitTextValues(helper.other_authors),
    ];
    const publicationPlace =
      getFirstMarcValue(marc, ["260"], "a") ||
      normalizeText(helper.publication_place);
    const publisherName =
      getFirstMarcValue(marc, ["260"], "b") || normalizeText(helper.publisher);
    const publicationYearRaw =
      getFirstMarcValue(marc, ["260"], "c") ||
      normalizeText(helper.year_digits) ||
      normalizeText(helper.year);
    const publicationYear = helper.year ?? extractYear(publicationYearRaw);
    const isbnRaw =
      getFirstMarcValue(marc, ["020"], "a") || normalizeText(helper.isbn);
    const languageCode =
      getFirstMarcValue(marc, ["041"], "a") ||
      normalizeText(helper.text_language_code);
    const keywords = [
      ...getMarcValues(marc, ["653"], "a"),
      ...splitTextValues(helper.keywords),
    ];
    const faculty =
      getFirstMarcValue(marc, ["952"], "d") || normalizeText(helper.faculty);
    const department =
      getFirstMarcValue(marc, ["952"], "e") || normalizeText(helper.department);
    const subject =
      getFirstMarcValue(marc, ["952"], "f") || normalizeText(helper.subject);
    const specialization =
      getFirstMarcValue(marc, ["952"], "j") ||
      normalizeText(helper.specialization);
    const literatureType =
      getFirstMarcValue(marc, ["952"], "a") ||
      normalizeText(helper.literature_type_ksu_vsh);
    const recordCreationYear =
      getFirstMarcValue(marc, ["990"], "d") ||
      normalizeText(helper.record_creation_year);
    const recordCreationMonth =
      getFirstMarcValue(marc, ["990"], "e") ||
      normalizeText(helper.record_creation_month);
    const recordCreationDate =
      getFirstMarcValue(marc, ["990"], "f") ||
      normalizeText(helper.record_creation_date);

    parsedDocuments.push([
      doc.DOC_ID,
      normalizeText(helper.control_number),
      normalizeText(doc.RECTYPE),
      normalizeText(doc.BIBLEVEL),
      title,
      titleContinuation,
      physicalMedia,
      typeContentAccess,
      primaryAuthor,
      publicationPlace,
      publisherName,
      publicationYear,
      publicationYearRaw,
      normalizeIsbn(isbnRaw),
      isbnRaw,
      normalizeText(languageCode),
      [
        ...new Set(
          keywords.map((value) => normalizeText(value)).filter(Boolean),
        ),
      ],
      faculty,
      department,
      subject,
      specialization,
      recordCreationYear,
      recordCreationMonth,
      recordCreationDate,
      literatureType,
      doc.ITEM ?? null,
      {
        rawDoc: doc,
        docView: helper,
      },
      batchId,
    ]);

    const authorNames = dedupeBy(
      [primaryAuthor, ...otherAuthors].filter(Boolean),
      (value) => normalizeName(value) || value,
    );
    authorNames.forEach((authorName, index) => {
      parsedAuthors.push([
        doc.DOC_ID,
        authorName,
        index === 0 ? "primary" : "secondary",
        index + 1,
      ]);
    });

    dedupeBy(
      keywords.map((value) => normalizeText(value)).filter(Boolean),
      (value) => normalizeName(value) || value,
    ).forEach((keyword, index) => {
      parsedKeywords.push([doc.DOC_ID, keyword, index + 1]);
    });

    dedupeBy(
      [
        [faculty, "faculty"],
        [department, "department"],
        [subject, "subject"],
        [specialization, "specialization"],
      ].filter(([value]) => Boolean(value)),
      ([value, kind]) => `${normalizeName(value) || value}|${kind}`,
    ).forEach(([value, kind]) => {
      parsedSubjects.push([doc.DOC_ID, value, kind]);
    });
  }

  return { parsedDocuments, parsedAuthors, parsedKeywords, parsedSubjects };
}

function buildParsedInventory(invRows, batchId) {
  return invRows.map((row) => [
    row.INV_ID,
    row.DOC_ID ?? null,
    normalizeText(row.T090e) || normalizeText(row.T876p) || null,
    normalizeText(row.T090f) || null,
    normalizeText(row.T876c),
    excelDateToIso(row.REGDATE),
    row.REGDATE ?? null,
    row.SIGLA_ID ?? null,
    row.STATE ?? null,
    normalizeText(row.TRACKINDEX),
    row,
    batchId,
  ]);
}

function buildParsedReaders(readerRows, batchId) {
  return readerRows.map((row) => [
    normalizeText(row.RDR_ID),
    normalizeText(row.CODE),
    normalizeText(row.NAME),
    normalizeText(row.EMAIL),
    normalizeText(row.WORKPHONE),
    normalizeText(row.EMPLOYMENT),
    normalizeText(row.COURSE),
    normalizeText(row.PROFESSION),
    normalizeText(row.SEX),
    excelDateToIso(row.BIRTHDAY)?.slice(0, 10) ?? null,
    row.BIRTHDAY ?? null,
    excelDateToIso(row.REGDATE),
    row.REGDATE ?? null,
    excelDateToIso(row.REREGDATE),
    row.REREGDATE ?? null,
    normalizeText(row.BOOKPOINTS),
    row,
    batchId,
  ]);
}

async function loadCore(client, batchId, sourceRows) {
  const parsedDocumentsResult = await client.query(
    "SELECT * FROM parsed.documents ORDER BY legacy_doc_id",
  );
  const parsedAuthorsResult = await client.query(
    "SELECT * FROM parsed.document_authors ORDER BY legacy_doc_id, sort_order",
  );
  const parsedKeywordsResult = await client.query(
    "SELECT * FROM parsed.document_keywords ORDER BY legacy_doc_id, sort_order",
  );
  const parsedSubjectsResult = await client.query(
    "SELECT * FROM parsed.document_subject_hints ORDER BY legacy_doc_id",
  );
  const parsedInventoryResult = await client.query(
    "SELECT * FROM parsed.inventory ORDER BY legacy_inv_id",
  );
  const parsedReadersResult = await client.query(
    "SELECT * FROM parsed.readers ORDER BY legacy_reader_id",
  );

  const bookpoints = sourceRows.BOOKPOINTS;
  const libsBookpoints = new Map(
    sourceRows.LIBS_BOOKPOINTS.map((row) => [row.BOOKPOINTS_ID, row.LIBS_ID]),
  );
  const libsById = new Map(sourceRows.LIBS.map((row) => [row.ID, row]));
  const pointsiglaBySigla = new Map(
    sourceRows.POINTSIGLA.map((row) => [row.IDS, row.IDP]),
  );

  const branches = bookpoints.map((row) => {
    const linkedLib = libsById.get(libsBookpoints.get(row.IDP));
    const code = normalizeCode(row.SHORTNAME, `BOOKPOINT_${row.IDP}`);
    return {
      id: randomUUID(),
      legacy_bookpoint_id: row.IDP,
      legacy_lib_id: linkedLib?.ID ?? null,
      code,
      name:
        normalizeText(linkedLib?.NAME) ||
        normalizeText(row.SHORTNAME) ||
        `Bookpoint ${row.IDP}`,
      source_payload: { bookpoint: row, library: linkedLib ?? null },
    };
  });

  const branchByBookpointId = new Map(
    branches.map((row) => [row.legacy_bookpoint_id, row]),
  );
  const branchByCode = new Map(branches.map((row) => [row.code, row]));

  const siglas = sourceRows.SIGLAS.map((row) => {
    const bookpointId = pointsiglaBySigla.get(row.ID) ?? null;
    const branch = bookpointId ? branchByBookpointId.get(bookpointId) : null;
    return {
      id: randomUUID(),
      legacy_sigla_id: row.ID,
      branch_id: branch?.id ?? null,
      shortname: normalizeText(row.SHORTNAME) || `SIGLA_${row.ID}`,
      storage: row.STORAGE ?? null,
      access_level: row.ACCLEVEL ?? null,
      source_payload: row,
    };
  });

  const siglaByLegacyId = new Map(
    siglas.map((row) => [row.legacy_sigla_id, row]),
  );

  const publisherMap = new Map();
  const publishers = [];
  for (const row of parsedDocumentsResult.rows) {
    const displayName = normalizeText(row.publisher_name);
    const normalizedName = normalizeName(displayName);
    if (!normalizedName || publisherMap.has(normalizedName)) {
      continue;
    }
    const publisher = {
      id: randomUUID(),
      normalized_name: normalizedName,
      display_name: displayName,
      source_payload: {},
    };
    publisherMap.set(normalizedName, publisher);
    publishers.push(publisher);
  }

  const authorMap = new Map();
  const authors = [];
  for (const row of parsedAuthorsResult.rows) {
    const displayName = normalizeText(row.author_name);
    const normalizedName = normalizeName(displayName);
    if (!normalizedName || authorMap.has(normalizedName)) {
      continue;
    }
    const author = {
      id: randomUUID(),
      normalized_name: normalizedName,
      display_name: displayName,
    };
    authorMap.set(normalizedName, author);
    authors.push(author);
  }

  const keywordMap = new Map();
  const keywords = [];
  for (const row of parsedKeywordsResult.rows) {
    const displayKeyword = normalizeText(row.keyword);
    const normalizedKeyword = normalizeName(displayKeyword);
    if (!normalizedKeyword || keywordMap.has(normalizedKeyword)) {
      continue;
    }
    const keyword = {
      id: randomUUID(),
      normalized_keyword: normalizedKeyword,
      display_keyword: displayKeyword,
    };
    keywordMap.set(normalizedKeyword, keyword);
    keywords.push(keyword);
  }

  const subjectMap = new Map();
  const subjects = [];
  for (const row of parsedSubjectsResult.rows) {
    const displaySubject = normalizeText(row.subject_name);
    const normalizedSubject = normalizeName(displaySubject);
    if (!normalizedSubject || subjectMap.has(normalizedSubject)) {
      continue;
    }
    const subject = {
      id: randomUUID(),
      normalized_subject: normalizedSubject,
      display_subject: displaySubject,
    };
    subjectMap.set(normalizedSubject, subject);
    subjects.push(subject);
  }

  const documents = parsedDocumentsResult.rows.map((row) => {
    const publisher = publisherMap.get(normalizeName(row.publisher_name));
    return {
      id: randomUUID(),
      legacy_doc_id: row.legacy_doc_id,
      control_number: row.control_number,
      title: row.title,
      subtitle: row.title_continuation,
      physical_media: row.physical_media,
      type_content_access: row.type_content_access,
      publication_place: row.publication_place,
      publisher_id: publisher?.id ?? null,
      publication_year: row.publication_year,
      isbn: row.isbn,
      language_code: row.language_code,
      faculty: row.faculty,
      department: row.department,
      specialization: row.specialization,
      literature_type: row.literature_type,
      raw_marc: row.raw_marc,
      source_payload: row.source_payload,
    };
  });

  const documentByLegacyDocId = new Map(
    documents.map((row) => [row.legacy_doc_id, row]),
  );
  const documentAuthors = parsedAuthorsResult.rows
    .map((row) => {
      const document = documentByLegacyDocId.get(row.legacy_doc_id);
      const author = authorMap.get(normalizeName(row.author_name));
      if (!document || !author) {
        return null;
      }
      return [document.id, author.id, row.author_role, row.sort_order];
    })
    .filter(Boolean);

  const documentKeywords = parsedKeywordsResult.rows
    .map((row) => {
      const document = documentByLegacyDocId.get(row.legacy_doc_id);
      const keyword = keywordMap.get(normalizeName(row.keyword));
      if (!document || !keyword) {
        return null;
      }
      return [document.id, keyword.id];
    })
    .filter(Boolean);

  const documentSubjects = parsedSubjectsResult.rows
    .map((row) => {
      const document = documentByLegacyDocId.get(row.legacy_doc_id);
      const subject = subjectMap.get(normalizeName(row.subject_name));
      if (!document || !subject) {
        return null;
      }
      return [document.id, subject.id, row.source_kind];
    })
    .filter(Boolean);

  const bookCopies = parsedInventoryResult.rows.map((row) => {
    const branchCode = normalizeCode(row.branch_hint, null);
    const sigla = row.sigla_id ? siglaByLegacyId.get(row.sigla_id) : null;
    const branch =
      (branchCode && branchByCode.get(branchCode)) ||
      (sigla?.branch_id
        ? branches.find((item) => item.id === sigla.branch_id)
        : null) ||
      null;
    return {
      id: randomUUID(),
      legacy_inv_id: row.legacy_inv_id,
      legacy_doc_id: row.legacy_doc_id,
      document_id: documentByLegacyDocId.get(row.legacy_doc_id)?.id ?? null,
      branch_id: branch?.id ?? null,
      storage_sigla_id: sigla?.id ?? null,
      inventory_number: row.inventory_number,
      branch_hint: row.branch_hint,
      price_raw: row.price_raw,
      registered_at: row.registered_at,
      state_code: row.state_code,
      track_index: row.track_index,
      source_payload: row.source_payload,
    };
  });

  const readerRows = parsedReadersResult.rows.map((row) => ({
    id: randomUUID(),
    legacy_reader_id: row.legacy_reader_id,
    legacy_code: row.code,
    full_name: row.full_name,
    email: row.email,
    work_phone: row.work_phone,
    employment: row.employment,
    course: row.course,
    profession: row.profession,
    sex: row.sex,
    birthday: row.birthday,
    registration_at: row.registration_at,
    reregistration_at: row.reregistration_at,
    bookpoints_raw: row.bookpoints_raw,
    source_payload: row.source_payload,
  }));

  const readerByLegacyId = new Map(
    readerRows.map((row) => [row.legacy_reader_id, row]),
  );
  const copyByLegacyInvId = new Map(
    bookCopies.map((row) => [row.legacy_inv_id, row]),
  );

  const copyEvents = sourceRows.BOOKSTATES.map((row) => {
    const branch = row.IDP ? branchByBookpointId.get(row.IDP) : null;
    const sigla = row.IDS ? siglaByLegacyId.get(row.IDS) : null;
    const copy = row.INV_ID ? copyByLegacyInvId.get(row.INV_ID) : null;
    const reader = normalizeText(row.RDR_ID)
      ? readerByLegacyId.get(normalizeText(row.RDR_ID))
      : null;
    return {
      id: randomUUID(),
      legacy_bookstate_id: row.IDBS,
      legacy_reader_id: normalizeText(row.RDR_ID),
      legacy_inv_id: row.INV_ID ?? null,
      legacy_doc_id: row.DOC_ID ?? null,
      book_copy_id: copy?.id ?? null,
      reader_id: reader?.id ?? null,
      branch_id: branch?.id ?? null,
      storage_sigla_id: sigla?.id ?? null,
      state_code: row.STATE ?? null,
      charged_at: excelDateToIso(row.CHSDATE),
      returned_at: excelDateToIso(row.RETDATE),
      flags: normalizeText(row.FLAGS),
      source_payload: row,
    };
  });

  await replaceTable(
    client,
    "core.library_branches",
    [
      "id",
      "legacy_bookpoint_id",
      "legacy_lib_id",
      "code",
      "name",
      "source_payload",
    ],
    branches.map((row) => [
      row.id,
      row.legacy_bookpoint_id,
      row.legacy_lib_id,
      row.code,
      row.name,
      row.source_payload,
    ]),
  );
  await replaceTable(
    client,
    "core.storage_siglas",
    [
      "id",
      "legacy_sigla_id",
      "branch_id",
      "shortname",
      "storage",
      "access_level",
      "source_payload",
    ],
    siglas.map((row) => [
      row.id,
      row.legacy_sigla_id,
      row.branch_id,
      row.shortname,
      row.storage,
      row.access_level,
      row.source_payload,
    ]),
  );
  await replaceTable(
    client,
    "core.publishers",
    ["id", "normalized_name", "display_name", "source_payload"],
    publishers.map((row) => [
      row.id,
      row.normalized_name,
      row.display_name,
      row.source_payload,
    ]),
  );
  await replaceTable(
    client,
    "core.authors",
    ["id", "normalized_name", "display_name"],
    authors.map((row) => [row.id, row.normalized_name, row.display_name]),
  );
  await replaceTable(
    client,
    "core.keywords",
    ["id", "normalized_keyword", "display_keyword"],
    keywords.map((row) => [
      row.id,
      row.normalized_keyword,
      row.display_keyword,
    ]),
  );
  await replaceTable(
    client,
    "core.subjects",
    ["id", "normalized_subject", "display_subject"],
    subjects.map((row) => [
      row.id,
      row.normalized_subject,
      row.display_subject,
    ]),
  );
  await replaceTable(
    client,
    "core.documents",
    [
      "id",
      "legacy_doc_id",
      "control_number",
      "title",
      "subtitle",
      "physical_media",
      "type_content_access",
      "publication_place",
      "publisher_id",
      "publication_year",
      "isbn",
      "language_code",
      "faculty",
      "department",
      "specialization",
      "literature_type",
      "raw_marc",
      "source_payload",
    ],
    documents.map((row) => [
      row.id,
      row.legacy_doc_id,
      row.control_number,
      row.title,
      row.subtitle,
      row.physical_media,
      row.type_content_access,
      row.publication_place,
      row.publisher_id,
      row.publication_year,
      row.isbn,
      row.language_code,
      row.faculty,
      row.department,
      row.specialization,
      row.literature_type,
      row.raw_marc,
      row.source_payload,
    ]),
  );
  await replaceTable(
    client,
    "core.document_authors",
    ["document_id", "author_id", "author_role", "sort_order"],
    documentAuthors,
  );
  await replaceTable(
    client,
    "core.document_keywords",
    ["document_id", "keyword_id"],
    documentKeywords,
  );
  await replaceTable(
    client,
    "core.document_subjects",
    ["document_id", "subject_id", "source_kind"],
    documentSubjects,
  );
  await replaceTable(
    client,
    "core.book_copies",
    [
      "id",
      "legacy_inv_id",
      "legacy_doc_id",
      "document_id",
      "branch_id",
      "storage_sigla_id",
      "inventory_number",
      "branch_hint",
      "price_raw",
      "registered_at",
      "state_code",
      "track_index",
      "source_payload",
    ],
    bookCopies.map((row) => [
      row.id,
      row.legacy_inv_id,
      row.legacy_doc_id,
      row.document_id,
      row.branch_id,
      row.storage_sigla_id,
      row.inventory_number,
      row.branch_hint,
      row.price_raw,
      row.registered_at,
      row.state_code,
      row.track_index,
      row.source_payload,
    ]),
  );
  await replaceTable(
    client,
    "core.legacy_readers",
    [
      "id",
      "legacy_reader_id",
      "legacy_code",
      "full_name",
      "email",
      "work_phone",
      "employment",
      "course",
      "profession",
      "sex",
      "birthday",
      "registration_at",
      "reregistration_at",
      "bookpoints_raw",
      "source_payload",
    ],
    readerRows.map((row) => [
      row.id,
      row.legacy_reader_id,
      row.legacy_code,
      row.full_name,
      row.email,
      row.work_phone,
      row.employment,
      row.course,
      row.profession,
      row.sex,
      row.birthday,
      row.registration_at,
      row.reregistration_at,
      row.bookpoints_raw,
      row.source_payload,
    ]),
  );
  await replaceTable(
    client,
    "core.legacy_copy_events",
    [
      "id",
      "legacy_bookstate_id",
      "legacy_reader_id",
      "legacy_inv_id",
      "legacy_doc_id",
      "book_copy_id",
      "reader_id",
      "branch_id",
      "storage_sigla_id",
      "state_code",
      "charged_at",
      "returned_at",
      "flags",
      "source_payload",
    ],
    copyEvents.map((row) => [
      row.id,
      row.legacy_bookstate_id,
      row.legacy_reader_id,
      row.legacy_inv_id,
      row.legacy_doc_id,
      row.book_copy_id,
      row.reader_id,
      row.branch_id,
      row.storage_sigla_id,
      row.state_code,
      row.charged_at,
      row.returned_at,
      row.flags,
      row.source_payload,
    ]),
  );

  return {
    documents,
    bookCopies,
    readerRows,
    copyEvents,
    branches,
    siglas,
  };
}

function buildQualityIssues(
  coreState,
  parsedDocs,
  parsedAuthorsRows,
  sourceRows,
  batchId,
) {
  const issues = [];
  const authorCountByDoc = new Map();
  for (const row of parsedAuthorsRows) {
    authorCountByDoc.set(
      row.legacy_doc_id,
      (authorCountByDoc.get(row.legacy_doc_id) ?? 0) + 1,
    );
  }

  for (const doc of parsedDocs) {
    const sourceKey = String(doc.legacy_doc_id);
    if (!normalizeText(doc.title)) {
      issues.push({
        issue_code: "missing_title",
        severity: "CRITICAL",
        source_schema: "parsed",
        source_table: "documents",
        source_key: sourceKey,
        core_entity: "documents",
        core_entity_key: sourceKey,
        summary: "Document is missing a title.",
        details: { legacyDocId: doc.legacy_doc_id },
      });
    }
    if ((authorCountByDoc.get(doc.legacy_doc_id) ?? 0) === 0) {
      issues.push({
        issue_code: "missing_author",
        severity: "HIGH",
        source_schema: "parsed",
        source_table: "documents",
        source_key: sourceKey,
        core_entity: "documents",
        core_entity_key: sourceKey,
        summary: "Document has no extracted author.",
        details: { legacyDocId: doc.legacy_doc_id },
      });
    }
    if (!doc.publication_year) {
      issues.push({
        issue_code: "missing_publication_year",
        severity: "HIGH",
        source_schema: "parsed",
        source_table: "documents",
        source_key: sourceKey,
        core_entity: "documents",
        core_entity_key: sourceKey,
        summary: "Document has no normalized publication year.",
        details: {
          legacyDocId: doc.legacy_doc_id,
          rawYear: doc.publication_year_raw,
        },
      });
    }
    if (!normalizeText(doc.isbn)) {
      issues.push({
        issue_code: "missing_isbn",
        severity: "MEDIUM",
        source_schema: "parsed",
        source_table: "documents",
        source_key: sourceKey,
        core_entity: "documents",
        core_entity_key: sourceKey,
        summary: "Document is missing ISBN.",
        details: { legacyDocId: doc.legacy_doc_id },
      });
    }
    if (!normalizeText(doc.language_code)) {
      issues.push({
        issue_code: "missing_language_code",
        severity: "MEDIUM",
        source_schema: "parsed",
        source_table: "documents",
        source_key: sourceKey,
        core_entity: "documents",
        core_entity_key: sourceKey,
        summary: "Document is missing language code.",
        details: { legacyDocId: doc.legacy_doc_id },
      });
    }
    const density = [
      doc.title,
      doc.publisher_name,
      doc.publication_year,
      doc.isbn,
      doc.language_code,
      doc.faculty,
      doc.department,
      doc.specialization,
    ].filter(
      (value) => value !== null && value !== undefined && value !== "",
    ).length;
    if (density <= 2) {
      issues.push({
        issue_code: "suspiciously_sparse_record",
        severity: "LOW",
        source_schema: "parsed",
        source_table: "documents",
        source_key: sourceKey,
        core_entity: "documents",
        core_entity_key: sourceKey,
        summary: "Document record is suspiciously sparse.",
        details: { legacyDocId: doc.legacy_doc_id, density },
      });
    }
  }

  const docIds = new Set(parsedDocs.map((row) => row.legacy_doc_id));
  for (const inv of coreState.bookCopies) {
    if (inv.legacy_doc_id && !docIds.has(inv.legacy_doc_id)) {
      issues.push({
        issue_code: "orphan_inventory_row",
        severity: "HIGH",
        source_schema: "raw",
        source_table: "inv",
        source_key: String(inv.legacy_inv_id),
        core_entity: "book_copies",
        core_entity_key: String(inv.legacy_inv_id),
        summary: "Inventory row points to a missing document.",
        details: {
          legacyInvId: inv.legacy_inv_id,
          legacyDocId: inv.legacy_doc_id,
        },
      });
    }
  }

  const readerIds = new Set(
    coreState.readerRows.map((row) => row.legacy_reader_id),
  );
  for (const row of sourceRows.RDRBP) {
    const readerId = normalizeText(row.RDR_ID);
    if (readerId && !readerIds.has(readerId)) {
      issues.push({
        issue_code: "orphan_reader_reference",
        severity: "HIGH",
        source_schema: "raw",
        source_table: "rdrbp",
        source_key: String(row.IDRBP),
        core_entity: "legacy_readers",
        core_entity_key: readerId,
        summary: "RDRBP references a reader missing from READERS.",
        details: { legacyReaderId: readerId, legacyRdrbpId: row.IDRBP },
      });
    }
  }

  for (const row of sourceRows.BOOKSTATES) {
    const readerId = normalizeText(row.RDR_ID);
    if (readerId && !readerIds.has(readerId)) {
      issues.push({
        issue_code: "orphan_reader_reference",
        severity: "HIGH",
        source_schema: "raw",
        source_table: "bookstates",
        source_key: String(row.IDBS),
        core_entity: "legacy_readers",
        core_entity_key: readerId,
        summary: "BOOKSTATES references a reader missing from READERS.",
        details: { legacyReaderId: readerId, legacyBookstateId: row.IDBS },
      });
    }
  }

  const isbnGroups = new Map();
  for (const doc of parsedDocs) {
    const isbn = normalizeText(doc.isbn);
    if (!isbn) {
      continue;
    }
    const list = isbnGroups.get(isbn) ?? [];
    list.push(doc.legacy_doc_id);
    isbnGroups.set(isbn, list);
  }
  for (const [isbn, ids] of isbnGroups) {
    if (ids.length > 1) {
      issues.push({
        issue_code: "probable_duplicate_isbn",
        severity: "HIGH",
        source_schema: "parsed",
        source_table: "documents",
        source_key: ids.join(","),
        core_entity: "documents",
        core_entity_key: isbn,
        summary: "Multiple documents share the same ISBN.",
        details: { isbn, legacyDocIds: ids },
      });
    }
  }

  const fingerprintGroups = new Map();
  for (const doc of parsedDocs) {
    const fingerprint = [
      normalizeName(doc.title),
      doc.publication_year ?? "",
      normalizeName(doc.primary_author_text),
    ].join("|");
    if (!fingerprint || fingerprint === "||") {
      continue;
    }
    const list = fingerprintGroups.get(fingerprint) ?? [];
    list.push(doc.legacy_doc_id);
    fingerprintGroups.set(fingerprint, list);
  }
  for (const [fingerprint, ids] of fingerprintGroups) {
    if (ids.length > 1) {
      issues.push({
        issue_code: "probable_duplicate_document",
        severity: "MEDIUM",
        source_schema: "parsed",
        source_table: "documents",
        source_key: ids.join(","),
        core_entity: "documents",
        core_entity_key: fingerprint,
        summary: "Documents share a strong duplicate fingerprint.",
        details: { fingerprint, legacyDocIds: ids },
      });
    }
  }

  return issues.map((issue) => [
    randomUUID(),
    batchId,
    issue.issue_code,
    issue.severity,
    "OPEN",
    issue.source_schema,
    issue.source_table,
    issue.source_key,
    issue.core_entity,
    issue.core_entity_key,
    issue.summary,
    issue.details,
  ]);
}

async function logImport(
  client,
  batchId,
  stage,
  sourceTable,
  level,
  message,
  rowCount,
  details = {},
) {
  await client.query(
    `INSERT INTO migration.import_logs (id, batch_id, stage, source_table, level, message, row_count, details) VALUES ($1,$2,$3,$4,$5,$6,$7,$8)`,
    [
      randomUUID(),
      batchId,
      stage,
      sourceTable,
      level,
      message,
      rowCount ?? null,
      details,
    ],
  );
}

async function main() {
  const timestamp = new Date().toISOString().replace(/[:.]/g, "-");
  const batchId = randomUUID();
  const exportDir = path.resolve(
    __dirname,
    "../../../migration/raw/legacy-reconstruction",
    timestamp,
  );
  const logDir = path.resolve(__dirname, "../../../migration/logs");
  ensureDir(exportDir);
  ensureDir(logDir);

  loadSqlServerTables(exportDir);

  const sourceRows = {};
  for (const table of SOURCE_TABLES) {
    sourceRows[table] = await readNdjson(
      path.join(exportDir, `${table.toLowerCase()}.ndjson`),
    );
  }

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  try {
    const schemaSql = fs.readFileSync(
      path.resolve(__dirname, "legacy-reconstruction-schema.sql"),
      "utf8",
    );
    await client.query(schemaSql);

    await client.query(
      `INSERT INTO migration.import_batches (id, source_system, source_database, status, notes, metrics) VALUES ($1, $2, $3, $4, $5, $6)`,
      [
        batchId,
        "sqlserver",
        process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
        "IN_PROGRESS",
        "Automated full legacy DB reconstruction batch",
        {},
      ],
    );

    const rawConfig = buildRawInsertConfig(batchId);
    const reconstructionTables = [
      "review.issue_notes",
      "review.quality_issues",
      "core.legacy_copy_events",
      "core.book_copies",
      "core.document_subjects",
      "core.document_keywords",
      "core.document_authors",
      "core.documents",
      "core.storage_siglas",
      "core.library_branches",
      "core.subjects",
      "core.keywords",
      "core.authors",
      "core.publishers",
      "core.legacy_readers",
      "parsed.document_subject_hints",
      "parsed.document_keywords",
      "parsed.document_authors",
      "parsed.documents",
      "parsed.inventory",
      "parsed.readers",
      "raw.preorders",
      "raw.bookstates",
      "raw.publisher",
      "raw.libs_siglas",
      "raw.libs_bookpoints",
      "raw.libs",
      "raw.pointsigla",
      "raw.siglas",
      "raw.bookpoints",
      "raw.rdrbp",
      "raw.readers",
      "raw.inv",
      "raw.doc_view",
      "raw.doc",
    ];

    for (const table of reconstructionTables) {
      await client.query(`TRUNCATE TABLE ${table} RESTART IDENTITY CASCADE`);
    }

    for (const table of SOURCE_TABLES) {
      const config = rawConfig[table];
      const rows = sourceRows[table].map(config.map);
      await batchInsert(client, config.table, config.columns, rows, config.key);
      await logImport(
        client,
        batchId,
        "raw",
        table,
        "INFO",
        `Ingested ${table} into raw schema`,
        rows.length,
        { exportDir },
      );
    }

    const { parsedDocuments, parsedAuthors, parsedKeywords, parsedSubjects } =
      buildParsedDocuments(sourceRows.DOC, sourceRows.DOC_VIEW, batchId);
    const parsedInventory = buildParsedInventory(sourceRows.INV, batchId);
    const parsedReaders = buildParsedReaders(sourceRows.READERS, batchId);

    await replaceTable(
      client,
      "parsed.documents",
      [
        "legacy_doc_id",
        "control_number",
        "rectype",
        "biblevel",
        "title",
        "title_continuation",
        "physical_media",
        "type_content_access",
        "primary_author_text",
        "publication_place",
        "publisher_name",
        "publication_year",
        "publication_year_raw",
        "isbn",
        "isbn_raw",
        "language_code",
        "keywords",
        "faculty",
        "department",
        "subject_text",
        "specialization",
        "record_creation_year",
        "record_creation_month",
        "record_creation_date",
        "literature_type",
        "raw_marc",
        "source_payload",
        "batch_id",
      ],
      parsedDocuments,
    );
    await replaceTable(
      client,
      "parsed.document_authors",
      ["legacy_doc_id", "author_name", "author_role", "sort_order"],
      parsedAuthors,
    );
    await replaceTable(
      client,
      "parsed.document_keywords",
      ["legacy_doc_id", "keyword", "sort_order"],
      parsedKeywords,
    );
    await replaceTable(
      client,
      "parsed.document_subject_hints",
      ["legacy_doc_id", "subject_name", "source_kind"],
      parsedSubjects,
    );
    await replaceTable(
      client,
      "parsed.inventory",
      [
        "legacy_inv_id",
        "legacy_doc_id",
        "inventory_number",
        "branch_hint",
        "price_raw",
        "registered_at",
        "registered_at_raw",
        "sigla_id",
        "state_code",
        "track_index",
        "source_payload",
        "batch_id",
      ],
      parsedInventory,
    );
    await replaceTable(
      client,
      "parsed.readers",
      [
        "legacy_reader_id",
        "code",
        "full_name",
        "email",
        "work_phone",
        "employment",
        "course",
        "profession",
        "sex",
        "birthday",
        "birthday_raw",
        "registration_at",
        "registration_raw",
        "reregistration_at",
        "reregistration_raw",
        "bookpoints_raw",
        "source_payload",
        "batch_id",
      ],
      parsedReaders,
    );

    await logImport(
      client,
      batchId,
      "parsed",
      "DOC/DOC_VIEW",
      "INFO",
      "Built parsed document layer",
      parsedDocuments.length,
    );
    await logImport(
      client,
      batchId,
      "parsed",
      "INV",
      "INFO",
      "Built parsed inventory layer",
      parsedInventory.length,
    );
    await logImport(
      client,
      batchId,
      "parsed",
      "READERS",
      "INFO",
      "Built parsed readers layer",
      parsedReaders.length,
    );

    const coreState = await loadCore(client, batchId, sourceRows);
    await logImport(
      client,
      batchId,
      "core",
      "documents",
      "INFO",
      "Built normalized document layer",
      coreState.documents.length,
    );
    await logImport(
      client,
      batchId,
      "core",
      "book_copies",
      "INFO",
      "Built normalized copy layer",
      coreState.bookCopies.length,
    );
    await logImport(
      client,
      batchId,
      "core",
      "legacy_readers",
      "INFO",
      "Built normalized reader layer",
      coreState.readerRows.length,
    );

    const parsedDocsForIssues = await client.query(
      "SELECT * FROM parsed.documents ORDER BY legacy_doc_id",
    );
    const parsedAuthorsForIssues = await client.query(
      "SELECT legacy_doc_id FROM parsed.document_authors",
    );
    const qualityIssues = buildQualityIssues(
      coreState,
      parsedDocsForIssues.rows,
      parsedAuthorsForIssues.rows,
      sourceRows,
      batchId,
    );
    await replaceTable(
      client,
      "review.quality_issues",
      [
        "id",
        "batch_id",
        "issue_code",
        "severity",
        "status",
        "source_schema",
        "source_table",
        "source_key",
        "core_entity",
        "core_entity_key",
        "summary",
        "details",
      ],
      qualityIssues,
    );
    await replaceTable(
      client,
      "review.issue_notes",
      ["id", "issue_id", "author", "note"],
      [],
    );
    await logImport(
      client,
      batchId,
      "review",
      "quality_issues",
      "INFO",
      "Generated automated quality issues",
      qualityIssues.length,
    );

    const counts = {
      source: Object.fromEntries(
        SOURCE_TABLES.map((table) => [table, sourceRows[table].length]),
      ),
      parsed: {
        documents: parsedDocuments.length,
        inventory: parsedInventory.length,
        readers: parsedReaders.length,
      },
      core: {
        documents: coreState.documents.length,
        bookCopies: coreState.bookCopies.length,
        legacyReaders: coreState.readerRows.length,
        copyEvents: coreState.copyEvents.length,
        branches: coreState.branches.length,
        siglas: coreState.siglas.length,
      },
      review: {
        qualityIssues: qualityIssues.length,
      },
    };

    const sampleChecks = {
      documentsWithTitle: (
        await client.query(
          "SELECT count(*)::int AS count FROM core.documents WHERE title IS NOT NULL",
        )
      ).rows[0].count,
      copiesLinkedToDocuments: (
        await client.query(
          "SELECT count(*)::int AS count FROM core.book_copies WHERE document_id IS NOT NULL",
        )
      ).rows[0].count,
      readerRowsLoaded: (
        await client.query(
          "SELECT count(*)::int AS count FROM core.legacy_readers",
        )
      ).rows[0].count,
    };

    const summary = {
      batchId,
      exportedAt: timestamp,
      sourceDatabase: process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
      counts,
      sampleChecks,
      exportDir,
    };

    const summaryPath = path.resolve(
      logDir,
      `legacy-reconstruction-summary-${batchId}.json`,
    );
    fs.writeFileSync(summaryPath, JSON.stringify(summary, null, 2));

    await client.query(
      `UPDATE migration.import_batches SET status = $2, completed_at = now(), metrics = $3 WHERE id = $1`,
      [batchId, "COMPLETED", summary],
    );

    console.log(JSON.stringify(summary, null, 2));
  } catch (error) {
    await client
      .query(
        `INSERT INTO migration.import_batches (id, source_system, source_database, status, notes, metrics)
       VALUES ($1, $2, $3, $4, $5, $6)
       ON CONFLICT (id) DO UPDATE SET status = EXCLUDED.status, completed_at = now(), notes = EXCLUDED.notes, metrics = EXCLUDED.metrics`,
        [
          batchId,
          "sqlserver",
          process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
          "FAILED",
          error.message,
          { stack: error.stack },
        ],
      )
      .catch(() => undefined);
    throw error;
  } finally {
    await client.end();
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});

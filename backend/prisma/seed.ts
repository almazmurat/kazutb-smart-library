import {
  PrismaClient,
  UserRole,
  CopyStatus,
  LoanStatus,
  ReservationStatus,
  InstitutionScopeCode,
  LibraryBranchCode,
} from "@prisma/client";

const prisma = new PrismaClient();
const DAY = 24 * 60 * 60 * 1000;

function daysAgo(days: number) {
  return new Date(Date.now() - days * DAY);
}

function daysFromNow(days: number) {
  return new Date(Date.now() + days * DAY);
}

async function main() {
  const universityScope = await prisma.institutionScope.upsert({
    where: { code: InstitutionScopeCode.UNIVERSITY },
    update: { name: "University", isActive: true },
    create: {
      code: InstitutionScopeCode.UNIVERSITY,
      name: "University",
    },
  });

  const collegeScope = await prisma.institutionScope.upsert({
    where: { code: InstitutionScopeCode.COLLEGE },
    update: { name: "College", isActive: true },
    create: {
      code: InstitutionScopeCode.COLLEGE,
      name: "College",
    },
  });

  const economicBranch = await prisma.libraryBranch.upsert({
    where: { code: LibraryBranchCode.ECONOMIC_LIBRARY },
    update: {
      name: "Economic Library",
      scopeId: universityScope.id,
      isActive: true,
    },
    create: {
      code: LibraryBranchCode.ECONOMIC_LIBRARY,
      name: "Economic Library",
      scopeId: universityScope.id,
    },
  });

  const technologicalBranch = await prisma.libraryBranch.upsert({
    where: { code: LibraryBranchCode.TECHNOLOGICAL_LIBRARY },
    update: {
      name: "Technological Library",
      scopeId: universityScope.id,
      isActive: true,
    },
    create: {
      code: LibraryBranchCode.TECHNOLOGICAL_LIBRARY,
      name: "Technological Library",
      scopeId: universityScope.id,
    },
  });

  const collegeBranch = await prisma.libraryBranch.upsert({
    where: { code: LibraryBranchCode.COLLEGE_LIBRARY },
    update: {
      name: "College Library",
      scopeId: collegeScope.id,
      isActive: true,
    },
    create: {
      code: LibraryBranchCode.COLLEGE_LIBRARY,
      name: "College Library",
      scopeId: collegeScope.id,
    },
  });

  const admin = await prisma.user.upsert({
    where: { universityId: "admin" },
    update: {
      email: "admin@kazutb.edu.kz",
      fullName: "System Administrator",
      role: UserRole.ADMIN,
      isActive: true,
      institutionScopeId: universityScope.id,
      libraryBranchId: null,
    },
    create: {
      universityId: "admin",
      email: "admin@kazutb.edu.kz",
      fullName: "System Administrator",
      role: UserRole.ADMIN,
      institutionScopeId: universityScope.id,
    },
  });

  const analyst = await prisma.user.upsert({
    where: { universityId: "analyst1" },
    update: {
      email: "analyst@kazutb.edu.kz",
      fullName: "Library Operations Analyst",
      role: UserRole.ANALYST,
      isActive: true,
      institutionScopeId: universityScope.id,
      libraryBranchId: null,
    },
    create: {
      universityId: "analyst1",
      email: "analyst@kazutb.edu.kz",
      fullName: "Library Operations Analyst",
      role: UserRole.ANALYST,
      institutionScopeId: universityScope.id,
    },
  });

  const librarianEconomic = await prisma.user.upsert({
    where: { universityId: "librarian_econ" },
    update: {
      email: "librarian.econ@kazutb.edu.kz",
      fullName: "Aigul T. Bekmukhan",
      role: UserRole.LIBRARIAN,
      isActive: true,
      institutionScopeId: universityScope.id,
      libraryBranchId: economicBranch.id,
    },
    create: {
      universityId: "librarian_econ",
      email: "librarian.econ@kazutb.edu.kz",
      fullName: "Aigul T. Bekmukhan",
      role: UserRole.LIBRARIAN,
      institutionScopeId: universityScope.id,
      libraryBranchId: economicBranch.id,
    },
  });

  const librarianTechnological = await prisma.user.upsert({
    where: { universityId: "librarian_tech" },
    update: {
      email: "librarian.tech@kazutb.edu.kz",
      fullName: "Dias M. Sarsen",
      role: UserRole.LIBRARIAN,
      isActive: true,
      institutionScopeId: universityScope.id,
      libraryBranchId: technologicalBranch.id,
    },
    create: {
      universityId: "librarian_tech",
      email: "librarian.tech@kazutb.edu.kz",
      fullName: "Dias M. Sarsen",
      role: UserRole.LIBRARIAN,
      institutionScopeId: universityScope.id,
      libraryBranchId: technologicalBranch.id,
    },
  });

  const librarianCollege = await prisma.user.upsert({
    where: { universityId: "librarian_college" },
    update: {
      email: "librarian.college@kazutb.edu.kz",
      fullName: "Maira K. Kozhakhmet",
      role: UserRole.LIBRARIAN,
      isActive: true,
      institutionScopeId: collegeScope.id,
      libraryBranchId: collegeBranch.id,
    },
    create: {
      universityId: "librarian_college",
      email: "librarian.college@kazutb.edu.kz",
      fullName: "Maira K. Kozhakhmet",
      role: UserRole.LIBRARIAN,
      institutionScopeId: collegeScope.id,
      libraryBranchId: collegeBranch.id,
    },
  });

  const studentOne = await prisma.user.upsert({
    where: { universityId: "student1" },
    update: {
      email: "student1@kazutb.edu.kz",
      fullName: "Assel N. Tursyn",
      role: UserRole.STUDENT,
      isActive: true,
      institutionScopeId: universityScope.id,
      libraryBranchId: economicBranch.id,
    },
    create: {
      universityId: "student1",
      email: "student1@kazutb.edu.kz",
      fullName: "Assel N. Tursyn",
      role: UserRole.STUDENT,
      institutionScopeId: universityScope.id,
      libraryBranchId: economicBranch.id,
    },
  });

  const studentTwo = await prisma.user.upsert({
    where: { universityId: "student2" },
    update: {
      email: "student2@kazutb.edu.kz",
      fullName: "Mansur R. Alimov",
      role: UserRole.STUDENT,
      isActive: true,
      institutionScopeId: collegeScope.id,
      libraryBranchId: collegeBranch.id,
    },
    create: {
      universityId: "student2",
      email: "student2@kazutb.edu.kz",
      fullName: "Mansur R. Alimov",
      role: UserRole.STUDENT,
      institutionScopeId: collegeScope.id,
      libraryBranchId: collegeBranch.id,
    },
  });

  const teacherOne = await prisma.user.upsert({
    where: { universityId: "teacher1" },
    update: {
      email: "teacher1@kazutb.edu.kz",
      fullName: "Professor Aida S. Nurpeis",
      role: UserRole.TEACHER,
      isActive: true,
      institutionScopeId: universityScope.id,
      libraryBranchId: technologicalBranch.id,
    },
    create: {
      universityId: "teacher1",
      email: "teacher1@kazutb.edu.kz",
      fullName: "Professor Aida S. Nurpeis",
      role: UserRole.TEACHER,
      institutionScopeId: universityScope.id,
      libraryBranchId: technologicalBranch.id,
    },
  });

  const publisherKazutb = await prisma.publisher.upsert({
    where: { name: "KazUTB Press" },
    update: {
      city: "Astana",
      country: "Kazakhstan",
    },
    create: {
      name: "KazUTB Press",
      city: "Astana",
      country: "Kazakhstan",
    },
  });

  const publisherAtlas = await prisma.publisher.upsert({
    where: { name: "Atlas Academic" },
    update: {
      city: "Almaty",
      country: "Kazakhstan",
    },
    create: {
      name: "Atlas Academic",
      city: "Almaty",
      country: "Kazakhstan",
    },
  });

  const publisherTech = await prisma.publisher.upsert({
    where: { name: "TechnoEducation Publishing" },
    update: {
      city: "Karaganda",
      country: "Kazakhstan",
    },
    create: {
      name: "TechnoEducation Publishing",
      city: "Karaganda",
      country: "Kazakhstan",
    },
  });

  const authorNames = [
    "Nurzhan A. Saparov",
    "Aigerim K. Utepova",
    "Timur B. Zhaksylykov",
    "Saule M. Nurgaliyeva",
    "Yelena V. Morozova",
    "Marat D. Kassen",
  ];

  const authorMap = new Map<string, string>();
  for (const fullName of authorNames) {
    const author = await prisma.author.upsert({
      where: { fullName },
      update: { isActive: true },
      create: {
        fullName,
        isActive: true,
      },
    });
    authorMap.set(fullName, author.id);
  }

  async function upsertRootCategory(name: string, code: string) {
    const existing = await prisma.category.findFirst({
      where: { name, parentId: null },
    });

    if (existing) {
      return prisma.category.update({
        where: { id: existing.id },
        data: {
          code,
          isActive: true,
        },
      });
    }

    return prisma.category.create({
      data: {
        name,
        code,
        isActive: true,
      },
    });
  }

  const categoryDigital = await upsertRootCategory(
    "Digital Library Systems",
    "CAT-DLS",
  );
  const categoryEconomics = await upsertRootCategory(
    "Applied Economics",
    "CAT-ECO",
  );
  const categorySoftware = await upsertRootCategory(
    "Software Engineering",
    "CAT-SWE",
  );
  const categoryData = await upsertRootCategory("Data Analytics", "CAT-DAT");
  const categoryCollege = await upsertRootCategory(
    "College Foundation Studies",
    "CAT-CFS",
  );

  const bookDefinitions = [
    {
      title: "Introduction to Digital Libraries",
      isbn: "9786010001001",
      publishYear: 2024,
      language: "en",
      description:
        "Foundational concepts for modern digital library platforms.",
      keywords: ["library", "digital", "catalog", "metadata"],
      branchId: economicBranch.id,
      publisherId: publisherKazutb.id,
      authorIds: [authorMap.get("Nurzhan A. Saparov")!],
      categoryIds: [categoryDigital.id],
    },
    {
      title: "University Service Economics",
      isbn: "9786010001002",
      publishYear: 2022,
      language: "kk",
      description:
        "Institutional budgeting and service economics for higher education libraries.",
      keywords: ["economics", "budget", "services"],
      branchId: economicBranch.id,
      publisherId: publisherAtlas.id,
      authorIds: [
        authorMap.get("Aigerim K. Utepova")!,
        authorMap.get("Marat D. Kassen")!,
      ],
      categoryIds: [categoryEconomics.id],
    },
    {
      title: "Information Retrieval for Libraries",
      isbn: "9786010001003",
      publishYear: 2021,
      language: "ru",
      description:
        "Search relevance, metadata quality, and retrieval techniques for catalog systems.",
      keywords: ["search", "retrieval", "metadata"],
      branchId: technologicalBranch.id,
      publisherId: publisherTech.id,
      authorIds: [
        authorMap.get("Yelena V. Morozova")!,
        authorMap.get("Timur B. Zhaksylykov")!,
      ],
      categoryIds: [categoryDigital.id, categorySoftware.id],
    },
    {
      title: "Practical Software Architecture",
      isbn: "9786010001004",
      publishYear: 2023,
      language: "en",
      description:
        "Applied architecture patterns for modular university systems.",
      keywords: ["architecture", "software", "modular"],
      branchId: technologicalBranch.id,
      publisherId: publisherTech.id,
      authorIds: [authorMap.get("Timur B. Zhaksylykov")!],
      categoryIds: [categorySoftware.id],
    },
    {
      title: "College Mathematics Primer",
      isbn: "9786010001005",
      publishYear: 2020,
      language: "kk",
      description: "Core mathematics module for first-year college cohorts.",
      keywords: ["mathematics", "college", "foundation"],
      branchId: collegeBranch.id,
      publisherId: publisherKazutb.id,
      authorIds: [authorMap.get("Saule M. Nurgaliyeva")!],
      categoryIds: [categoryCollege.id],
    },
    {
      title: "College English Communication",
      isbn: "9786010001006",
      publishYear: 2021,
      language: "en",
      description:
        "Academic communication standards for multilingual college classrooms.",
      keywords: ["english", "communication", "college"],
      branchId: collegeBranch.id,
      publisherId: publisherAtlas.id,
      authorIds: [authorMap.get("Aigerim K. Utepova")!],
      categoryIds: [categoryCollege.id],
    },
    {
      title: "Library Analytics Handbook",
      isbn: "9786010001007",
      publishYear: 2024,
      language: "ru",
      description: "Operational KPI design for academic library managers.",
      keywords: ["analytics", "kpi", "operations"],
      branchId: economicBranch.id,
      publisherId: publisherKazutb.id,
      authorIds: [
        authorMap.get("Marat D. Kassen")!,
        authorMap.get("Yelena V. Morozova")!,
      ],
      categoryIds: [categoryData.id, categoryDigital.id],
    },
    {
      title: "Data Literacy for Engineers",
      isbn: "9786010001008",
      publishYear: 2023,
      language: "en",
      description:
        "Data analysis fundamentals for engineering students and faculty.",
      keywords: ["data", "engineering", "literacy"],
      branchId: technologicalBranch.id,
      publisherId: publisherTech.id,
      authorIds: [
        authorMap.get("Nurzhan A. Saparov")!,
        authorMap.get("Saule M. Nurgaliyeva")!,
      ],
      categoryIds: [categoryData.id],
    },
  ];

  const books = new Map<string, string>();
  for (const definition of bookDefinitions) {
    const book = await prisma.book.upsert({
      where: { isbn: definition.isbn },
      update: {
        title: definition.title,
        publishYear: definition.publishYear,
        language: definition.language,
        description: definition.description,
        keywords: definition.keywords,
        publisherId: definition.publisherId,
        libraryBranchId: definition.branchId,
        isActive: true,
        authors: {
          deleteMany: {},
          create: definition.authorIds.map((authorId) => ({ authorId })),
        },
        categories: {
          deleteMany: {},
          create: definition.categoryIds.map((categoryId) => ({ categoryId })),
        },
      },
      create: {
        title: definition.title,
        isbn: definition.isbn,
        publishYear: definition.publishYear,
        language: definition.language,
        description: definition.description,
        keywords: definition.keywords,
        publisherId: definition.publisherId,
        libraryBranchId: definition.branchId,
        isActive: true,
        authors: {
          create: definition.authorIds.map((authorId) => ({ authorId })),
        },
        categories: {
          create: definition.categoryIds.map((categoryId) => ({ categoryId })),
        },
      },
    });

    books.set(definition.isbn, book.id);
  }

  const copyDefinitions = [
    {
      inventoryNumber: "INV-ECO-001",
      isbn: "9786010001001",
      branchId: economicBranch.id,
    },
    {
      inventoryNumber: "INV-ECO-002",
      isbn: "9786010001002",
      branchId: economicBranch.id,
    },
    {
      inventoryNumber: "INV-ECO-003",
      isbn: "9786010001007",
      branchId: economicBranch.id,
    },
    {
      inventoryNumber: "INV-TECH-001",
      isbn: "9786010001003",
      branchId: technologicalBranch.id,
    },
    {
      inventoryNumber: "INV-TECH-002",
      isbn: "9786010001004",
      branchId: technologicalBranch.id,
    },
    {
      inventoryNumber: "INV-TECH-003",
      isbn: "9786010001008",
      branchId: technologicalBranch.id,
    },
    {
      inventoryNumber: "INV-COL-001",
      isbn: "9786010001005",
      branchId: collegeBranch.id,
    },
    {
      inventoryNumber: "INV-COL-002",
      isbn: "9786010001006",
      branchId: collegeBranch.id,
    },
    {
      inventoryNumber: "INV-COL-003",
      isbn: "9786010001005",
      branchId: collegeBranch.id,
    },
  ];

  const copyMap = new Map<string, string>();
  for (const definition of copyDefinitions) {
    const copy = await prisma.bookCopy.upsert({
      where: { inventoryNumber: definition.inventoryNumber },
      update: {
        bookId: books.get(definition.isbn)!,
        libraryBranchId: definition.branchId,
        fund: "Main",
        status: CopyStatus.AVAILABLE,
      },
      create: {
        bookId: books.get(definition.isbn)!,
        inventoryNumber: definition.inventoryNumber,
        fund: "Main",
        status: CopyStatus.AVAILABLE,
        libraryBranchId: definition.branchId,
      },
    });

    copyMap.set(definition.inventoryNumber, copy.id);
  }

  const demoUserIds = [studentOne.id, studentTwo.id, teacherOne.id];
  await prisma.reservation.deleteMany({
    where: { userId: { in: demoUserIds } },
  });
  await prisma.loan.deleteMany({ where: { userId: { in: demoUserIds } } });

  await prisma.bookCopy.updateMany({
    where: {
      inventoryNumber: {
        in: copyDefinitions.map((copy) => copy.inventoryNumber),
      },
    },
    data: {
      status: CopyStatus.AVAILABLE,
    },
  });

  await prisma.loan.createMany({
    data: [
      {
        userId: studentOne.id,
        copyId: copyMap.get("INV-ECO-002")!,
        libraryBranchId: economicBranch.id,
        issuedBy: librarianEconomic.id,
        loanedAt: daysAgo(24),
        dueDate: daysAgo(6),
        status: LoanStatus.ACTIVE,
        notes: "Demo overdue loan in economic branch",
      },
      {
        userId: teacherOne.id,
        copyId: copyMap.get("INV-TECH-001")!,
        libraryBranchId: technologicalBranch.id,
        issuedBy: librarianTechnological.id,
        loanedAt: daysAgo(36),
        dueDate: daysAgo(20),
        returnedAt: daysAgo(18),
        status: LoanStatus.RETURNED,
        notes: "Completed circulation flow",
      },
      {
        userId: studentTwo.id,
        copyId: copyMap.get("INV-COL-001")!,
        libraryBranchId: collegeBranch.id,
        issuedBy: librarianCollege.id,
        loanedAt: daysAgo(3),
        dueDate: daysFromNow(9),
        status: LoanStatus.ACTIVE,
        notes: "Active college branch loan",
      },
      {
        userId: teacherOne.id,
        copyId: copyMap.get("INV-TECH-002")!,
        libraryBranchId: technologicalBranch.id,
        issuedBy: librarianTechnological.id,
        loanedAt: daysAgo(58),
        dueDate: daysAgo(32),
        status: LoanStatus.LOST,
        notes: "Lost item scenario for operations dashboard",
      },
      {
        userId: studentOne.id,
        copyId: copyMap.get("INV-ECO-003")!,
        libraryBranchId: economicBranch.id,
        issuedBy: librarianEconomic.id,
        loanedAt: daysAgo(12),
        dueDate: daysAgo(2),
        returnedAt: daysAgo(1),
        status: LoanStatus.RETURNED,
        notes: "Returned shortly before due date",
      },
    ],
  });

  await prisma.reservation.createMany({
    data: [
      {
        userId: studentOne.id,
        bookId: books.get("9786010001003")!,
        libraryBranchId: technologicalBranch.id,
        status: ReservationStatus.PENDING,
        reservedAt: daysAgo(2),
        expiresAt: daysFromNow(8),
        notes: "Pending technical catalog request",
      },
      {
        userId: studentTwo.id,
        bookId: books.get("9786010001001")!,
        copyId: copyMap.get("INV-ECO-001")!,
        libraryBranchId: economicBranch.id,
        status: ReservationStatus.READY,
        reservedAt: daysAgo(4),
        expiresAt: daysFromNow(2),
        processedAt: daysAgo(1),
        processedByUserId: librarianEconomic.id,
        notes: "Ready for pickup",
      },
      {
        userId: teacherOne.id,
        bookId: books.get("9786010001007")!,
        libraryBranchId: economicBranch.id,
        status: ReservationStatus.FULFILLED,
        reservedAt: daysAgo(17),
        processedAt: daysAgo(13),
        processedByUserId: librarianEconomic.id,
        notes: "Fulfilled after processing",
      },
      {
        userId: studentTwo.id,
        bookId: books.get("9786010001005")!,
        libraryBranchId: collegeBranch.id,
        status: ReservationStatus.CANCELLED,
        reservedAt: daysAgo(14),
        processedAt: daysAgo(12),
        processedByUserId: librarianCollege.id,
        notes: "Cancelled by user request",
      },
      {
        userId: studentOne.id,
        bookId: books.get("9786010001006")!,
        libraryBranchId: collegeBranch.id,
        status: ReservationStatus.EXPIRED,
        reservedAt: daysAgo(31),
        expiresAt: daysAgo(1),
        processedAt: daysAgo(1),
        processedByUserId: librarianCollege.id,
        notes: "Expired pickup window",
      },
      {
        userId: teacherOne.id,
        bookId: books.get("9786010001008")!,
        libraryBranchId: technologicalBranch.id,
        status: ReservationStatus.PENDING,
        reservedAt: daysAgo(1),
        expiresAt: daysFromNow(10),
        notes: "New request for next service cycle",
      },
    ],
  });

  await prisma.bookCopy.update({
    where: { id: copyMap.get("INV-ECO-001")! },
    data: { status: CopyStatus.RESERVED },
  });

  await prisma.bookCopy.updateMany({
    where: {
      id: { in: [copyMap.get("INV-ECO-002")!, copyMap.get("INV-COL-001")!] },
    },
    data: { status: CopyStatus.LOANED },
  });

  await prisma.bookCopy.update({
    where: { id: copyMap.get("INV-TECH-002")! },
    data: { status: CopyStatus.LOST },
  });

  await prisma.systemSetting.upsert({
    where: { key: "loan.defaultDurationDays" },
    update: {
      value: 14,
      description: "Default loan period in days",
      updatedBy: admin.id,
    },
    create: {
      key: "loan.defaultDurationDays",
      value: 14,
      description: "Default loan period in days",
      updatedBy: admin.id,
    },
  });

  await prisma.systemSetting.upsert({
    where: { key: "demo.dataset.version" },
    update: {
      value: "2026.03.demo-hardening",
      description: "Current showcase data marker",
      updatedBy: admin.id,
    },
    create: {
      key: "demo.dataset.version",
      value: "2026.03.demo-hardening",
      description: "Current showcase data marker",
      updatedBy: admin.id,
    },
  });

  const [bookCount, reservationCount, loanCount] = await Promise.all([
    prisma.book.count(),
    prisma.reservation.count(),
    prisma.loan.count(),
  ]);

  console.log("Seed complete:", {
    admin: admin.universityId,
    analyst: analyst.universityId,
    librarians: [
      librarianEconomic.universityId,
      librarianTechnological.universityId,
      librarianCollege.universityId,
    ],
    readers: [
      studentOne.universityId,
      studentTwo.universityId,
      teacherOne.universityId,
    ],
    totals: {
      books: bookCount,
      reservations: reservationCount,
      loans: loanCount,
    },
  });
}

main()
  .catch((error) => {
    console.error(error);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });

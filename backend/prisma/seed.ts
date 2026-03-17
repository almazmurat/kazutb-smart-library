import {
  PrismaClient,
  UserRole,
  CopyStatus,
  InstitutionScopeCode,
  LibraryBranchCode,
} from "@prisma/client";

const prisma = new PrismaClient();

async function main() {
  const universityScope = await prisma.institutionScope.upsert({
    where: { code: InstitutionScopeCode.UNIVERSITY },
    update: {},
    create: {
      code: InstitutionScopeCode.UNIVERSITY,
      name: "University",
    },
  });

  const collegeScope = await prisma.institutionScope.upsert({
    where: { code: InstitutionScopeCode.COLLEGE },
    update: {},
    create: {
      code: InstitutionScopeCode.COLLEGE,
      name: "College",
    },
  });

  const economicBranch = await prisma.libraryBranch.upsert({
    where: { code: LibraryBranchCode.ECONOMIC_LIBRARY },
    update: {},
    create: {
      code: LibraryBranchCode.ECONOMIC_LIBRARY,
      name: "Economic Library",
      scopeId: universityScope.id,
    },
  });

  await prisma.libraryBranch.upsert({
    where: { code: LibraryBranchCode.TECHNOLOGICAL_LIBRARY },
    update: {},
    create: {
      code: LibraryBranchCode.TECHNOLOGICAL_LIBRARY,
      name: "Technological Library",
      scopeId: universityScope.id,
    },
  });

  await prisma.libraryBranch.upsert({
    where: { code: LibraryBranchCode.COLLEGE_LIBRARY },
    update: {},
    create: {
      code: LibraryBranchCode.COLLEGE_LIBRARY,
      name: "College Library",
      scopeId: collegeScope.id,
    },
  });

  const admin = await prisma.user.upsert({
    where: { universityId: "admin" },
    update: {},
    create: {
      universityId: "admin",
      email: "admin@kazutb.edu.kz",
      fullName: "System Administrator",
      role: UserRole.ADMIN,
      institutionScopeId: universityScope.id,
    },
  });

  const librarian = await prisma.user.upsert({
    where: { universityId: "librarian1" },
    update: {},
    create: {
      universityId: "librarian1",
      email: "librarian@kazutb.edu.kz",
      fullName: "Main Librarian",
      role: UserRole.LIBRARIAN,
      institutionScopeId: universityScope.id,
      libraryBranchId: economicBranch.id,
    },
  });

  const student = await prisma.user.upsert({
    where: { universityId: "student1" },
    update: {},
    create: {
      universityId: "student1",
      email: "student@kazutb.edu.kz",
      fullName: "Test Student",
      role: UserRole.STUDENT,
      institutionScopeId: universityScope.id,
    },
  });

  const publisher = await prisma.publisher.upsert({
    where: { name: "KazUTB Press" },
    update: {},
    create: {
      name: "KazUTB Press",
      city: "Astana",
      country: "Kazakhstan",
    },
  });

  const book = await prisma.book.create({
    data: {
      title: "Introduction to Digital Libraries",
      publishYear: 2024,
      language: "en",
      description:
        "Foundational concepts for modern digital library platforms.",
      keywords: ["library", "digital", "catalog", "metadata"],
      publisherId: publisher.id,
      libraryBranchId: economicBranch.id,
    },
  });

  await prisma.bookCopy.create({
    data: {
      bookId: book.id,
      inventoryNumber: "INV-000001",
      fund: "Main",
      status: CopyStatus.AVAILABLE,
      libraryBranchId: economicBranch.id,
    },
  });

  await prisma.systemSetting.upsert({
    where: { key: "loan.defaultDurationDays" },
    update: { value: 14, description: "Default loan period in days" },
    create: {
      key: "loan.defaultDurationDays",
      value: 14,
      description: "Default loan period in days",
      updatedBy: admin.id,
    },
  });

  console.log("Seed complete:", {
    admin: admin.universityId,
    librarian: librarian.universityId,
    student: student.universityId,
    book: book.title,
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

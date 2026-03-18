import { LoanStatus } from "@prisma/client";

export class LoanResponseDto {
  id!: string;
  status!: LoanStatus;
  loanedAt!: Date;
  dueDate!: Date;
  returnedAt?: Date;
  notes?: string;
  issuedBy?: string;
  userId!: string;
  copyId!: string;
  libraryBranchId!: string;
  createdAt!: Date;
  updatedAt!: Date;
  user?: { id: string; fullName: string; universityId: string; email: string };
  copy?: {
    id: string;
    inventoryNumber: string;
    book: { id: string; title: string };
  };
  libraryBranch?: { id: string; name: string };
}

export class ListLoansResponseDto {
  data!: LoanResponseDto[];
  meta!: {
    total: number;
    page: number;
    limit: number;
    totalPages: number;
  };
}

import axios from "axios";

const API_BASE = "/api/v1";

export type LoanStatus = "ACTIVE" | "RETURNED" | "OVERDUE" | "LOST";

export interface Loan {
  id: string;
  status: LoanStatus;
  loanedAt: string;
  dueDate: string;
  returnedAt?: string;
  notes?: string;
  issuedBy?: string;
  userId: string;
  copyId: string;
  libraryBranchId: string;
  createdAt: string;
  updatedAt: string;
  user?: { id: string; fullName: string; universityId: string; email: string };
  copy?: {
    id: string;
    inventoryNumber: string;
    book: { id: string; title: string };
  };
  libraryBranch?: { id: string; name: string };
}

export interface ListLoansResponse {
  data: Loan[];
  meta: {
    total: number;
    page: number;
    limit: number;
    totalPages: number;
  };
}

export interface IssueLoanPayload {
  userId: string;
  copyId: string;
  dueDate?: string;
  notes?: string;
}

export async function issueLoan(payload: IssueLoanPayload): Promise<Loan> {
  const response = await axios.post(`${API_BASE}/circulation/loans`, payload);
  return response.data;
}

export async function returnLoan(
  loanId: string,
  notes?: string,
): Promise<Loan> {
  const response = await axios.patch(
    `${API_BASE}/circulation/loans/${loanId}/return`,
    { notes },
  );
  return response.data;
}

export async function listLoans(
  status?: LoanStatus,
  page?: number,
  limit?: number,
  branchId?: string,
  overdueOnly?: boolean,
): Promise<ListLoansResponse> {
  const response = await axios.get(`${API_BASE}/circulation/loans`, {
    params: { status, page, limit, branchId, overdueOnly },
  });
  return response.data;
}

export async function getLoanById(loanId: string): Promise<Loan> {
  const response = await axios.get(`${API_BASE}/circulation/loans/${loanId}`);
  return response.data;
}

export async function getMyLoans(
  page?: number,
  limit?: number,
  status?: LoanStatus,
): Promise<ListLoansResponse> {
  const response = await axios.get(`${API_BASE}/circulation/my`, {
    params: { page, limit, status },
  });
  return response.data;
}

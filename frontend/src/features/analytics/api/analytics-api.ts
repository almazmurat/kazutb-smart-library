import axios from "axios";

const API_BASE = "/api/v1";

/* ── Dashboard ──────────────────────────────────────────── */

export interface BranchSummaryItem {
  id: string;
  name: string;
  code: string;
}

export interface DashboardData {
  totalBooks: number;
  totalCopies: number;
  activeReservations: number;
  activeLoans: number;
  overdueLoans: number;
  totalUsers: number;
  branches: BranchSummaryItem[];
  scope: "global" | "branch";
}

/* ── Popular books ──────────────────────────────────────── */

export interface PopularBook {
  id: string;
  title: string;
  publishYear: number | null;
  language: string | null;
  branch: { id: string; name: string } | null;
  authors: string[];
  loanCount: number;
  reservationCount: number;
  score: number;
}

export interface PopularBooksResponse {
  data: PopularBook[];
  rankingLogic: string;
}

/* ── Activity ───────────────────────────────────────────── */

export interface ActivityBucket {
  today: number;
  last7days: number;
  last30days: number;
}

export interface ActivityData {
  reservations: ActivityBucket;
  loans: ActivityBucket;
  returns: ActivityBucket;
  scope: "global" | "branch";
}

/* ── Reports ────────────────────────────────────────────── */

export interface MonthlyRow {
  month: number;
  loans: number;
  returns: number;
  reservations: number;
}

export interface BranchReportRow {
  branchId: string;
  branchName: string;
  loans: number;
  returns: number;
  reservations: number;
}

export interface ReportsOverview {
  year: number;
  scope: "global" | "branch";
  yearly: {
    loans: number;
    returns: number;
    reservations: number;
    currentOverdue: number;
  };
  monthly: MonthlyRow[];
  branchSummary: BranchReportRow[];
}

/* ── API functions ──────────────────────────────────────── */

export async function fetchDashboard(): Promise<DashboardData> {
  const { data } = await axios.get<DashboardData>(
    `${API_BASE}/analytics/dashboard`,
  );
  return data;
}

export async function fetchPopularBooks(
  limit = 10,
): Promise<PopularBooksResponse> {
  const { data } = await axios.get<PopularBooksResponse>(
    `${API_BASE}/analytics/popular-books`,
    { params: { limit } },
  );
  return data;
}

export async function fetchActivity(): Promise<ActivityData> {
  const { data } = await axios.get<ActivityData>(
    `${API_BASE}/analytics/activity`,
  );
  return data;
}

export async function fetchReportsOverview(
  year?: number,
): Promise<ReportsOverview> {
  const { data } = await axios.get<ReportsOverview>(
    `${API_BASE}/reports/overview`,
    { params: year ? { year } : {} },
  );
  return data;
}

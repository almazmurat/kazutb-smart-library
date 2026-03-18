import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import {
  issueLoan,
  returnLoan,
  listLoans,
  getMyLoans,
  type LoanStatus,
  type IssueLoanPayload,
} from "../api/circulation-api";

export function useLoans(
  status?: LoanStatus,
  page?: number,
  limit?: number,
  branchId?: string,
  overdueOnly?: boolean,
) {
  return useQuery({
    queryKey: ["loans", status, page, limit, branchId, overdueOnly],
    queryFn: () => listLoans(status, page, limit, branchId, overdueOnly),
  });
}

export function useMyLoans(
  page?: number,
  limit?: number,
  status?: LoanStatus,
) {
  return useQuery({
    queryKey: ["my-loans", page, limit, status],
    queryFn: () => getMyLoans(page, limit, status),
  });
}

export function useIssueLoan() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (payload: IssueLoanPayload) => issueLoan(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["loans"] });
    },
  });
}

export function useReturnLoan() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ loanId, notes }: { loanId: string; notes?: string }) =>
      returnLoan(loanId, notes),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["loans"] });
      queryClient.invalidateQueries({ queryKey: ["my-loans"] });
    },
  });
}

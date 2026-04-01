<?php

namespace App\Services\Library;

use App\Models\Library\CirculationLoan;
use Illuminate\Support\Carbon;

class CirculationLoanReadService
{
    /**
     * @return array<string, mixed>|null
     */
    public function findLoan(string $loanId): ?array
    {
        $loan = CirculationLoan::query()->find($loanId);

        return $loan ? $this->toArray($loan) : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findActiveLoanByCopy(string $copyId): ?array
    {
        $loan = CirculationLoan::query()
            ->where('copy_id', $copyId)
            ->where('status', 'active')
            ->whereNull('returned_at')
            ->orderByDesc('issued_at')
            ->first();

        return $loan ? $this->toArray($loan) : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function toArray(CirculationLoan $loan): array
    {
        $dueAt = $loan->due_at instanceof Carbon ? $loan->due_at : null;
        $returnedAt = $loan->returned_at instanceof Carbon ? $loan->returned_at : null;

        return [
            'id' => (string) $loan->id,
            'readerId' => (string) $loan->reader_id,
            'copyId' => (string) $loan->copy_id,
            'status' => (string) $loan->status,
            'issuedAt' => $loan->issued_at?->toAtomString(),
            'dueAt' => $dueAt?->toAtomString(),
            'returnedAt' => $returnedAt?->toAtomString(),
            'renewCount' => (int) $loan->renew_count,
            'isOverdue' => $returnedAt === null && $dueAt !== null && $dueAt->isPast(),
            'source' => 'app.circulation_loans',
        ];
    }
}

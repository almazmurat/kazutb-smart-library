import { ReservationStatus } from "@prisma/client";

export class ReservationResponseDto {
  id!: string;
  status!: ReservationStatus;
  reservedAt!: Date;
  expiresAt?: Date;
  processedAt?: Date;
  notes?: string;
  userId!: string;
  bookId!: string;
  libraryBranchId!: string;
  processedByUserId?: string;
  createdAt!: Date;
  updatedAt!: Date;
}

export class ListReservationsResponseDto {
  data!: ReservationResponseDto[];
  meta!: {
    total: number;
    page: number;
    limit: number;
    totalPages: number;
  };
}

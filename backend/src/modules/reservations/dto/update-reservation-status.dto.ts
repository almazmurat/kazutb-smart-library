import { IsEnum, IsOptional, IsString } from "class-validator";
import { ReservationStatus } from "@prisma/client";

export class UpdateReservationStatusDto {
  @IsEnum(ReservationStatus)
  status!: ReservationStatus;

  @IsOptional()
  @IsString()
  notes?: string;
}

import { IsUUID, IsOptional, IsString, IsDateString } from "class-validator";

export class CreateLoanDto {
  @IsUUID()
  userId!: string;

  @IsUUID()
  copyId!: string;

  @IsOptional()
  @IsDateString()
  dueDate?: string;

  @IsOptional()
  @IsString()
  notes?: string;
}

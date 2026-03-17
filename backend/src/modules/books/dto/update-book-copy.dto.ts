import { CopyStatus } from "@prisma/client";
import {
  IsDateString,
  IsEnum,
  IsOptional,
  IsString,
  IsUUID,
  MaxLength,
  MinLength,
} from "class-validator";

export class UpdateBookCopyDto {
  @IsOptional()
  @IsString()
  @MinLength(3)
  @MaxLength(64)
  inventoryNumber?: string;

  @IsOptional()
  @IsUUID()
  libraryBranchId?: string;

  @IsOptional()
  @IsEnum(CopyStatus)
  status?: CopyStatus;

  @IsOptional()
  @IsString()
  @MaxLength(128)
  fund?: string | null;

  @IsOptional()
  @IsString()
  @MaxLength(300)
  condition?: string | null;

  @IsOptional()
  @IsDateString()
  acquisitionDate?: string | null;
}

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

export class CreateBookCopyDto {
  @IsString()
  @MinLength(3)
  @MaxLength(64)
  inventoryNumber!: string;

  @IsUUID()
  libraryBranchId!: string;

  @IsOptional()
  @IsEnum(CopyStatus)
  status?: CopyStatus;

  @IsOptional()
  @IsString()
  @MaxLength(128)
  fund?: string;

  @IsOptional()
  @IsString()
  @MaxLength(300)
  condition?: string;

  @IsOptional()
  @IsDateString()
  acquisitionDate?: string;
}

import { Type } from "class-transformer";
import {
  IsArray,
  IsBoolean,
  IsInt,
  IsOptional,
  IsString,
  IsUUID,
  Max,
  MaxLength,
  Min,
  MinLength,
} from "class-validator";

export class UpdateBookDto {
  @IsOptional()
  @IsString()
  @MinLength(1)
  @MaxLength(500)
  title?: string;

  @IsOptional()
  @IsString()
  @MaxLength(500)
  subtitle?: string | null;

  @IsOptional()
  @IsString()
  @MaxLength(4000)
  description?: string | null;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(0)
  @Max(2100)
  publishYear?: number | null;

  @IsOptional()
  @IsString()
  @MaxLength(32)
  isbn?: string | null;

  @IsOptional()
  @IsString()
  @MaxLength(16)
  language?: string | null;

  @IsOptional()
  @IsUUID()
  libraryBranchId?: string;

  @IsOptional()
  @IsBoolean()
  isActive?: boolean;

  @IsOptional()
  @IsArray()
  @IsUUID(undefined, { each: true })
  authorIds?: string[];

  @IsOptional()
  @IsArray()
  @IsUUID(undefined, { each: true })
  categoryIds?: string[];
}

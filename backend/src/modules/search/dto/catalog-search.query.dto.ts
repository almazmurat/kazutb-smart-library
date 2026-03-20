import { Type } from "class-transformer";
import { IsEnum, IsInt, IsOptional, IsString, Max, Min } from "class-validator";
import { ApiPropertyOptional } from "@nestjs/swagger";

export enum CatalogAvailabilityFilter {
  ALL = "all",
  AVAILABLE = "available",
  UNAVAILABLE = "unavailable",
}

export class CatalogSearchQueryDto {
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  q?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  title?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  author?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  isbn?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  language?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  institutionUnitCode?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  campusCode?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  servicePointCode?: string;

  @ApiPropertyOptional({
    enum: CatalogAvailabilityFilter,
    default: CatalogAvailabilityFilter.ALL,
  })
  @IsOptional()
  @IsEnum(CatalogAvailabilityFilter)
  availability?: CatalogAvailabilityFilter = CatalogAvailabilityFilter.ALL;

  @ApiPropertyOptional({ minimum: 0 })
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(0)
  minCopies?: number;

  @ApiPropertyOptional({ default: 1, minimum: 1 })
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(1)
  page?: number = 1;

  @ApiPropertyOptional({ default: 20, minimum: 1, maximum: 100 })
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(1)
  @Max(100)
  limit?: number = 20;
}

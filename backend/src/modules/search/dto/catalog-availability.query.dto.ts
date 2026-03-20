import { ApiPropertyOptional } from "@nestjs/swagger";
import { IsOptional, IsString } from "class-validator";

export class CatalogAvailabilityQueryDto {
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
}

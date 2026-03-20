import { ApiProperty, ApiPropertyOptional } from "@nestjs/swagger";
import { IsIn, IsOptional, IsString, MinLength } from "class-validator";

export class AppReviewActionDto {
  @ApiProperty({
    enum: ["accept_suggestion", "reject_suggestion", "manual_edit"],
  })
  @IsString()
  @IsIn(["accept_suggestion", "reject_suggestion", "manual_edit"])
  action!: "accept_suggestion" | "reject_suggestion" | "manual_edit";

  @ApiPropertyOptional({ description: "Optional suggestion row id to target" })
  @IsOptional()
  @IsString()
  suggestionId?: string;

  @ApiPropertyOptional({
    description: "Field name for manual edit (e.g. title_display)",
  })
  @IsOptional()
  @IsString()
  fieldName?: string;

  @ApiPropertyOptional({
    description: "Manual value used for manual_edit action",
  })
  @IsOptional()
  @IsString()
  @MinLength(1)
  manualValue?: string;

  @ApiPropertyOptional({ description: "Optional librarian note" })
  @IsOptional()
  @IsString()
  note?: string;
}

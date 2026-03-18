import { IsUUID, IsString } from "class-validator";

export class CreateReservationDto {
  @IsUUID()
  bookId!: string;
}

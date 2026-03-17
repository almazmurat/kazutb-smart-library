import { Controller, Get } from '@nestjs/common';
import { ApiBearerAuth, ApiTags } from '@nestjs/swagger';

import { Roles } from '@common/decorators/roles.decorator';
import { UserRole } from '@common/types/user-role.enum';
import { ReservationsService } from './reservations.service';

@ApiTags('reservations')
@ApiBearerAuth()
@Controller({ path: 'reservations', version: '1' })
export class ReservationsController {
  constructor(private readonly service: ReservationsService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get()
  list() {
    return { data: this.service.list() };
  }
}
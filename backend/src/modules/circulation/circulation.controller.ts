import { Controller, Get } from '@nestjs/common';
import { ApiBearerAuth, ApiTags } from '@nestjs/swagger';

import { Roles } from '@common/decorators/roles.decorator';
import { UserRole } from '@common/types/user-role.enum';
import { CirculationService } from './circulation.service';

@ApiTags('circulation')
@ApiBearerAuth()
@Controller({ path: 'circulation', version: '1' })
export class CirculationController {
  constructor(private readonly service: CirculationService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get()
  list() {
    return { data: this.service.list() };
  }
}
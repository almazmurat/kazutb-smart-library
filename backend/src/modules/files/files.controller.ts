import { Controller, Get } from '@nestjs/common';
import { ApiBearerAuth, ApiTags } from '@nestjs/swagger';

import { Roles } from '@common/decorators/roles.decorator';
import { UserRole } from '@common/types/user-role.enum';
import { FilesService } from './files.service';

@ApiTags('files')
@ApiBearerAuth()
@Controller({ path: 'files', version: '1' })
export class FilesController {
  constructor(private readonly service: FilesService) {}

  @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
  @Get()
  list() {
    return { data: this.service.list() };
  }
}
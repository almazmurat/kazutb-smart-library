import { Controller, Get } from '@nestjs/common';
import { ApiTags } from '@nestjs/swagger';

import { Public } from '@common/decorators/public.decorator';
import { AuthorsService } from './authors.service';

@ApiTags('authors')
@Controller({ path: 'authors', version: '1' })
export class AuthorsController {
  constructor(private readonly service: AuthorsService) {}

  @Public()
  @Get()
  list() {
    return { data: this.service.list() };
  }
}
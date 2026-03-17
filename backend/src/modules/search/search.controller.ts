import { Controller, Get } from '@nestjs/common';
import { ApiTags } from '@nestjs/swagger';

import { Public } from '@common/decorators/public.decorator';
import { SearchService } from './search.service';

@ApiTags('search')
@Controller({ path: 'search', version: '1' })
export class SearchController {
  constructor(private readonly service: SearchService) {}

  @Public()
  @Get()
  list() {
    return { data: this.service.list() };
  }
}
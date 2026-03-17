import { Controller, Get } from '@nestjs/common';
import { ApiTags } from '@nestjs/swagger';

import { Public } from '@common/decorators/public.decorator';
import { BooksService } from './books.service';

@ApiTags('books')
@Controller({ path: 'books', version: '1' })
export class BooksController {
  constructor(private readonly service: BooksService) {}

  @Public()
  @Get()
  list() {
    return { data: this.service.list() };
  }
}
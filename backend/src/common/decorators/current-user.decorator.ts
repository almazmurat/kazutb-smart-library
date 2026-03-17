import { createParamDecorator, ExecutionContext } from '@nestjs/common';

/**
 * Extracts the authenticated user object from the request.
 * Populated by JwtAuthGuard after successful token verification.
 *
 * @example
 * @Get('profile')
 * getProfile(@CurrentUser() user: RequestUser) { ... }
 */
export const CurrentUser = createParamDecorator(
  (data: unknown, ctx: ExecutionContext) => {
    const request = ctx.switchToHttp().getRequest();
    return request.user;
  },
);

import { SetMetadata } from '@nestjs/common';
import { UserRole } from '../types/user-role.enum';

export const ROLES_KEY = 'roles';

/**
 * Decorator to specify which roles are allowed to access a route.
 * Used together with RolesGuard.
 *
 * @example
 * @Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
 * @Post()
 * createBook() { ... }
 */
export const Roles = (...roles: UserRole[]) => SetMetadata(ROLES_KEY, roles);

import { UserRole } from '../types/user-role.enum';

/**
 * Shape of the user object attached to the request after JWT verification.
 * Populated by the JWT strategy's validate() method.
 */
export interface RequestUser {
  id: string;
  universityId: string;
  email: string;
  fullName: string;
  role: UserRole;
}

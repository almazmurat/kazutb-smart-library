/**
 * System user roles.
 * GUEST is the implicit role for unauthenticated users.
 * Authenticated users are auto-provisioned with STUDENT on first LDAP login.
 * Admins can promote users to other roles via the admin panel.
 */
export enum UserRole {
  GUEST = 'GUEST',
  STUDENT = 'STUDENT',
  TEACHER = 'TEACHER',
  LIBRARIAN = 'LIBRARIAN',
  ADMIN = 'ADMIN',
  ANALYST = 'ANALYST',
}

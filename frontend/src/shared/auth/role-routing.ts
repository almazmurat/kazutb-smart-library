import type { Role } from "@shared/types/role";

export function getLandingRouteByRole(role: Role): string {
  if (role === "ADMIN") {
    return "/admin";
  }

  if (role === "LIBRARIAN") {
    return "/librarian";
  }

  if (role === "ANALYST") {
    return "/analytics";
  }

  if (role === "STUDENT" || role === "TEACHER") {
    return "/cabinet";
  }

  return "/overview";
}

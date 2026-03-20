import { Role } from "@shared/types/role";

export interface AuthState {
  isAuthenticated: boolean;
  role: Role;
}

// TODO: Replace with real auth state from JWT profile endpoint.
export const authStore: AuthState = {
  isAuthenticated: true,
  role: "LIBRARIAN",
};

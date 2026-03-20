import { Navigate } from "react-router-dom";

import { useAuthState } from "./auth-store";
import { Role } from "@shared/types/role";

interface ProtectedRouteProps {
  children: JSX.Element;
  roles?: Role[];
}

export function ProtectedRoute({ children, roles }: ProtectedRouteProps) {
  const auth = useAuthState();

  if (!auth.isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  if (roles && !roles.includes(auth.role)) {
    return <Navigate to="/catalog" replace />;
  }

  return children;
}

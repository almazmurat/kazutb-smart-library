import { Navigate } from 'react-router-dom';

import { authStore } from './auth-store';
import { Role } from '@shared/types/role';

interface ProtectedRouteProps {
  children: JSX.Element;
  roles?: Role[];
}

export function ProtectedRoute({ children, roles }: ProtectedRouteProps) {
  if (!authStore.isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  if (roles && !roles.includes(authStore.role)) {
    return <Navigate to="/catalog" replace />;
  }

  return children;
}

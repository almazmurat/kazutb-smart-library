import { createBrowserRouter, Navigate } from 'react-router-dom';

import { AppShell } from './shell';
import { CatalogPage } from '@features/catalog/pages/catalog-page';
import { SearchPage } from '@features/search/pages/search-page';
import { BookDetailsPage } from '@features/book/pages/book-details-page';
import { LoginPage } from '@features/auth/pages/login-page';
import { CabinetPage } from '@features/cabinet/pages/cabinet-page';
import { LibrarianPage } from '@features/librarian/pages/librarian-page';
import { AdminPage } from '@features/admin/pages/admin-page';
import { AnalyticsPage } from '@features/analytics/pages/analytics-page';
import { ReportsPage } from '@features/reports/pages/reports-page';
import { ProtectedRoute } from '@shared/auth/protected-route';

export const router = createBrowserRouter([
  {
    path: '/',
    element: <AppShell />,
    children: [
      { index: true, element: <Navigate to="/catalog" replace /> },
      { path: '/catalog', element: <CatalogPage /> },
      { path: '/search', element: <SearchPage /> },
      { path: '/books/:id', element: <BookDetailsPage /> },
      { path: '/login', element: <LoginPage /> },
      {
        path: '/cabinet',
        element: (
          <ProtectedRoute>
            <CabinetPage />
          </ProtectedRoute>
        ),
      },
      {
        path: '/librarian',
        element: (
          <ProtectedRoute roles={['LIBRARIAN', 'ADMIN']}>
            <LibrarianPage />
          </ProtectedRoute>
        ),
      },
      {
        path: '/admin',
        element: (
          <ProtectedRoute roles={['ADMIN']}>
            <AdminPage />
          </ProtectedRoute>
        ),
      },
      {
        path: '/analytics',
        element: (
          <ProtectedRoute roles={['LIBRARIAN', 'ANALYST', 'ADMIN']}>
            <AnalyticsPage />
          </ProtectedRoute>
        ),
      },
      {
        path: '/reports',
        element: (
          <ProtectedRoute roles={['LIBRARIAN', 'ANALYST', 'ADMIN']}>
            <ReportsPage />
          </ProtectedRoute>
        ),
      },
    ],
  },
]);

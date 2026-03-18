import { createBrowserRouter, Navigate } from "react-router-dom";

import { AppShell } from "./shell";
import { CatalogPage } from "@features/catalog/pages/catalog-page";
import { SearchPage } from "@features/search/pages/search-page";
import { BookDetailsPage } from "@features/book/pages/book-details-page";
import { LoginPage } from "@features/auth/pages/login-page";
import { CabinetPage } from "@features/cabinet/pages/cabinet-page";
import { LibrarianPage } from "@features/librarian/pages/librarian-page";
import { CirculationPage } from "@features/circulation/pages/circulation-page";
import { AdminPage } from "@features/admin/pages/admin-page";
import { AnalyticsPage } from "@features/analytics/pages/analytics-page";
import { ReportsPage } from "@features/reports/pages/reports-page";
import { ProtectedRoute } from "@shared/auth/protected-route";
import { AuthorsManagementPage } from "@features/catalog-management/pages/authors-management-page";
import { CategoriesManagementPage } from "@features/catalog-management/pages/categories-management-page";
import { BooksManagementPage } from "@features/catalog-management/pages/books-management-page";
import { BookCopiesManagementPage } from "@features/catalog-management/pages/book-copies-management-page";

export const router = createBrowserRouter([
  {
    path: "/",
    element: <AppShell />,
    children: [
      { index: true, element: <Navigate to="/catalog" replace /> },
      { path: "/catalog", element: <CatalogPage /> },
      { path: "/search", element: <SearchPage /> },
      { path: "/books/:id", element: <BookDetailsPage /> },
      { path: "/login", element: <LoginPage /> },
      {
        path: "/cabinet",
        element: (
          <ProtectedRoute>
            <CabinetPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/cabinet/reservations",
        element: (
          <ProtectedRoute>
            <CabinetPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/librarian",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ADMIN"]}>
            <LibrarianPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/librarian/reservations",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ADMIN"]}>
            <LibrarianPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/librarian/circulation",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ADMIN"]}>
            <CirculationPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/admin",
        element: (
          <ProtectedRoute roles={["ADMIN"]}>
            <AdminPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/analytics",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ANALYST", "ADMIN"]}>
            <AnalyticsPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/reports",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ANALYST", "ADMIN"]}>
            <ReportsPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/librarian/catalog/authors",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ADMIN"]}>
            <AuthorsManagementPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/librarian/catalog/categories",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ADMIN"]}>
            <CategoriesManagementPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/librarian/catalog/books",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ADMIN"]}>
            <BooksManagementPage />
          </ProtectedRoute>
        ),
      },
      {
        path: "/librarian/catalog/copies",
        element: (
          <ProtectedRoute roles={["LIBRARIAN", "ADMIN"]}>
            <BookCopiesManagementPage />
          </ProtectedRoute>
        ),
      },
    ],
  },
]);

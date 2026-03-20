import { useMemo, useState } from "react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";

import { apiClient } from "@shared/api/client";

type AdminUser = {
  id: string;
  universityId: string;
  fullName: string;
  email: string;
  role: string;
  isActive: boolean;
  libraryBranch?: { name: string } | null;
};

type UsersResponse = {
  data: AdminUser[];
  meta: {
    total: number;
    page: number;
    limit: number;
    totalPages: number;
  };
};

type RolesResponse = {
  data: string[];
};

export function AdminPage() {
  const queryClient = useQueryClient();
  const [search, setSearch] = useState("");
  const [roleFilter, setRoleFilter] = useState("");
  const [statusMessage, setStatusMessage] = useState<string | null>(null);

  const usersQuery = useQuery({
    queryKey: ["admin-users", search, roleFilter],
    queryFn: async () => {
      const { data } = await apiClient.get<UsersResponse>("/users", {
        params: {
          search: search || undefined,
          role: roleFilter || undefined,
          page: 1,
          limit: 50,
        },
      });
      return data;
    },
  });

  const rolesQuery = useQuery({
    queryKey: ["admin-roles"],
    queryFn: async () => {
      const { data } = await apiClient.get<RolesResponse>("/roles");
      return data.data;
    },
    staleTime: 60_000,
  });

  const updateRoleMutation = useMutation({
    mutationFn: async ({ userId, role }: { userId: string; role: string }) => {
      await apiClient.patch(`/users/${userId}/role`, { role });
    },
    onSuccess: () => {
      setStatusMessage("Роль пользователя обновлена.");
      queryClient.invalidateQueries({ queryKey: ["admin-users"] });
    },
    onError: (error: any) => {
      setStatusMessage(
        error?.response?.data?.message ||
          "Не удалось обновить роль пользователя.",
      );
    },
  });

  const users = usersQuery.data?.data ?? [];
  const roles = rolesQuery.data ?? [
    "STUDENT",
    "TEACHER",
    "LIBRARIAN",
    "ANALYST",
    "ADMIN",
  ];

  const activeCount = useMemo(
    () => users.filter((item) => item.isActive).length,
    [users],
  );

  return (
    <section className="app-page">
      <article className="app-panel-strong p-6 md:p-7">
        <p className="app-kicker">Администрирование</p>
        <h1 className="mt-2 text-2xl font-semibold text-slate-900">
          Панель администратора
        </h1>
        <p className="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
          Управление пользователями, ролями и доступом к операционным разделам.
        </p>
      </article>

      <article className="app-panel p-6">
        <h2 className="app-section-heading">Пользователи</h2>

        <div className="mt-4 grid gap-4 md:grid-cols-3">
          <div className="app-stat-card">
            <p className="app-kicker">Всего</p>
            <p className="mt-2 text-2xl font-semibold text-slate-900">
              {usersQuery.data?.meta.total ?? 0}
            </p>
          </div>
          <div className="app-stat-card">
            <p className="app-kicker">Активные</p>
            <p className="mt-2 text-2xl font-semibold text-slate-900">
              {activeCount}
            </p>
          </div>
          <div className="app-stat-card">
            <p className="app-kicker">Роли</p>
            <p className="mt-2 text-2xl font-semibold text-slate-900">
              {roles.length}
            </p>
          </div>
        </div>

        <div className="mt-5 grid gap-3 md:grid-cols-[1fr_220px]">
          <input
            className="app-form-control"
            value={search}
            onChange={(event) => setSearch(event.target.value)}
            placeholder="Поиск по ФИО, email или universityId"
          />
          <select
            className="app-form-control"
            value={roleFilter}
            onChange={(event) => setRoleFilter(event.target.value)}
          >
            <option value="">Все роли</option>
            {roles.map((role) => (
              <option key={role} value={role}>
                {role}
              </option>
            ))}
          </select>
        </div>

        {statusMessage ? (
          <div className="app-subpanel mt-4 p-4 text-sm text-slate-700">
            {statusMessage}
          </div>
        ) : null}

        {usersQuery.isLoading ? (
          <div className="app-empty-state mt-4">Загрузка пользователей...</div>
        ) : null}

        {usersQuery.isError ? (
          <div className="app-subpanel mt-4 p-4 text-sm text-slate-700">
            Не удалось загрузить пользователей. Раздел работает в безопасном
            режиме.
          </div>
        ) : null}

        {!usersQuery.isLoading && !usersQuery.isError ? (
          users.length === 0 ? (
            <div className="app-empty-state mt-4">Пользователи не найдены.</div>
          ) : (
            <div className="app-table-shell mt-4">
              <table className="w-full text-sm">
                <thead className="app-table-head">
                  <tr className="border-b border-blue-100/70">
                    <th className="px-4 py-3 text-left font-medium text-slate-700">
                      Пользователь
                    </th>
                    <th className="px-4 py-3 text-left font-medium text-slate-700">
                      ID
                    </th>
                    <th className="px-4 py-3 text-left font-medium text-slate-700">
                      Филиал
                    </th>
                    <th className="px-4 py-3 text-left font-medium text-slate-700">
                      Роль
                    </th>
                    <th className="px-4 py-3 text-left font-medium text-slate-700">
                      Статус
                    </th>
                  </tr>
                </thead>
                <tbody>
                  {users.map((user) => (
                    <tr
                      key={user.id}
                      className="border-b border-slate-100/90 hover:bg-slate-50/70"
                    >
                      <td className="px-4 py-4">
                        <div className="font-medium text-slate-900">
                          {user.fullName}
                        </div>
                        <div className="text-xs text-slate-500">
                          {user.email}
                        </div>
                      </td>
                      <td className="px-4 py-4 text-slate-600">
                        {user.universityId}
                      </td>
                      <td className="px-4 py-4 text-slate-600">
                        {user.libraryBranch?.name || "—"}
                      </td>
                      <td className="px-4 py-4">
                        <select
                          className="app-form-control"
                          value={user.role}
                          onChange={(event) =>
                            updateRoleMutation.mutate({
                              userId: user.id,
                              role: event.target.value,
                            })
                          }
                          disabled={updateRoleMutation.isPending}
                        >
                          {roles.map((role) => (
                            <option key={role} value={role}>
                              {role}
                            </option>
                          ))}
                        </select>
                      </td>
                      <td className="px-4 py-4 text-slate-600">
                        {user.isActive ? "Активен" : "Отключен"}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )
        ) : null}
      </article>

      <article className="app-panel p-6">
        <h2 className="app-section-heading">Роли и права (минимум)</h2>
        <div className="mt-4 grid gap-3 md:grid-cols-2">
          {roles.map((role) => (
            <div key={role} className="app-stat-card">
              <p className="app-kicker">Роль</p>
              <p className="mt-2 text-base font-semibold text-slate-900">
                {role}
              </p>
              <p className="mt-2 text-sm text-slate-600">
                Управление доступом через защищенные маршруты и серверные роли.
              </p>
            </div>
          ))}
        </div>
      </article>
    </section>
  );
}

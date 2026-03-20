import { useEffect } from "react";
import { useMutation, useQuery } from "@tanstack/react-query";

import { authStore } from "@shared/auth/auth-store";
import {
  fetchDemoUsers,
  fetchProfile,
  login,
  logout,
  type LoginPayload,
} from "../api/auth-api";

export function useDemoUsers() {
  return useQuery({
    queryKey: ["auth", "demo-users"],
    queryFn: fetchDemoUsers,
    staleTime: 60_000,
  });
}

export function useLogin() {
  return useMutation({
    mutationFn: (payload: LoginPayload) => login(payload),
    onSuccess: (session) => {
      authStore.setSession(session);
    },
  });
}

export function useLogout() {
  return useMutation({
    mutationFn: logout,
    onSettled: () => {
      authStore.clearSession();
    },
  });
}

export function useAuthProfile(enabled: boolean) {
  const query = useQuery({
    queryKey: ["auth", "profile", authStore.user?.id],
    queryFn: fetchProfile,
    enabled,
    staleTime: 30_000,
    retry: 0,
  });

  useEffect(() => {
    if (query.data) {
      authStore.patchUser(query.data);
    }
  }, [query.data]);

  useEffect(() => {
    if (query.isError) {
      authStore.clearSession();
    }
  }, [query.isError]);

  return query;
}

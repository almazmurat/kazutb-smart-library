import { apiClient } from "@shared/api/client";
import type { Role } from "@shared/types/role";

export interface LoginPayload {
  username: string;
  password: string;
}

export interface AuthUser {
  id: string;
  universityId: string;
  email: string;
  fullName: string;
  role: Role;
}

export interface LoginResponse {
  accessToken: string;
  refreshToken: string;
  user: AuthUser;
}

export interface DemoUser {
  username: string;
  password: string;
  role: Role;
  fullName: string;
}

export async function login(payload: LoginPayload): Promise<LoginResponse> {
  const { data } = await apiClient.post<LoginResponse>("/auth/login", payload);
  return data;
}

export async function fetchDemoUsers(): Promise<DemoUser[]> {
  const { data } = await apiClient.get<{ data: DemoUser[] }>(
    "/auth/demo-users",
  );
  return data.data;
}

export async function fetchProfile(): Promise<AuthUser> {
  const { data } = await apiClient.get<{ data: AuthUser }>("/auth/profile");
  return data.data;
}

export async function logout(): Promise<void> {
  await apiClient.post("/auth/logout");
}

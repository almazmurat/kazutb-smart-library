import { useSyncExternalStore } from "react";

import { Role } from "@shared/types/role";

const ACCESS_TOKEN_KEY = "kazutb_demo_access_token";
const REFRESH_TOKEN_KEY = "kazutb_demo_refresh_token";
const AUTH_USER_KEY = "kazutb_demo_user";

export interface AuthUser {
  id: string;
  universityId: string;
  email: string;
  fullName: string;
  role: Role;
}

export interface AuthSnapshot {
  isAuthenticated: boolean;
  role: Role;
  user: AuthUser | null;
  accessToken: string | null;
  refreshToken: string | null;
}

type AuthSessionPayload = {
  accessToken: string;
  refreshToken: string;
  user: AuthUser;
};

function readStorageValue(key: string): string | null {
  if (typeof window === "undefined") {
    return null;
  }
  return window.localStorage.getItem(key);
}

function parseStoredUser(value: string | null): AuthUser | null {
  if (!value) {
    return null;
  }

  try {
    const parsed = JSON.parse(value) as Partial<AuthUser>;
    if (
      parsed &&
      typeof parsed.id === "string" &&
      typeof parsed.universityId === "string" &&
      typeof parsed.email === "string" &&
      typeof parsed.fullName === "string" &&
      typeof parsed.role === "string"
    ) {
      return {
        id: parsed.id,
        universityId: parsed.universityId,
        email: parsed.email,
        fullName: parsed.fullName,
        role: parsed.role as Role,
      };
    }
  } catch {
    return null;
  }

  return null;
}

function createInitialSnapshot(): AuthSnapshot {
  const accessToken = readStorageValue(ACCESS_TOKEN_KEY);
  const refreshToken = readStorageValue(REFRESH_TOKEN_KEY);
  const user = parseStoredUser(readStorageValue(AUTH_USER_KEY));

  if (accessToken && refreshToken && user) {
    return {
      isAuthenticated: true,
      role: user.role,
      user,
      accessToken,
      refreshToken,
    };
  }

  return {
    isAuthenticated: false,
    role: "GUEST",
    user: null,
    accessToken: null,
    refreshToken: null,
  };
}

let snapshot: AuthSnapshot = createInitialSnapshot();
const listeners = new Set<() => void>();

function notify() {
  listeners.forEach((listener) => listener());
}

function persistSnapshot(next: AuthSnapshot) {
  if (typeof window === "undefined") {
    return;
  }

  if (next.accessToken) {
    window.localStorage.setItem(ACCESS_TOKEN_KEY, next.accessToken);
  } else {
    window.localStorage.removeItem(ACCESS_TOKEN_KEY);
  }

  if (next.refreshToken) {
    window.localStorage.setItem(REFRESH_TOKEN_KEY, next.refreshToken);
  } else {
    window.localStorage.removeItem(REFRESH_TOKEN_KEY);
  }

  if (next.user) {
    window.localStorage.setItem(AUTH_USER_KEY, JSON.stringify(next.user));
  } else {
    window.localStorage.removeItem(AUTH_USER_KEY);
  }
}

function setSnapshot(next: AuthSnapshot) {
  snapshot = next;
  persistSnapshot(next);
  notify();
}

export const authStore = {
  get isAuthenticated() {
    return snapshot.isAuthenticated;
  },
  get role() {
    return snapshot.role;
  },
  get user() {
    return snapshot.user;
  },
  get accessToken() {
    return snapshot.accessToken;
  },
  get refreshToken() {
    return snapshot.refreshToken;
  },
  subscribe(listener: () => void) {
    listeners.add(listener);
    return () => listeners.delete(listener);
  },
  getSnapshot() {
    return snapshot;
  },
  setGuestMode() {
    setSnapshot({
      isAuthenticated: false,
      role: "GUEST",
      user: null,
      accessToken: null,
      refreshToken: null,
    });
  },
  setSession(payload: AuthSessionPayload) {
    setSnapshot({
      isAuthenticated: true,
      role: payload.user.role,
      user: payload.user,
      accessToken: payload.accessToken,
      refreshToken: payload.refreshToken,
    });
  },
  patchUser(user: AuthUser) {
    setSnapshot({
      ...snapshot,
      isAuthenticated: true,
      role: user.role,
      user,
    });
  },
  clearSession() {
    this.setGuestMode();
  },
};

export function useAuthState(): AuthSnapshot {
  return useSyncExternalStore(
    authStore.subscribe,
    authStore.getSnapshot,
    authStore.getSnapshot,
  );
}

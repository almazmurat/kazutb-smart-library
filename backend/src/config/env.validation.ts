type EnvRecord = Record<string, unknown>;

function asString(value: unknown): string {
  return typeof value === "string" ? value : "";
}

function isTrue(value: unknown): boolean {
  return asString(value).toLowerCase() === "true";
}

export function validateEnv(config: EnvRecord): EnvRecord {
  const env = asString(config.NODE_ENV) || "development";
  const isProd = env === "production";

  const requiredAlways = ["DATABASE_URL", "JWT_SECRET"];
  const requiredInProd = [
    "LDAP_URL",
    "LDAP_BIND_DN",
    "LDAP_BIND_PASSWORD",
    "LDAP_BASE_DN",
  ];

  const missing: string[] = [];

  for (const key of requiredAlways) {
    if (!asString(config[key])) {
      missing.push(key);
    }
  }

  if (isProd) {
    for (const key of requiredInProd) {
      if (!asString(config[key])) {
        missing.push(key);
      }
    }

    if (isTrue(config.LDAP_DEV_MOCK)) {
      missing.push("LDAP_DEV_MOCK must be false in production");
    }
  }

  const jwtSecret = asString(config.JWT_SECRET);
  if (jwtSecret && jwtSecret.length < 32) {
    missing.push("JWT_SECRET must be at least 32 characters");
  }

  if (missing.length > 0) {
    throw new Error(`Environment validation failed: ${missing.join(", ")}`);
  }

  return config;
}

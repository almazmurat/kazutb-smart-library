import { validateEnv } from "./env.validation";

describe("validateEnv", () => {
  it("passes with required development variables", () => {
    const result = validateEnv({
      NODE_ENV: "development",
      DATABASE_URL: "postgresql://localhost:5432/test",
      JWT_SECRET: "abcdefghijklmnopqrstuvwxyz123456",
    });

    expect(result).toBeDefined();
  });

  it("fails when production has LDAP mock enabled", () => {
    expect(() =>
      validateEnv({
        NODE_ENV: "production",
        DATABASE_URL: "postgresql://localhost:5432/test",
        JWT_SECRET: "abcdefghijklmnopqrstuvwxyz123456",
        LDAP_URL: "ldaps://example",
        LDAP_BIND_DN: "cn=svc",
        LDAP_BIND_PASSWORD: "pass",
        LDAP_BASE_DN: "dc=example,dc=com",
        LDAP_DEV_MOCK: "true",
      }),
    ).toThrow();
  });
});

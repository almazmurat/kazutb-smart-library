# AUTH_MODEL
> Derived from [[PROJECT_CONTEXT]] §3

## Login flow
```
User opens the library login page
  → enters their credentials
  → library sends them to the configured CRM API
  → CRM validates against LDAP/AD
  → CRM returns a session token and user data
  → library stores the session securely
  → library loads current profile data
  → the user stays inside the library UX at all times
```

## Endpoint model
| Endpoint | Method | Purpose |
|---|---|---|
| `/api/login` | POST | Authenticate via AD |
| `/api/admin/login` | POST | Admin authentication |
| `/api/me` | GET | Get current user info |
| `/api/logout` | POST | Logout |

Host and token details must be read from secure config: **[REDACTED - see secure config]**.

## Token handling rules
- store the session token only in an httpOnly cookie or secure server session
- never use localStorage
- react gracefully to invalid or expired sessions
- never expose the raw token in client-side JavaScript

## User data used by the library
The library relies on CRM identity fields such as ID, display name, email, role, department, and AD login to establish role-aware access.

## Access rules
Only AD users can access protected features. Guests remain public and read-only.

## Links
- [[PROJECT_CONTEXT]]
- [[ROLES_AND_ACCESS]]
- [[API_STRATEGY]]

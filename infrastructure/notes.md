# Infrastructure Notes — DevOps Handoff

## System Overview

The KazUTB Smart Library runs as a **monolithic Node.js application** on a **university-owned on-premises server**. This document provides reference information for the DevOps team responsible for deployment and operations.

---

## Application Components

| Component    | Technology             | Default Port           | Notes                                             |
| ------------ | ---------------------- | ---------------------- | ------------------------------------------------- |
| Backend API  | NestJS (Node.js)       | 3000                   | REST API server                                   |
| Frontend     | React SPA (Vite build) | Served as static files | Served by reverse proxy or backend static serving |
| Database     | PostgreSQL 15          | 5432                   | Primary data store                                |
| File Storage | Local filesystem       | N/A                    | Protected, not web-accessible                     |
| LDAP         | University AD          | 636 (LDAPs)            | External dependency                               |

---

## Environment Variables

All required environment variables are documented in `.env.example` at the project root and in `backend/.env.example`.

**Critical variables that must be set in production:**

- `DATABASE_URL` — PostgreSQL connection string
- `JWT_SECRET` — Strong random secret (min 64 chars); generate fresh for production
- `LDAP_URL` — University LDAP server address (use `ldaps://` with port 636)
- `LDAP_BIND_DN` — Service account DN for LDAP binding
- `LDAP_BIND_PASSWORD` — Service account password
- `LDAP_BASE_DN` — LDAP search base
- `LDAP_DEV_MOCK` — Must be `false` in production
- `NODE_ENV` — Must be `production`

---

## Recommended Server Requirements

| Resource            | Minimum          | Recommended             |
| ------------------- | ---------------- | ----------------------- |
| CPU                 | 2 cores          | 4 cores                 |
| RAM                 | 4 GB             | 8 GB                    |
| Disk (OS + App)     | 20 GB            | 50 GB                   |
| Disk (File Storage) | 100 GB           | 500 GB (for PDFs/scans) |
| OS                  | Ubuntu 22.04 LTS | Ubuntu 22.04 LTS        |
| Node.js             | 20 LTS           | 20 LTS                  |
| PostgreSQL          | 15               | 15                      |

---

## Production Build

### Backend

```bash
cd backend
npm run build
# Produces: backend/dist/
```

### Frontend

```bash
cd frontend
npm run build
# Produces: frontend/dist/  (static files)
```

---

## Process Management

Recommended: **PM2** for Node.js process management.

```bash
npm install -g pm2

# Start the backend
pm2 start backend/dist/main.js --name kazutb-api

# Serve frontend static files (or use Nginx)
pm2 start serve --name kazutb-frontend -- -s frontend/dist -p 5000

# Save process list
pm2 save
pm2 startup
```

---

## Reverse Proxy (Nginx — recommended)

```nginx
server {
    listen 80;
    server_name library.university.edu;

    # Redirect to HTTPS
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    server_name library.university.edu;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    # Frontend SPA
    location / {
        root /var/www/kazutb-frontend/dist;
        try_files $uri $uri/ /index.html;
    }

    # Backend API
    location /api/ {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

---

## Database Setup

```bash
# Create database and user
sudo -u postgres psql -c "CREATE USER kazutb_user WITH PASSWORD 'strong_password_here';"
sudo -u postgres psql -c "CREATE DATABASE kazutb_library OWNER kazutb_user;"
sudo -u postgres psql -d kazutb_library -c "CREATE EXTENSION IF NOT EXISTS pg_trgm;"
sudo -u postgres psql -d kazutb_library -c "CREATE EXTENSION IF NOT EXISTS 'uuid-ossp';"

# Run Prisma migrations
cd backend
npx prisma migrate deploy
```

---

## Backups

### Database Backup (recommended: daily)

```bash
pg_dump -U kazutb_user -h localhost kazutb_library \
  | gzip > /backups/kazutb_db_$(date +%Y%m%d).sql.gz
```

### File Storage Backup

```bash
rsync -av /var/lib/kazutb/storage/ /backups/storage/
```

Retain at least **30 days** of database backups. Store backups on a separate volume or network share.

---

## Logging

Application logs are written to:

- `logs/app.log` — application-level logs
- PostgreSQL `audit_logs` table — business-level audit trail

Recommended: Configure log rotation with `logrotate`.

---

## Security Checklist

- [ ] `NODE_ENV=production` set
- [ ] `LDAP_DEV_MOCK=false` set
- [ ] Strong `JWT_SECRET` generated (not the example value)
- [ ] PostgreSQL user has minimal required permissions (no superuser)
- [ ] LDAP connection uses `ldaps://` (TLS), not `ldap://`
- [ ] File storage directory is NOT web-accessible directly
- [ ] Nginx configured with TLS certificate
- [ ] Firewall: only ports 80, 443 exposed externally; 3000, 5432 internal only
- [ ] Regular automated backups configured and tested

---

## Future Considerations

- **Containerization:** The application is containerization-ready. Docker Compose + Dockerfile can be added when needed.
- **Search scaling:** Replace PostgreSQL FTS with Elasticsearch/Meilisearch if query performance degrades at >200K records.
- **Caching:** Add Redis for caching frequently-read catalog data under high load.
- **Background jobs:** Add a job queue (Bull/BullMQ) for scheduled tasks (overdue notices, report generation) when needed.

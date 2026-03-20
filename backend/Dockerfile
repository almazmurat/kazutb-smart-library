# ─── Stage 1: deps ────────────────────────────────────────────
FROM node:20-slim AS deps
WORKDIR /app
COPY package*.json ./
RUN npm ci --omit=dev

# ─── Stage 2: builder ─────────────────────────────────────────
FROM node:20-slim AS builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
# Generate Prisma client for this build OS (needed for TypeScript compilation)
RUN npx prisma generate
RUN npm run build

# ─── Stage 3: runner ──────────────────────────────────────────
FROM node:20-slim AS runner
WORKDIR /app

RUN apt-get update -qq && apt-get install -y --no-install-recommends \
    dumb-init openssl ca-certificates curl \
    && rm -rf /var/lib/apt/lists/*

ENV NODE_ENV=production

# Copy compiled output and production node_modules
COPY --from=builder /app/dist ./dist
COPY --from=deps    /app/node_modules ./node_modules
# Copy Prisma schema and generate the client for this exact runtime environment
COPY --from=builder /app/prisma ./prisma
COPY package*.json ./
RUN npx prisma generate
COPY entrypoint.sh ./entrypoint.sh
RUN chmod +x entrypoint.sh

EXPOSE 3000

# Run migrations then start the server
ENTRYPOINT ["dumb-init", "--"]
CMD ["sh", "entrypoint.sh"]

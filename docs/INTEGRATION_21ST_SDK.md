# 21st SDK Integration

This project now includes a minimal 21st Agents SDK integration adapted for the current Laravel + Blade + Vite stack.

## What is official vs adapted

Official 21st docs show:
- React chat UI via `@21st-sdk/react`
- token exchange and sandbox/thread management via `@21st-sdk/node`
- Next.js convenience helpers for token routes

This repository is not a Next.js app, so the integration here uses a Laravel-friendly adaptation:
- frontend chat UI is mounted as a React island on a dedicated Blade page
- Laravel exposes protected internal endpoints for token exchange and sandbox/thread lifecycle
- Laravel calls a small local Node bridge script that uses `@21st-sdk/node`

This keeps `API_KEY_21ST` on the server and avoids exposing it to the browser.

## Files involved

- `agents/frontend-dev-agent/index.ts`
- `scripts/21st/bridge.mjs`
- `app/Services/Ai/TwentyFirstBridgeService.php`
- `app/Http/Controllers/Api/InternalAiAssistantController.php`
- `resources/js/ai-chat/*`
- `resources/views/internal-ai-chat.blade.php`

## Environment

Add these values to `.env`:

```env
API_KEY_21ST=
AGENT_21ST_SLUG=frontend-dev-agent
AGENT_21ST_TOKEN_EXPIRES_IN=1h
```

## Deploy the agent

```bash
npm run 21st:login
npm run 21st:deploy
```

After deployment, confirm that `AGENT_21ST_SLUG` matches the deployed agent slug.

## Internal UI route

The chat is available at:

- `/internal/ai-chat`

This route is intended for staff users only. The backend API routes are protected by the existing internal staff session middleware.

## Backend endpoints

- `POST /api/v1/internal/ai-assistant/token`
- `POST /api/v1/internal/ai-assistant/session`
- `POST /api/v1/internal/ai-assistant/thread`

These endpoints:
- never expose `API_KEY_21ST`
- create short-lived browser tokens
- create a sandbox + initial thread for the UI
- create additional threads inside the current sandbox

## Frontend lifecycle

The React chat island:
- boots on the dedicated Blade page only
- stores `sandboxId` and `threadId` in `localStorage`
- requests a new session from Laravel on first load
- requests a new thread when the user clicks `New thread`
- talks to the 21st relay using a short-lived token from Laravel

## Notes

- This integration is minimal and production-friendly for the current stack.
- It does not refactor the app into Inertia or SPA mode.
- It does not expose secrets to the client.
- It assumes the machine running Laravel can execute `node` for the bridge script.
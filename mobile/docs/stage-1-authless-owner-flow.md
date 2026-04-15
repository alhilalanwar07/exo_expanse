# Stage 1 - Public First Open Flow

This document locks the startup flow for mobile app so iOS release does not need auth UX changes later.

## Goal

- First open must show public/basic page first.
- Login/register appears only when user wants to choose theme or manage invitation.
- Owner session can still use device access code and remains revocable from web.

## Startup Flow

1. User opens mobile app.
2. App shows public/basic home (no forced login).
3. User explores public content (for example theme showcase preview).
4. When user chooses theme or wants to manage invitation, app opens Login/Register choice.
5. User proceeds to login/register route.
6. For owner access-code flow, user can continue to "Hubungkan Perangkat".
7. App exchanges access code to owner session token and opens owner dashboard.

## Deep Link Format

- Custom scheme: exoinvite://connect?code=EXO-AB12CD34
- Universal link (future): https://domain-app/connect?code=EXO-AB12CD34

## Session Model

- access_token: short TTL (15-60 minutes)
- refresh_token: long TTL (30 days), revocable
- device_alias: owner-defined device name (ex: iPhone Budi)
- last_used_at: telemetry for security panel in web

## Required API Contract (Stage 2)

### 1) Exchange access code
- POST /api/mobile/access/exchange
- Request:
  {
    "access_code": "EXO-AB12CD34",
    "device_alias": "iPhone Budi",
    "platform": "ios"
  }
- Response 200:
  {
    "session": {
      "workspace_id": "ws_01",
      "workspace_label": "Undangan Budi & Sari",
      "owner_name": "Budi",
      "access_token": "...",
      "refresh_token": "...",
      "expires_at": "2026-04-05T12:00:00Z"
    }
  }

### 2) Refresh token
- POST /api/mobile/access/refresh
- Request:
  {
    "refresh_token": "..."
  }
- Response 200: same shape as exchange response

### 3) Revoke current device session
- POST /api/mobile/access/revoke
- Header: Authorization: Bearer <access_token>
- Response 200:
  {
    "success": true
  }

### 4) List active owner devices (for security panel)
- GET /api/mobile/access/devices
- Header: Authorization: Bearer <access_token>
- Response 200:
  {
    "data": [
      {
        "id": "dev_01",
        "device_alias": "iPhone Budi",
        "platform": "ios",
        "last_used_at": "2026-04-04T11:22:00Z",
        "is_current": true
      }
    ]
  }

## Security Rules

- Access code is one-time and expires fast (recommended <= 10 minutes).
- Access code must be invalidated after successful exchange.
- Every mobile owner endpoint must require owner session token.
- Rate limit exchange endpoint to mitigate brute force.
- Keep an audit trail for device connect/revoke.

## Stage 1 Implementation Status

- Mobile startup flow updated to public first-open page.
- Login/register appears only after user chooses protected actions.
- URL scheme added in Expo config for deep link readiness.

## Stage 2 Implementation Status

- API endpoint exchange implemented.
- API endpoint refresh implemented.
- API endpoint revoke implemented.
- API endpoint devices implemented.
- Endpoint issue code for authenticated web owner implemented.

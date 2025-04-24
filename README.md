# Mood Tracker Backend

This is the Symfony‑based REST API for the Mood Tracker application. It provides user registration, login via JWT, and CRUD for mood entries.

---

## Table of Contents

- [Tech Stack](#tech-stack)  
- [Requirements](#requirements)  
- [Installation](#installation)  
- [Configuration](#configuration)  
- [Database & Migrations](#database--migrations)  
- [Running the Server](#running-the-server)  
- [API Endpoints](#api-endpoints)  
- [Authentication](#authentication)  
- [Testing Credentials](#testing-credentials)
---

## Tech Stack

- **Framework:** Symfony 7.2  
- **ORM:** Doctrine (SQLite)  
- **JWT:** firebase/php-jwt  
- **CORS:** NelmioCorsBundle  

---

## Requirements

- PHP 8.4+ with SQLite support  
- Composer  
- OpenSSL (for generating keys, if you ever switch from secret‑based JWT)  

---

## Installation

1. **Clone the repo**  
   ```bash
   git clone https://github.com/yazdan0101/mood-tracker-backend.git
   cd mood-tracker-backend
   ```

2. **Install dependencies**  
   ```bash
   composer install
   ```

3. **Set up environment**  
   Copy `.env` to `.env.local` and adjust if needed:
   ```dotenv
   APP_ENV=dev
   APP_SECRET=your_symfony_secret
   JWT_SECRET=your_jwt_secret_key  # same as APP_SECRET for HS256
   ```

---

## Database & Migrations

The project uses Doctrine Migrations with an SQLite database at `var/data.db`.

- **Create the initial schema**  
  ```bash
  php bin/console doctrine:migrations:migrate
  ```  
  (Alternatively, if starting fresh: `php bin/console doctrine:schema:create`)

- **Generate a new migration** after entity changes:  
  ```bash
  php bin/console make:migration
  ```

---

## Running the Server

Start Symfony’s built‑in server on all interfaces (so emulators and web can reach it):

```bash
php -S 0.0.0.0:8000 -t public public/index.php
```

CORS is enabled for all `/api/*` routes via NelmioCorsBundle.

---

## API Endpoints

### POST `/api/register`

Create a new user.

- **Body** (JSON):
  ```json
  {
    "username": "psuser",
    "password": "pspass"
  }
  ```
- **Response**:
  ```json
  { "status": "user_created" }
  ```

### POST `/api/login`

Authenticate and receive a JWT.

- **Body** (JSON):
  ```json
  {
    "username": "psuser",
    "password": "pspass"
  }
  ```
- **Response**:
  ```json
  { "token": "eyJ0eXAiOiJKV1QiLCJhbGci..." }
  ```

### POST `/api/mood-entries`

Create a mood entry (authenticated).

- **Headers**:  
  `Authorization: Bearer <token>`
- **Body** (JSON):
  ```json
  {
    "moodType": "happy",
    "occurredAt": "2025-04-25T12:34:56Z",
    "feelingList": ["calm","grateful"],
    "sleepQuality": "7 hours",
    "activityList": ["reading","jogging"],
    "bestAboutToday": "Sunshine",
    "note": "Felt great!"
  }
  ```
- **Response**:
  ```json
  { "status": "created" }
  ```

---

## Authentication

This API uses HS256‑signed JWTs. The token’s `sub` claim is the user ID. All protected routes require the `Authorization: Bearer <token>` header.

---

## Testing Credentials

- **Username:** `psuser`  
- **Password:** `pspass`




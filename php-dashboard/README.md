# PHP Firebase Dashboard

This lightweight PHP app reads data from a Firebase Realtime Database and renders a dashboard-style page.

## 1) Configure environment variables

```bash
export FIREBASE_DATABASE_URL="https://your-project-default-rtdb.firebaseio.com"
export FIREBASE_DATABASE_SECRET="your_database_secret_or_token" # optional for public DBs
```

## 2) Run locally

```bash
php -S 0.0.0.0:8080 -t php-dashboard
```

Then open `http://localhost:8080`.

## Notes
- You can change the path from the form (for example `/users`, `/batteries`).
- The app uses Firebase REST API (`.json`) endpoints.

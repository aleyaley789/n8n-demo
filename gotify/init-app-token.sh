#!/bin/sh
set -eu

apk add --no-cache sqlite >/dev/null

DB=/data/gotify.db
TOKEN="${GOTIFY_APP_TOKEN}"
NAME="Support Ticket Demo"

# Warten, bis Gotify die Datenbank inkl. Standard-Admin-User (id=1) angelegt hat
for i in $(seq 1 30); do
    if [ -f "$DB" ] && [ "$(sqlite3 "$DB" "SELECT count(*) FROM users WHERE id=1" 2>/dev/null || echo 0)" = "1" ]; then
        break
    fi
    sleep 1
done

EXISTING=$(sqlite3 "$DB" "SELECT count(*) FROM applications WHERE token='$TOKEN'")
if [ "$EXISTING" = "0" ]; then
    echo "==> Lege Gotify-Application mit festem Token an"
    sqlite3 "$DB" "INSERT INTO applications (token, user_id, name, description, internal, image, default_priority, sort_key) VALUES ('$TOKEN', 1, '$NAME', 'Automatisch beim Start des Stacks angelegt', 0, '', 0, X'6130');"
else
    echo "==> Gotify-Application mit festem Token existiert bereits"
fi

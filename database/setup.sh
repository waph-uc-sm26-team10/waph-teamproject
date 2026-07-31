#!/bin/bash
# Creates the database and application account from .env, imports the schema,
# and seeds the admin user plus a couple of sample posts.
#
# WARNING: this rebuilds the tables from scratch - existing accounts and posts
# in ${DB_NAME} are dropped. It is a development setup script, not a migration.

set -euo pipefail

# Load .env from the repo root
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
if [ ! -f "$REPO_ROOT/.env" ]; then
  echo "Missing $REPO_ROOT/.env — copy .env.example to .env and fill it in." >&2
  exit 1
fi
set -a
# shellcheck disable=SC1091
source "$REPO_ROOT/.env"
set +a

# Optional in .env; the users table needs a name and email for the admin row.
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@waph-team10.minifacebook.com}"
ADMIN_NAME="${ADMIN_NAME:-Team 10 Admin}"

# Create the database and the application account 
sudo mysql <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;
CREATE USER IF NOT EXISTS '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL ON \`${DB_NAME}\`.* TO '${DB_USER}'@'${DB_HOST}';
SQL

sudo mysql "${DB_NAME}" < "$REPO_ROOT/database/schema.sql"

sudo mysql "${DB_NAME}" <<SQL
INSERT INTO users(email, username, name, password)
VALUES ('${ADMIN_EMAIL}', '${ADMIN_USERNAME}', '${ADMIN_NAME}', md5('${ADMIN_PASSWORD}'));

SET @adminid = LAST_INSERT_ID();

INSERT INTO superusers(userid) VALUES (@adminid);

INSERT INTO posts(title, content, owner) VALUES
  ('Welcome to miniFacebook',
   'This is the first post on the Team 10 miniFacebook application.',
   @adminid),
  ('Sprint 1 progress',
   'Registration, login, password changes, and profile editing are in place.',
   @adminid);

INSERT INTO comments(postid, owner, content)
SELECT postid, @adminid, 'Great to see this up and running.'
FROM posts WHERE owner = @adminid ORDER BY postid LIMIT 1;
SQL

echo "Database '${DB_NAME}' set up successfully."
echo "  admin account: ${ADMIN_USERNAME}  (regular user + superuser)"
echo "Sign in at /Front-End/loginform.php; superusers also get /Admin/dashboard.php."

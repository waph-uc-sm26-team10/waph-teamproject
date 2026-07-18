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

# Set SQL to MySQL as root (prompts for the root password)
mysql -u root -p <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;
CREATE USER IF NOT EXISTS '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL ON \`${DB_NAME}\`.* TO '${DB_USER}'@'${DB_HOST}';
GRANT ALL ON \`${DB_NAME}\`.* TO 'root'@'localhost';

USE \`${DB_NAME}\`;

DROP TABLE IF EXISTS users;
CREATE TABLE users(
  username varchar(50) PRIMARY KEY,
  password varchar(100) NOT NULL
);
INSERT INTO users(username, password) VALUES ('${ADMIN_USERNAME}', md5('${ADMIN_PASSWORD}'));
SQL

echo "Database '${DB_NAME}' set up successfully."

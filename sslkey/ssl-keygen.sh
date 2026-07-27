#!/bin/bash
# Generates the self-signed TLS pair for the team site and installs it for Apache.
# Safe to re-run - it overwrites the local pair with a fresh one.
#
# The .crt is committed; the .key is git-ignored and must NEVER be committed.
# If a key is ever exposed, re-run this script to rotate the pair.

set -euo pipefail
cd "$(dirname "$0")"

DOMAIN="${DOMAIN:-waph-team10.minifacebook.com}"
DAYS="${DAYS:-365}"
SUBJ="/C=US/ST=Ohio/L=Cincinnati/O=University of Cincinnati/OU=waph/CN=${DOMAIN}"

# -subj and -addext keep this non-interactive; the SAN is required for the
# certificate to be accepted for ${DOMAIN} (CN alone is ignored by browsers).
openssl req -x509 -nodes -days "$DAYS" -newkey rsa:4096 \
    -keyout waph-team10.key \
    -out waph-team10.crt \
    -subj "$SUBJ" \
    -addext "subjectAltName=DNS:${DOMAIN},DNS:localhost,IP:127.0.0.1"

chmod 600 waph-team10.key
chmod 644 waph-team10.crt
echo "Generated a new pair for ${DOMAIN} (valid ${DAYS} days)."

# Install for Apache. Needs root, so this part prompts for your password.
sudo install -m 644 -o root -g root waph-team10.crt /etc/ssl/certs/waph-team10.crt
sudo install -m 600 -o root -g root waph-team10.key /etc/ssl/private/waph-team10.key
echo "Installed to /etc/ssl/certs and /etc/ssl/private."

if sudo apache2ctl configtest 2>/dev/null; then
    sudo systemctl reload apache2
    echo "Apache reloaded."
else
    echo "Apache config test failed or apache2 not installed - reload it yourself." >&2
fi

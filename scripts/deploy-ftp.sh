#!/usr/bin/env bash
#
# Déploiement manuel par FTP(S) depuis votre PC vers PlanetHoster N0C.
# Alternative au workflow GitHub Actions (utile pour un envoi ponctuel).
#
# Prérequis : lftp installé (Debian/Ubuntu : sudo apt install lftp ;
#             macOS : brew install lftp).
#
# Usage :
#   1. Copiez scripts/.env.example en scripts/.env et renseignez vos accès FTP N0C.
#   2. Lancez : bash scripts/deploy-ftp.sh
#
# scripts/.env n'est PAS versionné (voir .gitignore).

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="$ROOT/scripts/.env"

if [[ -f "$ENV_FILE" ]]; then
  # shellcheck disable=SC1090
  source "$ENV_FILE"
fi

: "${FTP_HOST:?Renseignez FTP_HOST (ex: nodeXXX-eu.n0c.com) dans scripts/.env}"
: "${FTP_USER:?Renseignez FTP_USER dans scripts/.env}"
: "${FTP_PASS:?Renseignez FTP_PASS dans scripts/.env}"
: "${FTP_DIR:?Renseignez FTP_DIR (ex: /maloc.pertec.fr/) dans scripts/.env}"

echo "→ Déploiement de $ROOT vers ${FTP_HOST}:${FTP_DIR}"

lftp -c "
set ftp:ssl-allow true;
set ssl:verify-certificate no;
open -u '${FTP_USER}','${FTP_PASS}' '${FTP_HOST}';
mirror --reverse --delete --verbose \
  --exclude-glob .git/ \
  --exclude-glob .github/ \
  --exclude-glob scripts/ \
  --exclude-glob '*.md' \
  --exclude-glob config/config.php \
  '${ROOT}/' '${FTP_DIR}';
"

echo "✓ Déploiement terminé. Les migrations de base s'appliqueront au prochain chargement du site."

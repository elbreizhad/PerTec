# PerTec — consignes pour Claude

## Fichiers de déploiement : NE JAMAIS MODIFIER

Ces fichiers sont spécifiques au serveur de production (PlanetHoster N0C, maloc.pertec.fr)
et évoluent côté déploiement. Ils ne doivent **jamais** être créés, modifiés, déplacés
ou supprimés dans une évolution de l'application :

- `deploy.php`
- `.htaccess`
- `.github/workflows/` (dont `deploy.yml`)
- `scripts/` (`deploy-ftp.sh`, `.env.example`, `README.md`)
- `config/` (`config.php`, `deploy_token.txt`, `config.example.php`)

Un hook (`.claude/hooks/protect-deploy.sh`) bloque toute tentative.
Si une fonctionnalité semble nécessiter un changement de ces fichiers, le signaler à
l'utilisateur au lieu de le faire.

## Réglages de l'application

Tout nouveau réglage (SMTP, signature, coordonnées…) est stocké en base, table `settings`
(page Paramètres, modèle `Setting`), jamais dans un fichier : les déploiements recopient
les fichiers du dépôt sur le serveur, mais ne touchent pas à la base.

Évolutions de schéma : nouveau fichier `database/migrations/NNN_nom.sql` (appliqué
automatiquement par `Migrator` au chargement du site).

# Déploiement — Pont GitHub → PlanetHoster N0C

Ce dossier contient tout ce qu'il faut pour déployer PerTec sur ton hébergement
**PlanetHoster N0C** depuis GitHub. Trois méthodes, de la plus automatique à la
plus manuelle. **Choisis-en une seule** (la A est recommandée).

> ⚠️ Aucune de ces méthodes ne touche à `config/config.php` sur le serveur
> (ta configuration de prod avec les identifiants MySQL est préservée), ni à ta
> base de données. Les données sont dans MySQL, pas dans les fichiers.

---

## Méthode A — GitHub Actions par SSH / rsync (recommandée) ✅

À **chaque push**, GitHub synchronise les fichiers sur ton serveur via SSH
(port **5022** chez PlanetHoster N0C). Plus fiable que le FTP (qui est souvent
bloqué par le pare-feu anti-brute-force après plusieurs connexions).

### 1. Préparer la clé SSH
Dans N0C : **Fichiers → Clés SSH** (port indiqué : **5022**).
- Génère une paire de clés dédiée au déploiement (sur ton PC) :
  `ssh-keygen -t ed25519 -f deploy_key -C "github-deploy"` (laisse la passphrase vide).
- Ajoute le contenu de `deploy_key.pub` via le bouton **Ajouter** de N0C.
- Garde `deploy_key` (clé privée) pour l'étape suivante.

### 2. Ajouter les secrets dans GitHub
Dépôt GitHub → **Settings → Secrets and variables → Actions → New repository secret** :

| Secret | Exemple | Description |
|--------|---------|-------------|
| `SSH_HOST` | `nodeXXX-eu.n0c.com` | hôte SSH N0C |
| `SSH_USER` | `monuser` | identifiant SSH/N0C |
| `SSH_PORT` | `5022` | port SSH PlanetHoster |
| `SSH_PRIVATE_KEY` | *(contenu de `deploy_key`)* | clé privée (tout le fichier) |
| `SSH_TARGET` | `/home/USER/maloc.pertec.fr/` | dossier cible (finir par `/`) |

### 3. C'est prêt
Le workflow [`.github/workflows/deploy.yml`](../.github/workflows/deploy.yml)
se déclenche à chaque push sur `main` (et sur la branche de travail), ou
manuellement via **Actions → Déploiement PlanetHoster → Run workflow**.

Les migrations de base de données s'appliquent **automatiquement** au premier
chargement du site après le déploiement.

> Si le serveur ne dispose pas de `rsync`, on peut basculer sur un déploiement
> SFTP pur — demande-le.

---

## Méthode B — Envoi FTP manuel depuis ton PC

Utile pour un envoi ponctuel sans passer par GitHub. Nécessite `lftp`.

```bash
cp scripts/.env.example scripts/.env   # puis renseigne tes accès FTP
bash scripts/deploy-ftp.sh
```

`scripts/.env` n'est jamais versionné (protégé par `.gitignore`).

---

## Méthode C — Git côté serveur (SSH)

Si tu préfères que le serveur récupère lui-même le code (N0C fournit un accès SSH
et Git).

### Première mise en place (une fois, en SSH)
```bash
cd ~/maloc.pertec.fr                 # dossier racine du site
git clone https://github.com/elbreizhad/PerTec.git .
cp config/config.example.php config/config.php   # puis édite tes identifiants MySQL
```

### À chaque mise à jour
```bash
cd ~/maloc.pertec.fr
git pull
php scripts/migrate.php              # applique les migrations (optionnel, sinon auto au 1er accès)
```

> Astuce : tu peux automatiser ce `git pull` avec une tâche **Cron** dans N0C
> (ex. toutes les heures), ou déclencher un déploiement depuis GitHub Actions
> via SSH (rsync) si tu ajoutes une clé SSH dédiée en secret.

---

## Contenu du dossier

| Fichier | Rôle |
|---------|------|
| `deploy-ftp.sh` | Déploiement FTP manuel depuis le PC local (méthode B) |
| `.env.example` | Modèle d'identifiants FTP pour la méthode B |
| `migrate.php` | Applique les migrations de base en ligne de commande (méthode C) |

## Ce qui n'est jamais déployé / écrasé

- `config/config.php` — ta configuration de prod (identifiants MySQL)
- `.git/`, `.github/`, `scripts/`, fichiers `*.md`
- La base de données MySQL (jamais touchée par un déploiement de fichiers)

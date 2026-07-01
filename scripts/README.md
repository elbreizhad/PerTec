# Déploiement — Pont GitHub → PlanetHoster N0C

Ce dossier contient tout ce qu'il faut pour déployer PerTec sur ton hébergement
**PlanetHoster N0C** depuis GitHub. Trois méthodes, de la plus automatique à la
plus manuelle. **Choisis-en une seule** (la A est recommandée).

> ⚠️ Aucune de ces méthodes ne touche à `config/config.php` sur le serveur
> (ta configuration de prod avec les identifiants MySQL est préservée), ni à ta
> base de données. Les données sont dans MySQL, pas dans les fichiers.

---

## Méthode A — GitHub Actions par FTP (recommandée) ✅

À **chaque push**, GitHub envoie automatiquement les fichiers sur ton serveur.
C'est le vrai « pont » : tu ne fais plus rien après la configuration initiale.

### 1. Récupérer tes accès FTP dans N0C
Dans le panneau **N0C** de PlanetHoster : section **FTP** → note l'hôte
(ex. `nodeXXX-eu.n0c.com`), ton identifiant et ton mot de passe FTP.
Repère aussi le **dossier racine du site** `maloc.pertec.fr` (souvent quelque
chose comme `/maloc.pertec.fr/` ou `/public_html/...`).

### 2. Ajouter les secrets dans GitHub
Dépôt GitHub → **Settings → Secrets and variables → Actions → New repository secret**.
Crée ces 4 secrets :

| Secret | Exemple | Description |
|--------|---------|-------------|
| `FTP_SERVER` | `nodeXXX-eu.n0c.com` | hôte FTP fourni par N0C |
| `FTP_USERNAME` | `monuser` | identifiant FTP |
| `FTP_PASSWORD` | `••••••••` | mot de passe FTP |
| `FTP_SERVER_DIR` | `/maloc.pertec.fr/` | dossier cible (termine par `/`) |

### 3. C'est prêt
Le workflow [`.github/workflows/deploy.yml`](../.github/workflows/deploy.yml)
se déclenche à chaque push sur `main` (et sur la branche de travail), ou
manuellement via l'onglet **Actions → Déploiement PlanetHoster → Run workflow**.

Les migrations de base de données s'appliquent **automatiquement** au premier
chargement du site après le déploiement.

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

# PerTec — Gestion locative

Application web PHP pour gérer votre patrimoine immobilier locatif :
biens, investissement & rentabilité, locataires, baux, suivi des loyers,
génération de **quittances de loyer** et de **contrats de bail** (PDF via impression).

## Fonctionnalités

- 🏠 **Biens** : fiche détaillée (adresse, surface, prix d'achat, frais de notaire,
  travaux, emprunt, taxe foncière, assurance…).
- 💰 **Investissement & rentabilité** : calcul automatique du coût total d'acquisition,
  de la rentabilité **brute** et **nette**, et du **cash-flow** mensuel/annuel.
- 👤 **Locataires** : carnet d'adresses des locataires.
- 📄 **Baux** : contrats liés à un bien et un locataire (loyer, charges, dépôt de garantie…).
- 📅 **Suivi des loyers** : génération automatique des échéances, marquage payé/en attente,
  détection des retards, statistiques annuelles.
- 🧾 **Quittances de loyer** : page imprimable conforme (→ « Enregistrer en PDF »),
  numérotation automatique.
- 📑 **Contrats de bail** : génération à partir des données du bail (loi du 6 juillet 1989).
- 🔐 **Accès protégé** par identifiant / mot de passe (compte unique).

## Prérequis

- PHP **7.1+** (testé sur 8.4) avec les extensions `pdo_mysql`, `dom`, `mbstring`, `gd`
- MySQL **5.7+** ou MariaDB **10.2+**
- Un serveur web (Apache avec `mod_rewrite`, ou Nginx)

> La génération PDF (quittances, contrats) utilise **Dompdf**, déjà inclus dans
> `vendor/` (aucun `composer install` requis sur le serveur). Les extensions
> `dom`, `mbstring` et `gd` doivent être activées.

## Installation

1. Copiez les fichiers sur votre hébergement (dossier racine ou sous-dossier).
2. Assurez-vous que le dossier `config/` est **accessible en écriture** par le serveur web
   (l'installateur y écrit `config.php`).
3. Ouvrez le site dans votre navigateur : vous êtes automatiquement redirigé vers
   **`/install`**.
4. Renseignez les identifiants de la base de données et créez votre compte admin.
   La base et les tables sont créées automatiquement.
5. Connectez-vous, puis renseignez vos **coordonnées de bailleur** dans
   *Paramètres* (elles figurent sur les quittances et contrats).

> L'installateur se désactive dès que `config/config.php` existe.

### Installation manuelle (optionnelle)

```bash
cp config/config.example.php config/config.php   # puis éditez les identifiants
mysql -u USER -p NOM_BASE < database/schema.sql   # crée les tables
```

## Test en local (serveur PHP intégré)

```bash
php -S 127.0.0.1:8000 router.php
```

Le fichier `router.php` sert les fichiers statiques (CSS) et délègue le reste à
`index.php`. Il n'est **pas** nécessaire en production.

## Structure

```
index.php              Point d'entrée (front controller)
router.php             Routeur pour le serveur PHP intégré (dev)
.htaccess              Réécriture d'URL + protection des dossiers (Apache)
config/                Configuration (config.php ignoré par git)
database/schema.sql    Schéma de la base
src/                   App, Database, Auth, helpers, modèles, contrôleurs
templates/             Vues (biens, locataires, baux, loyers, documents…)
assets/                CSS (interface + impression)
```

## Sécurité

- Mots de passe hachés (`password_hash`).
- Protection CSRF sur tous les formulaires.
- Requêtes préparées (PDO) contre les injections SQL.
- Le `.htaccess` bloque l'accès direct à `config/`, `src/`, `database/`, `templates/`.
  Sous Nginx, pensez à interdire l'accès à ces dossiers dans votre configuration.

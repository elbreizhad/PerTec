# CAHIER DES CHARGES - PERTEC.FR
## Site vitrine pour création de sites internet clé en main

---

## 1. PRÉSENTATION DU PROJET

### 1.1 Contexte
- **Porteur du projet** : Auto-entrepreneur spécialisé en création de sites web
- **Nom de domaine** : pertec.fr
- **Localisation** : Evreux (27), Eure, Normandie
- **Activité** : Création de sites internet vitrine clé en main (hébergement + domaine + création + rédaction)

### 1.2 Objectifs du site
- Attirer des clients locaux (Evreux et Eure élargi)
- Cibler toutes les petites activités (artisans, commerces, restaurants, professions libérales, TPE)
- Générer des demandes de devis
- Mettre en avant l'offre "clé en main" (tout-inclus)
- Optimisation SEO maximale pour référencement local

### 1.3 Cibles prioritaires
- Artisans (plombiers, électriciens, maçons, peintres, menuisiers...)
- Commerces (boutiques, fleuristes, magasins...)
- Restaurants, cafés, brasseries, traiteurs
- Professions libérales (avocats, comptables, kinés, médecins...)
- Services à la personne (coiffeurs, esthéticiennes, coaches...)
- TPE/PME locales
- Associations

---

## 2. SPÉCIFICATIONS TECHNIQUES

### 2.1 Technologies recommandées
- **Frontend** : HTML5, CSS3, JavaScript vanilla ou framework léger
- **Framework** : Next.js (React) OU Astro (recommandé pour performances SEO)
- **Styling** : Tailwind CSS
- **Formulaires** : Formspree, EmailJS ou Web3Forms
- **Analytics** : Google Analytics 4 + Google Search Console
- **Hébergement** : Vercel, Netlify ou OVH
- **Performance** : Score Google PageSpeed >90/100

### 2.2 Contraintes techniques
- Site 100% responsive (mobile-first)
- Temps de chargement < 2 secondes
- Accessibilité WCAG 2.1 niveau AA minimum
- HTTPS obligatoire
- Optimisation images (WebP, lazy loading)
- Schema.org (LocalBusiness, Service)
- Sitemap.xml et robots.txt

### 2.3 Compatibilité
- Navigateurs : Chrome, Firefox, Safari, Edge (2 dernières versions)
- Appareils : Desktop, tablette, mobile (iPhone et Android)

---

## 3. ARBORESCENCE DU SITE

```
📁 PERTEC.FR
│
├── 🏠 ACCUEIL (/)
│   ├── Hero avec CTA principal
│   ├── Présentation offre clé en main
│   ├── 3 packs tarifaires
│   ├── Pourquoi me choisir (5 arguments)
│   ├── Secteurs d'activité (visuels + liens)
│   ├── Témoignages clients
│   ├── Zone d'intervention
│   └── Formulaire contact rapide
│
├── 📋 SERVICES & TARIFS (/services)
│   ├── Détail de l'offre clé en main
│   ├── Ce qui est inclus (domaine, hébergement, création, rédaction)
│   ├── 3 packs détaillés avec prix
│   ├── Options supplémentaires
│   ├── Process de travail (étapes)
│   └── FAQ tarifs
│
├── 🎯 PAGES SECTORIELLES (landing pages SEO)
│   ├── /site-internet-artisan-evreux
│   ├── /creation-site-web-restaurant-eure
│   ├── /site-vitrine-commerce-evreux
│   ├── /site-internet-profession-liberale
│   ├── /site-web-coiffeur-estheticienne
│   └── /creation-site-tpe-normandie
│
├── 💼 PORTFOLIO (/realisations)
│   ├── Galerie de projets réalisés
│   ├── Filtres par secteur d'activité
│   ├── Études de cas (avant/après)
│   └── Témoignages clients intégrés
│
├── 📝 BLOG (/blog)
│   ├── Articles SEO optimisés
│   ├── Catégories (Conseils, SEO, Actualités web...)
│   └── Articles pré-rédigés à intégrer (voir section 5)
│
├── 📍 ZONE D'INTERVENTION (/zone-intervention)
│   ├── Carte interactive
│   ├── Liste des villes couvertes
│   └── Evreux, Louviers, Vernon, Bernay...
│
├── ℹ️ À PROPOS (/a-propos)
│   ├── Qui suis-je
│   ├── Mon expertise
│   ├── Pourquoi le clé en main
│   └── Valeurs (proximité, transparence, réactivité)
│
└── 📞 CONTACT (/contact)
    ├── Formulaire détaillé
    ├── Téléphone cliquable
    ├── Email
    ├── Adresse Evreux
    ├── Carte Google Maps
    └── Horaires disponibilité
```

---

## 4. PAGES DÉTAILLÉES

### 4.1 PAGE D'ACCUEIL (/)

#### Section HERO
- **Titre H1** : "Création de site internet clé en main à Evreux | Pertec.fr"
- **Sous-titre** : "Domaine + Hébergement + Création + Rédaction | Pour toutes activités | Site en ligne en 2 semaines"
- **CTA principal** : "Demander mon devis gratuit" (bouton orange/vert très visible)
- **CTA secondaire** : "Voir mes tarifs"
- **Visuel** : Mockup de sites web sur différents appareils OU illustration moderne

#### Section "Offre clé en main"
- **Titre H2** : "Tout inclus dans votre site internet"
- 4 blocs avec icônes :
  1. 🌐 **Nom de domaine** (.fr, .com, .net) - Réservation et configuration
  2. 🚀 **Hébergement web** - Rapide, sécurisé, maintenance incluse
  3. 🎨 **Création sur-mesure** - Design moderne et responsive
  4. ✍️ **Rédaction de contenu** - Textes optimisés SEO rédigés pour vous
- **Sous-texte** : "Vous ne vous occupez de rien, je gère tout de A à Z"

#### Section "Mes 3 packs"
Tableau comparatif clair des 3 offres :

**PACK STARTER** - XXX€
- Site 3 pages (Accueil, Services, Contact)
- Nom de domaine .fr inclus (1 an)
- Hébergement 1 an inclus
- Design responsive mobile/desktop
- Formulaire de contact
- Intégration Google Maps
- Rédaction des textes incluse
- Formation à la modification
- CTA : "Choisir Starter"

**PACK ESSENTIEL** - XXX€ ⭐ (POPULAIRE)
- Tout du Pack Starter +
- Site jusqu'à 5 pages
- Galerie photos (jusqu'à 20 images)
- Optimisation SEO avancée
- Fiche Google Business (création/optimisation)
- Intégration réseaux sociaux
- Certificat SSL
- Support 3 mois inclus
- CTA : "Choisir Essentiel"

**PACK PREMIUM** - XXX€
- Tout du Pack Essentiel +
- Site jusqu'à 10 pages
- Blog intégré (5 articles rédigés)
- Module réservation OU catalogue produits
- Animation et effets avancés
- Optimisation performance maximale
- Support 6 mois inclus
- Maintenance mensuelle
- CTA : "Choisir Premium"

**Mention** : "Paiement en 3x ou 4x sans frais possible"

#### Section "Pourquoi me choisir"
5 arguments clés avec icônes :
1. ✅ **Service local** - Basé à Evreux, rendez-vous possibles
2. ⚡ **Rapide** - Votre site en ligne en 2-3 semaines
3. 💰 **Tarifs transparents** - Pas de frais cachés, tout est inclus
4. 🎯 **Clé en main** - Vous ne vous occupez de rien
5. 🔧 **Support réactif** - Je suis là après la livraison

#### Section "Pour toutes activités"
Grille visuelle 3x3 avec icônes cliquables :
- 🔧 Artisans (lien vers page sectorielle)
- 🍽️ Restaurants (lien)
- 🏪 Commerces (lien)
- ⚖️ Professions libérales (lien)
- ✂️ Services à la personne (lien)
- 🏢 TPE/PME (lien)
- 🏥 Santé (lien)
- 🎓 Associations (lien)
- 💼 Tous secteurs (lien services)

#### Section Témoignages
Carrousel de 5-6 témoignages :
- Photo client (ou initiales)
- Prénom + Activité + Ville
- Citation (2-3 phrases)
- Note 5 étoiles
- Exemple : "Jean D., Plombier à Louviers - 'Site parfait, clients en 1 semaine !'"

#### Section Zone d'intervention
- **Titre** : "J'interviens dans tout le département de l'Eure"
- Carte ou liste de villes : Evreux, Louviers, Vernon, Bernay, Pont-Audemer, Val-de-Reuil, Gisors, Les Andelys...
- **Sous-texte** : "Et aussi : Yvelines (78), Eure-et-Loir (28), Seine-Maritime (76)"

#### Section CTA final
- Fond coloré
- **Titre** : "Prêt à lancer votre site internet ?"
- **Texte** : "Demandez votre devis gratuit sans engagement. Réponse sous 24h."
- Formulaire simplifié (Nom, Email, Téléphone, Activité, Message)
- Bouton : "Recevoir mon devis gratuit"

#### Footer
- Logo Pertec
- Navigation (liens pages principales)
- Coordonnées : Téléphone, Email, Adresse Evreux
- Réseaux sociaux
- Mentions légales, CGV, Politique confidentialité
- © 2025 Pertec.fr - Création de sites internet à Evreux

---

### 4.2 PAGE SERVICES & TARIFS (/services)

#### Structure :
- **H1** : "Création de site internet clé en main : formules et tarifs"
- Explication détaillée de l'offre clé en main
- **Section "Qu'est-ce que le clé en main ?"**
  - Comparaison : ce que vous NE gérez PAS (technique, hébergement, configuration...) vs ce que VOUS faites (donner infos sur votre activité)
- **Section "Ce qui est inclus dans tous les packs"**
  - Liste exhaustive : design responsive, optimisation mobile, sécurité SSL, sauvegarde, etc.
- Tableau comparatif des 3 packs (même que accueil, plus détaillé)
- **Section "Options supplémentaires"** (à la carte)
  - Logo création : XXX€
  - Séance photo pro : XXX€
  - Rédaction article blog (x10) : XXX€
  - Module e-commerce : XXX€
  - Publicité Google Ads (gestion) : XXX€/mois
- **Section "Mon process de travail"**
  1. Prise de contact et devis gratuit
  2. Rendez-vous (visio ou présentiel) pour définir vos besoins
  3. Création de la maquette et validation
  4. Développement du site
  5. Rédaction des contenus
  6. Validation et ajustements
  7. Mise en ligne
  8. Formation à la gestion
- **FAQ Tarifs** (10-15 questions/réponses)
  - "Pourquoi ces prix ?"
  - "Que se passe-t-il après la première année d'hébergement ?"
  - "Puis-je payer en plusieurs fois ?"
  - "Combien de temps pour créer le site ?"
  - "Puis-je modifier mon site moi-même après ?"
  - etc.

---

### 4.3 PAGES SECTORIELLES (landing pages SEO)

**6 pages à créer minimum** (structure identique, contenu adapté)

#### Exemple : /site-internet-artisan-evreux

**H1** : "Création de site internet pour artisans à Evreux et dans l'Eure"

**Sections** :
1. **Hero spécifique**
   - Visuel métier (plombier, électricien...)
   - Texte : "Plombier, électricien, maçon, peintre... Développez votre activité avec un site professionnel"
   - CTA : "Devis gratuit artisan"

2. **Pourquoi un site pour artisan ?**
   - Être trouvé sur Google quand on cherche "plombier Evreux"
   - Présenter vos services 24h/24
   - Montrer vos réalisations (galerie photos)
   - Recevoir des demandes de devis en ligne
   - Être crédible et professionnel

3. **Ce qui est inclus pour les artisans**
   - Pages : Accueil, Services, Réalisations, Devis, Contact
   - Formulaire devis personnalisé
   - Galerie photos chantiers
   - Zone d'intervention
   - Avis clients
   - Textes rédigés sur vos métiers

4. **Témoignage artisan**
   - Exemple : "Marc, électricien à Louviers - J'ai eu 15 demandes de devis le premier mois"

5. **Pack recommandé pour artisans**
   - Pack Essentiel ou Premium
   - Prix et détails

6. **FAQ artisans**
   - "Combien coûte un site pour artisan ?"
   - "Puis-je ajouter mes photos de chantiers ?"
   - "Comment recevoir des demandes de devis ?"

7. **CTA final** : Formulaire devis

**Mots-clés SEO à intégrer naturellement** :
- site internet artisan Evreux
- création site web plombier Eure
- site vitrine électricien
- développeur web pour artisan Normandie
- etc.

#### Autres pages sectorielles à créer :
- **/creation-site-web-restaurant-eure**
  - Spécificités : menu en ligne, photos plats, réservation, avis Google
- **/site-vitrine-commerce-evreux**
  - Boutique, horaires, promotions, catalogue produits
- **/site-internet-profession-liberale**
  - Avocats, comptables, médecins : présentation expertise, prise RDV
- **/site-web-coiffeur-estheticienne**
  - Galerie avant/après, tarifs, réservation en ligne
- **/creation-site-tpe-normandie**
  - Page générique pour toutes TPE/PME

---

### 4.4 PAGE PORTFOLIO (/realisations)

**H1** : "Mes réalisations : sites internet créés pour mes clients"

**Structure** :
- Intro : "Découvrez les sites web que j'ai créés pour des artisans, commerces, restaurants et professions libérales dans l'Eure"
- **Filtres** : Tous / Artisans / Restaurants / Commerces / Professions libérales / Services
- **Grille de projets** (format carte)
  - Image screenshot site
  - Nom client (ou anonyme)
  - Secteur d'activité
  - Ville
  - Courte description
  - Lien "Voir le site" (si autorisation client)
  - Badge "Pack Essentiel" / "Pack Premium"
- **Études de cas** (3-4 projets détaillés)
  - Contexte : problème du client
  - Solution apportée
  - Résultats (nombre visites, demandes reçues...)
  - Témoignage client
  - Avant/Après (si possible)

**Note importante** : Au début, si pas encore de clients, créer 3-4 projets "démo" ou "fictifs" clairement identifiés comme exemples.

---

### 4.5 PAGE BLOG (/blog)

**H1** : "Blog : conseils pour votre site internet et votre visibilité en ligne"

**Structure** :
- Liste d'articles (format blog classique)
- Sidebar : catégories, articles populaires, CTA "Devis gratuit"
- Pagination
- Partage réseaux sociaux

**Catégories** :
- Conseils web
- SEO et référencement
- Marketing digital
- Actualités web
- Études de cas

**Articles à rédiger** (voir section 5 pour contenu détaillé)

---

### 4.6 PAGE ZONE D'INTERVENTION (/zone-intervention)

**H1** : "Zone d'intervention : création de sites internet dans l'Eure et départements limitrophes"

**Structure** :
- Carte interactive (Google Maps ou Leaflet) avec marqueurs villes principales
- **Eure (27)** : Liste des villes
  - Evreux (ville principale)
  - Louviers
  - Vernon
  - Bernay
  - Pont-Audemer
  - Val-de-Reuil
  - Gisors
  - Les Andelys
  - Conches-en-Ouche
  - Brionne
  - (etc.)
- **Départements limitrophes** :
  - Yvelines (78) : Mantes-la-Jolie, Poissy...
  - Eure-et-Loir (28) : Dreux, Chartres...
  - Seine-Maritime (76) : Rouen, Elbeuf...
  - Orne (61) : L'Aigle...
- **Texte SEO** : "Je me déplace dans toute l'Eure pour rencontrer mes clients et comprendre leurs besoins. Basé à Evreux, j'interviens également dans les départements voisins..."

---

### 4.7 PAGE À PROPOS (/a-propos)

**H1** : "À propos de Pertec : votre créateur de sites internet à Evreux"

**Structure** :
- Photo professionnelle (ou avatar)
- **Qui suis-je ?**
  - Présentation personnelle (parcours, pourquoi création web)
  - Auto-entrepreneur basé à Evreux
  - Passionné par le digital et l'accompagnement des TPE
- **Mon expertise**
  - Compétences techniques (développement, design, SEO, rédaction)
  - Outils utilisés
  - Veille technologique
- **Ma philosophie**
  - Pourquoi le "clé en main" : simplifier la vie des entrepreneurs
  - Proximité : service local, à l'écoute
  - Transparence : tarifs clairs, pas de surprise
  - Réactivité : disponible et joignable
- **Mes valeurs**
  - Qualité
  - Accompagnement personnalisé
  - Résultats concrets pour mes clients
- **CTA** : "Discutons de votre projet"

---

### 4.8 PAGE CONTACT (/contact)

**H1** : "Demandez votre devis gratuit pour votre site internet"

**Formulaire de contact** (champs obligatoires *) :
- Nom et prénom *
- Entreprise / Activité *
- Email *
- Téléphone *
- Ville *
- Type de projet * (menu déroulant)
  - Création nouveau site
  - Refonte site existant
  - Maintenance / Support
  - Autre
- Pack souhaité (menu déroulant)
  - Starter
  - Essentiel
  - Premium
  - Je ne sais pas encore
- Message / Décrivez votre projet *
- Case à cocher : "J'accepte d'être recontacté" *
- Bouton : "Envoyer ma demande"

**Coordonnées visibles** :
- 📞 Téléphone : [NUMÉRO] (lien cliquable mobile)
- 📧 Email : contact@pertec.fr
- 📍 Adresse : Evreux, Eure (27)
- 🕒 Disponibilité : Lundi-Vendredi 9h-18h

**Carte Google Maps** : 
- Centré sur Evreux

**Temps de réponse** :
- Badge "Réponse sous 24h garantie"

**Réassurance** :
- "Devis gratuit et sans engagement"
- "Premier échange téléphonique ou visio offert"

---

## 5. CONTENU BLOG - ARTICLES À RÉDIGER

### Articles prioritaires (10 articles SEO)

#### Article 1 : "Pourquoi votre restaurant à Evreux a besoin d'un site internet en 2025"
- **Mots-clés** : site internet restaurant Evreux, création site web restaurant
- **Contenu** : Statistiques (% clients cherchent sur Google), avantages (visibilité, menu en ligne, réservation), exemples, CTA

#### Article 2 : "Combien coûte un site internet professionnel à Evreux ? Guide des tarifs 2025"
- **Mots-clés** : prix site internet Evreux, tarif création site web
- **Contenu** : Fourchettes de prix, ce qui influence le coût, pourquoi pas de site gratuit, comparaison packs

#### Article 3 : "Site vitrine vs marketplace : que choisir pour votre commerce ?"
- **Mots-clés** : site vitrine commerce, différence site e-commerce
- **Contenu** : Définitions, avantages/inconvénients, quand choisir quoi, exemples

#### Article 4 : "Artisan : 5 raisons d'avoir votre propre site web plutôt que juste Facebook"
- **Mots-clés** : site internet artisan, visibilité artisan Google
- **Contenu** : Limites réseaux sociaux, avantages site propre, SEO, crédibilité

#### Article 5 : "Les 7 pages indispensables d'un bon site internet professionnel"
- **Mots-clés** : pages site internet, structure site vitrine
- **Contenu** : Accueil, Services, À propos, Réalisations, Tarifs, Blog, Contact - pourquoi chacune

#### Article 6 : "Comment être trouvé sur Google quand on est une TPE à Evreux ?"
- **Mots-clés** : référencement local Evreux, SEO TPE
- **Contenu** : Google Business, site web optimisé, avis clients, contenu local

#### Article 7 : "Site internet clé en main : qu'est-ce que ça inclut vraiment ?"
- **Mots-clés** : site clé en main, création site tout compris
- **Contenu** : Définition, avantages, ce qui est géré pour vous, vs DIY

#### Article 8 : "Refonte de site web : 10 signes qu'il est temps de refaire votre site"
- **Mots-clés** : refonte site internet, moderniser site web
- **Contenu** : Design obsolète, pas mobile, lent, pas sécurisé, etc.

#### Article 9 : "Google My Business + Site internet : le combo gagnant pour les commerçants"
- **Mots-clés** : Google Business Profile, fiche Google entreprise
- **Contenu** : Complémentarité, comment optimiser les deux, exemples

#### Article 10 : "Créer son site soi-même ou faire appel à un professionnel : le vrai comparatif"
- **Mots-clés** : créer site internet soi-même, développeur web vs DIY
- **Contenu** : Avantages/inconvénients, temps, compétences, coûts cachés

---

## 6. OPTIMISATION SEO

### 6.1 SEO On-Page (à implémenter sur TOUTES les pages)

**Balises meta** :
- Title unique par page (50-60 caractères)
  - Exemple accueil : "Création Site Internet Evreux | Clé en Main | Pertec.fr"
  - Exemple page artisan : "Site Internet pour Artisan à Evreux | Formule Tout Inclus"
- Meta description unique (150-160 caractères)
  - Exemple : "Créateur de sites web à Evreux. Formule clé en main : domaine + hébergement + création + rédaction. Devis gratuit sous 24h. ☎️ [TEL]"

**Structure Hn** :
- 1 seul H1 par page (mot-clé principal)
- H2 pour sections principales
- H3 pour sous-sections
- Hiérarchie logique et cohérente

**URL optimisées** :
- Courtes, descriptives, avec mots-clés
- Exemples :
  - `/services` ✅
  - `/site-internet-artisan-evreux` ✅
  - `/blog/cout-site-internet-2025` ✅
  - Éviter : `/page.php?id=123` ❌

**Texte alt images** :
- Toutes les images doivent avoir un attribut alt descriptif
- Exemple : "Site internet responsive pour restaurant créé par Pertec à Evreux"

**Maillage interne** :
- Liens entre pages pertinentes
- Ancres optimisées (pas de "cliquez ici")
- Minimum 2-3 liens internes par page

**Mots-clés à intégrer naturellement** :
- Principaux : création site internet Evreux, site web clé en main, développeur web Eure, site vitrine Evreux
- Longue traîne : site internet artisan Evreux, création site restaurant Eure, site web commerce Louviers, développeur web Normandie

### 6.2 SEO Technique

**Performance** :
- Compression images (WebP, lazy loading)
- Minification CSS/JS
- Cache navigateur
- CDN si possible
- Score PageSpeed >90

**Mobile-friendly** :
- Test Google Mobile-Friendly réussi
- Design responsive natif
- Boutons tactiles suffisamment grands
- Pas de pop-ups intrusifs

**Schema.org** (données structurées) :
```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Pertec - Création de sites internet",
  "image": "https://pertec.fr/logo.jpg",
  "description": "Création de sites internet clé en main pour TPE, artisans, commerces à Evreux et dans l'Eure",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "[ADRESSE]",
    "addressLocality": "Evreux",
    "postalCode": "27000",
    "addressCountry": "FR"
  },
  "telephone": "[TELEPHONE]",
  "url": "https://pertec.fr",
  "priceRange": "€€",
  "areaServed": ["Evreux", "Louviers", "Vernon", "Eure"],
  "serviceType": ["Création site internet", "Développement web", "SEO"]
}
```

**Sitemap.xml** :
- Génération automatique de toutes les pages
- Soumission à Google Search Console

**Robots.txt** :
```
User-agent: *
Allow: /
Sitemap: https://pertec.fr/sitemap.xml
```

**Certificat SSL** :
- HTTPS sur tout le site
- Redirection HTTP → HTTPS

### 6.3 SEO Local (PRIORITAIRE)

**Google Business Profile** :
- Créer/optimiser fiche
- Catégorie : "Concepteur de sites web" ou "Agence de marketing numérique"
- Ajout photos (logo, locaux si possible, mockups sites)
- Posts réguliers
- Récolte d'avis 5 étoiles

**Citations locales** :
- Inscription annuaires : PagesJaunes, Yelp, Mappy, Solocal
- Cohérence NAP (Name, Address, Phone) partout

**Contenu local** :
- Mentionner Evreux et villes de l'Eure dans les textes
- Page zone d'intervention détaillée
- Articles blog avec ancrage local

### 6.4 Backlinks (à développer progressivement)

**Stratégie d'acquisition** :
- Partenariats locaux (CCI Portes de Normandie, annuaires pros)
- Sites clients (footer "Site créé par Pertec.fr")
- Articles invités sur blogs marketing/web
- Inscription annuaires qualité (éviter spam)
- Réseaux sociaux (LinkedIn, Facebook page professionnelle)

---

## 7. DESIGN & IDENTITÉ VISUELLE

### 7.1 Charte graphique

**Couleurs principales** (à définir - suggestions) :
- Primaire : Bleu moderne (#2563EB) ou Vert tech (#10B981)
- Secondaire : Orange énergique (#F59E0B) pour CTA
- Neutre : Gris foncé (#1F2937), Gris clair (#F3F4F6), Blanc (#FFFFFF)

**Typographie** :
- Titres : Police moderne sans-serif (Inter, Poppins, Montserrat)
- Texte : Police lisible (Inter, Open Sans, Roboto)
- Taille minimum mobile : 16px

**Style général** :
- Design moderne et épuré
- Pas surchargé (beaucoup d'espace blanc)
- Professionnel mais accessible
- Icônes : Font Awesome ou Heroicons

### 7.2 Composants UI

**Boutons CTA** :
- Primaire : Fond orange/vert, texte blanc, ombre portée, hover animation
- Secondaire : Bordure, fond transparent
- Taille : minimum 44x44px (tactile)

**Cartes** :
- Bords arrondis (8-12px)
- Ombre légère
- Hover : élévation

**Formulaires** :
- Champs larges et espacés
- Labels clairs
- Validation en temps réel
- Messages d'erreur/succès

**Témoignages** :
- Format carte avec guillemets
- Photo/initiales
- 5 étoiles visuelles

---

## 8. FONCTIONNALITÉS ATTENDUES

### 8.1 Formulaires

**Formulaire contact/devis** :
- Validation côté client (JavaScript)
- Messages d'erreur clairs
- Envoi email (Formspree, EmailJS, ou backend simple)
- Message de confirmation après envoi
- Auto-réponse email au client
- Protection anti-spam (reCAPTCHA v3 invisible)

### 8.2 Interactions

**Animations** :
- Scroll animations (fade in, slide up) - librairie : AOS.js ou Framer Motion
- Hover effects sur boutons/cartes
- Transitions fluides
- Pas trop de mouvement (accessibilité)

**Navigation** :
- Menu responsive (burger mobile)
- Sticky header au scroll
- Smooth scroll vers ancres
- Fil d'Ariane sur pages profondes

### 8.3 Intégrations

**Google Maps** :
- Page contact : carte interactive centrée sur Evreux
- Page zone intervention : carte avec marqueurs villes

**Google Analytics 4** :
- Tracking pages vues
- Événements : clics CTA, soumission formulaire, clics téléphone

**Réseaux sociaux** :
- Boutons partage articles blog
- Liens vers profils sociaux (footer)

**Live chat** (optionnel) :
- Widget type Tawk.to ou Crisp (gratuit)
- Disponibilité heures ouvrables

---

## 9. CONTENUS À FOURNIR

### 9.1 Textes

**Pages principales** :
- Rédaction de tous les textes (à faire via IA + personnalisation)
- Ton : professionnel mais accessible, direct, orienté bénéfices client
- Longueur minimale accueil : 800-1000 mots
- Pages sectorielles : 600-800 mots
- Articles blog : 800-1200 mots

**Appels à l'action (CTA)** :
- "Demandez votre devis gratuit"
- "Appelez-moi maintenant"
- "Voir mes réalisations"
- "Découvrir mes tarifs"
- "Discutons de votre projet"

### 9.2 Visuels

**Images nécessaires** :
- Logo Pertec (à créer ou utiliser texte stylisé)
- Hero image accueil (mockup sites web, illustration)
- Photos/icônes secteurs d'activité
- Mockups projets portfolio (si pas encore de vrais clients)
- Illustrations process/étapes
- Photo "à propos" (ou avatar professionnel)

**Sources images libres** :
- Unsplash, Pexels (photos HD gratuites)
- unDraw, Storyset (illustrations gratuites)
- Mockup : Smartmockups, Previewed

### 9.3 Informations à intégrer

**Coordonnées** :
- Nom complet / Raison sociale
- Téléphone
- Email : contact@pertec.fr
- Adresse : Evreux (préciser si possible)
- Numéro SIRET (mentions légales)

**Horaires disponibilité** :
- Lundi-Vendredi : 9h-18h (ou adapter)

**Réseaux sociaux** :
- LinkedIn (profil professionnel)
- Facebook page (si créée)
- Instagram (optionnel)

---

## 10. PAGES LÉGALES

### 10.1 Mentions légales (/mentions-legales)

**Contenu obligatoire** :
- Éditeur du site (nom, statut auto-entrepreneur)
- SIRET
- Adresse siège social
- Contact (email, téléphone)
- Directeur de publication
- Hébergeur (nom, adresse, téléphone)
- Propriété intellectuelle
- Limitation de responsabilité

### 10.2 Politique de confidentialité (/politique-confidentialite)

**RGPD** :
- Données collectées (formulaires, cookies)
- Finalité collecte
- Durée conservation
- Droits utilisateur (accès, rectification, suppression)
- Contact pour exercer droits
- Cookies utilisés (Analytics)
- Consentement cookies (banner)

### 10.3 CGV (si vente en ligne) (/cgv)

**Conditions Générales de Vente** :
- Prix et modalités paiement
- Délais livraison/réalisation
- Droit de rétractation (si applicable)
- Garanties
- Résolution litiges

---

## 11. LIVRABLES ATTENDUS

### Phase 1 : Structure et design (semaine 1-2)
- Maquette page d'accueil (desktop + mobile)
- Validation charte graphique
- Arborescence confirmée
- Setup projet technique

### Phase 2 : Développement (semaine 2-4)
- Toutes les pages HTML/CSS/JS
- Formulaires fonctionnels
- Responsive parfait
- Optimisations SEO de base

### Phase 3 : Contenu (semaine 4-5)
- Tous les textes rédigés et intégrés
- 10 articles blog rédigés
- Images optimisées et intégrées
- Relecture et corrections

### Phase 4 : Finitions (semaine 5-6)
- Tests multi-navigateurs/appareils
- Corrections bugs
- Optimisations performances
- Schema.org, sitemap, robots.txt
- Google Analytics setup

### Phase 5 : Mise en ligne et SEO (semaine 6)
- Déploiement sur hébergement
- Configuration domaine pertec.fr
- Soumission Google Search Console
- Création Google Business Profile
- Tests finaux

---

## 12. TESTS & VALIDATION

### 12.1 Tests fonctionnels
- ✅ Tous les liens fonctionnent
- ✅ Formulaires envoient correctement
- ✅ Navigation fluide
- ✅ Animations fonctionnelles
- ✅ Pas d'erreurs console

### 12.2 Tests compatibilité
- ✅ Chrome, Firefox, Safari, Edge
- ✅ iPhone (Safari)
- ✅ Android (Chrome)
- ✅ Tablettes iPad/Android
- ✅ Desktop 1920px, 1366px, 1024px
- ✅ Mobile 375px, 414px

### 12.3 Tests performance
- ✅ Google PageSpeed >90 (mobile et desktop)
- ✅ Temps chargement <2s
- ✅ Images optimisées
- ✅ Pas de ressources bloquantes

### 12.4 Tests SEO
- ✅ Test Google Mobile-Friendly
- ✅ Balises meta uniques et optimisées
- ✅ Structure Hn correcte
- ✅ Attributs alt images
- ✅ Sitemap.xml généré
- ✅ Robots.txt configuré
- ✅ HTTPS actif
- ✅ Schema.org validé (Google Rich Results Test)

### 12.5 Tests accessibilité
- ✅ Contraste couleurs suffisant (WCAG AA)
- ✅ Navigation clavier possible
- ✅ Lecteurs d'écran compatibles
- ✅ Labels formulaires explicites

---

## 13. MAINTENANCE ET ÉVOLUTION

### 13.1 Maintenance régulière
- Sauvegarde hebdomadaire
- Mises à jour sécurité
- Monitoring uptime
- Vérification liens cassés
- Renouvellement domaine/hébergement

### 13.2 Évolutions futures possibles
- Ajout nouvelles pages sectorielles
- Blog alimenté régulièrement (2-4 articles/mois)
- Témoignages clients réels
- Portfolio enrichi
- Module de prise de rendez-vous en ligne
- Chat en direct
- Espace client (si beaucoup de clients)
- Multilingue (anglais) si expansion

---

## 14. BUDGET & DÉLAIS

### 14.1 Délai de réalisation
- **Durée estimée** : 4-6 semaines
- **Phases** :
  - Design & structure : 1-2 semaines
  - Développement : 2-3 semaines
  - Rédaction contenu : 1 semaine (parallèle développement)
  - Tests & corrections : 1 semaine
  - Mise en ligne : 2-3 jours

### 14.2 Coûts récurrents annuels
- Nom de domaine .fr : ~15€/an
- Hébergement (Vercel/Netlify gratuit OU OVH ~50€/an)
- Email professionnel (optionnel) : ~50€/an
- **Total** : 65-115€/an

### 14.3 Budget développement
- À définir selon prestataire (Claude Code = gratuit pour vous si vous le faites)
- Si délégation : 800-2000€ selon complexité

---

## 15. CRITÈRES DE SUCCÈS

### KPIs à 3 mois :
- ✅ Site indexé sur Google (minimum 10 pages)
- ✅ Positionnement page 1-2 Google sur "création site internet Evreux"
- ✅ 5+ demandes de devis reçues
- ✅ Google Business Profile avec 5+ avis
- ✅ 100+ visiteurs/mois organiques
- ✅ Score PageSpeed >90

### KPIs à 6 mois :
- ✅ 10+ clients réalisations portfolio
- ✅ Positionnement top 3 sur mots-clés locaux principaux
- ✅ 300+ visiteurs/mois
- ✅ Taux conversion formulaire >3%
- ✅ 20 articles blog publiés

---

## 16. RECOMMANDATIONS TECHNIQUES POUR CLAUDE CODE

### 16.1 Stack technique recommandée

**Option A : Astro (RECOMMANDÉ pour SEO)**
```bash
npm create astro@latest pertec-fr
# Template: Portfolio ou Blog
# Astro = ultra-rapide, SEO natif, parfait pour site vitrine
```
**Avantages** :
- Performance exceptionnelle (score PageSpeed 100 facile)
- SEO optimisé par défaut
- Support Tailwind CSS natif
- Génération static (SSG) = rapidité maximale
- Peu de JavaScript (meilleur pour SEO)

**Option B : Next.js**
```bash
npx create-next-app@latest pertec-fr
# Next.js = populaire, flexible, bon SEO
```
**Avantages** :
- Écosystème riche
- Image optimization native
- SEO solide avec next/head
- Deploy facile sur Vercel

### 16.2 Librairies utiles

**Styling** :
```bash
npm install tailwindcss @tailwindcss/forms @tailwindcss/typography
```

**Animations** :
```bash
npm install aos # Animate On Scroll
# OU
npm install framer-motion # Animations React
```

**Formulaires** :
```bash
npm install react-hook-form # Si Next.js/React
npm install @formspree/react # Service envoi email facile
```

**SEO** :
```bash
npm install next-seo # Si Next.js
```

**Icons** :
```bash
npm install @heroicons/react # OU lucide-react
```

### 16.3 Structure fichiers recommandée

```
pertec-fr/
├── public/
│   ├── favicon.ico
│   ├── logo.svg
│   ├── images/
│   │   ├── hero.jpg
│   │   ├── secteurs/
│   │   └── portfolio/
│   └── robots.txt
├── src/
│   ├── components/
│   │   ├── Header.astro
│   │   ├── Footer.astro
│   │   ├── Hero.astro
│   │   ├── CTAButton.astro
│   │   ├── PackCard.astro
│   │   ├── Testimonial.astro
│   │   └── ContactForm.astro
│   ├── layouts/
│   │   └── BaseLayout.astro
│   ├── pages/
│   │   ├── index.astro (accueil)
│   │   ├── services.astro
│   │   ├── realisations.astro
│   │   ├── a-propos.astro
│   │   ├── contact.astro
│   │   ├── zone-intervention.astro
│   │   ├── mentions-legales.astro
│   │   ├── politique-confidentialite.astro
│   │   ├── secteurs/
│   │   │   ├── artisan.astro
│   │   │   ├── restaurant.astro
│   │   │   ├── commerce.astro
│   │   │   ├── profession-liberale.astro
│   │   │   ├── coiffeur-estheticienne.astro
│   │   │   └── tpe.astro
│   │   └── blog/
│   │       ├── index.astro
│   │       └── [slug].astro
│   ├── content/ (si Astro Content Collections)
│   │   └── blog/
│   │       ├── article-1.md
│   │       ├── article-2.md
│   │       └── ...
│   ├── styles/
│   │   └── global.css
│   └── data/
│       ├── testimonials.json
│       ├── portfolio.json
│       └── villes.json
├── astro.config.mjs
├── tailwind.config.cjs
├── package.json
└── README.md
```

### 16.4 Configuration Tailwind

**tailwind.config.cjs** :
```javascript
module.exports = {
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
  theme: {
    extend: {
      colors: {
        primary: '#2563EB', // Bleu
        secondary: '#F59E0B', // Orange
        dark: '#1F2937',
        light: '#F3F4F6',
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### 16.5 Exemples de composants clés

**Header.astro** :
```astro
---
// Logique du composant
const navItems = [
  { label: 'Services', href: '/services' },
  { label: 'Réalisations', href: '/realisations' },
  { label: 'Blog', href: '/blog' },
  { label: 'À propos', href: '/a-propos' },
  { label: 'Contact', href: '/contact' },
];
---

<header class="sticky top-0 bg-white shadow-md z-50">
  <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
    <a href="/" class="text-2xl font-bold text-primary">Pertec.fr</a>
    
    <!-- Desktop Menu -->
    <ul class="hidden md:flex space-x-6">
      {navItems.map(item => (
        <li><a href={item.href} class="hover:text-primary transition">{item.label}</a></li>
      ))}
    </ul>
    
    <!-- CTA Button -->
    <a href="/contact" class="hidden md:block bg-secondary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
      Devis gratuit
    </a>
    
    <!-- Mobile Menu Button -->
    <button class="md:hidden" id="mobile-menu-button">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </nav>
  
  <!-- Mobile Menu (hidden by default) -->
  <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
    <ul class="px-4 py-4 space-y-3">
      {navItems.map(item => (
        <li><a href={item.href} class="block hover:text-primary">{item.label}</a></li>
      ))}
      <li><a href="/contact" class="block bg-secondary text-white px-6 py-2 rounded-lg text-center">Devis gratuit</a></li>
    </ul>
  </div>
</header>

<script>
  // Toggle mobile menu
  const menuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  
  menuButton?.addEventListener('click', () => {
    mobileMenu?.classList.toggle('hidden');
  });
</script>
```

**PackCard.astro** :
```astro
---
interface Props {
  title: string;
  price: string;
  features: string[];
  popular?: boolean;
  ctaText?: string;
  ctaLink?: string;
}

const { title, price, features, popular = false, ctaText = "Choisir", ctaLink = "/contact" } = Astro.props;
---

<div class={`border rounded-lg p-6 hover:shadow-xl transition ${popular ? 'border-secondary border-2 relative' : 'border-gray-200'}`}>
  {popular && (
    <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-secondary text-white px-4 py-1 rounded-full text-sm font-semibold">
      ⭐ Populaire
    </span>
  )}
  
  <h3 class="text-2xl font-bold mb-2">{title}</h3>
  <p class="text-4xl font-bold text-primary mb-6">{price}</p>
  
  <ul class="space-y-3 mb-6">
    {features.map(feature => (
      <li class="flex items-start">
        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-gray-700">{feature}</span>
      </li>
    ))}
  </ul>
  
  <a href={ctaLink} class={`block text-center py-3 px-6 rounded-lg font-semibold transition ${popular ? 'bg-secondary text-white hover:bg-opacity-90' : 'bg-gray-100 text-dark hover:bg-gray-200'}`}>
    {ctaText}
  </a>
</div>
```

**ContactForm.astro** :
```astro
<form action="https://formspree.io/f/YOUR_FORM_ID" method="POST" class="space-y-4">
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label for="nom" class="block text-sm font-medium mb-1">Nom et Prénom *</label>
      <input type="text" id="nom" name="nom" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
    </div>
    
    <div>
      <label for="entreprise" class="block text-sm font-medium mb-1">Entreprise / Activité *</label>
      <input type="text" id="entreprise" name="entreprise" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
    </div>
  </div>
  
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label for="email" class="block text-sm font-medium mb-1">Email *</label>
      <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
    </div>
    
    <div>
      <label for="telephone" class="block text-sm font-medium mb-1">Téléphone *</label>
      <input type="tel" id="telephone" name="telephone" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
    </div>
  </div>
  
  <div>
    <label for="pack" class="block text-sm font-medium mb-1">Pack souhaité</label>
    <select id="pack" name="pack" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
      <option>Je ne sais pas encore</option>
      <option>Pack Starter</option>
      <option>Pack Essentiel</option>
      <option>Pack Premium</option>
    </select>
  </div>
  
  <div>
    <label for="message" class="block text-sm font-medium mb-1">Décrivez votre projet *</label>
    <textarea id="message" name="message" rows="5" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary"></textarea>
  </div>
  
  <div class="flex items-start">
    <input type="checkbox" id="consent" name="consent" required class="mt-1 mr-2">
    <label for="consent" class="text-sm text-gray-600">J'accepte d'être recontacté concernant ma demande *</label>
  </div>
  
  <button type="submit" class="w-full bg-secondary text-white py-3 px-6 rounded-lg font-semibold hover:bg-opacity-90 transition">
    Envoyer ma demande
  </button>
  
  <p class="text-sm text-gray-500 text-center">⚡ Réponse sous 24h garantie</p>
</form>
```

### 16.6 Configuration SEO par page

**Exemple index.astro (Accueil)** :
```astro
---
import BaseLayout from '../layouts/BaseLayout.astro';

const pageTitle = "Création Site Internet Evreux | Clé en Main | Pertec.fr";
const pageDescription = "Créateur de sites web à Evreux. Formule clé en main : domaine + hébergement + création + rédaction. Pour artisans, commerces, restaurants. Devis gratuit 24h.";
---

<BaseLayout title={pageTitle} description={pageDescription}>
  <!-- Contenu de la page -->
</BaseLayout>
```

**BaseLayout.astro** :
```astro
---
interface Props {
  title: string;
  description: string;
}

const { title, description } = Astro.props;
const canonicalURL = new URL(Astro.url.pathname, Astro.site);
---

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{title}</title>
  <meta name="description" content={description}>
  <link rel="canonical" href={canonicalURL}>
  
  <!-- Open Graph -->
  <meta property="og:title" content={title}>
  <meta property="og:description" content={description}>
  <meta property="og:type" content="website">
  <meta property="og:url" content={canonicalURL}>
  <meta property="og:image" content="/images/og-image.jpg">
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content={title}>
  <meta name="twitter:description" content={description}>
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
  <script is:inline>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-XXXXXXXXXX');
  </script>
  
  <!-- Schema.org LocalBusiness -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Pertec - Création de sites internet",
      "description": "Création de sites internet clé en main à Evreux",
      "url": "https://pertec.fr",
      "telephone": "+33XXXXXXXXX",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Evreux",
        "postalCode": "27000",
        "addressCountry": "FR"
      },
      "areaServed": ["Evreux", "Louviers", "Vernon", "Eure"],
      "serviceType": ["Création site internet", "Développement web"]
    }
  </script>
</head>
<body class="font-sans antialiased">
  <slot />
</body>
</html>
```

---

## 17. CHECKLIST FINALE AVANT MISE EN LIGNE

### Contenu
- ✅ Toutes les pages créées et remplies
- ✅ Textes relus et corrigés (orthographe/grammaire)
- ✅ Images optimisées (WebP, <200KB)
- ✅ Logos et visuels en place
- ✅ Coordonnées à jour (tél, email, adresse)
- ✅ 10 articles blog rédigés
- ✅ Témoignages/portfolio (ou démo)

### SEO
- ✅ Title et meta description uniques sur toutes les pages
- ✅ H1 unique par page avec mot-clé principal
- ✅ Alt text sur toutes les images
- ✅ URLs optimisées
- ✅ Sitemap.xml généré
- ✅ Robots.txt configuré
- ✅ Schema.org LocalBusiness intégré
- ✅ Google Analytics configuré
- ✅ Google Search Console configuré
- ✅ Google Business Profile créé

### Technique
- ✅ HTTPS actif (certificat SSL)
- ✅ Responsive parfait (testé tous appareils)
- ✅ Formulaires testés et fonctionnels
- ✅ Liens internes/externes vérifiés
- ✅ Pas d'erreurs console
- ✅ PageSpeed >90 (mobile + desktop)
- ✅ Temps chargement <2s
- ✅ Compatibilité navigateurs OK

### Légal
- ✅ Mentions légales complètes
- ✅ Politique de confidentialité (RGPD)
- ✅ CGV (si applicable)
- ✅ Banner cookies (consentement)

### Fonctionnel
- ✅ Navigation fluide
- ✅ CTA visibles et cliquables
- ✅ Formulaire anti-spam (reCAPTCHA)
- ✅ Auto-réponse email configurée
- ✅ Numéros téléphone cliquables (mobile)
- ✅ Google Maps intégrées

---

## 18. POST-LANCEMENT

### Semaine 1
- Soumission sitemap à Google Search Console
- Vérification indexation pages
- Monitoring erreurs 404
- Test envoi formulaires
- Partage sur réseaux sociaux

### Mois 1
- Publication 2 articles blog
- Récolte premiers avis Google Business
- Suivi Analytics (trafic, pages populaires)
- Ajustements SEO si besoin
- Corrections bugs éventuels

### Mois 2-3
- Prospection active (phoning, emails, terrain)
- Ajout témoignages clients réels
- Enrichissement portfolio
- Netlinking (annuaires, partenariats)
- Suivi positionnement mots-clés

### Trimestre 1
- Analyse performances SEO
- A/B testing CTA
- Optimisation taux conversion
- Extension contenu blog (2 articles/mois)
- Amélioration continue

---

## ANNEXES

### A. Liste des 50 mots-clés SEO prioritaires

**Très haute priorité (top 10)** :
1. création site internet Evreux
2. site web clé en main Evreux
3. développeur web Evreux
4. agence web Evreux
5. site vitrine Evreux
6. création site internet Eure
7. site internet professionnel Evreux
8. créateur site web Normandie
9. site web tout compris
10. site internet pas cher Evreux

**Haute priorité (sectoriels locaux)** :
11. site internet artisan Evreux
12. création site web restaurant Eure
13. site vitrine commerce Evreux
14. site internet profession libérale
15. site web coiffeur Evreux
16. création site TPE Normandie
17. site internet plombier Louviers
18. site web électricien Vernon
19. développeur web pour artisan
20. site restaurant Bernay

**Moyenne priorité (informationnel)** :
21. combien coûte site internet
22. prix création site web Evreux
23. tarif site vitrine
24. devis site internet
25. créer son site internet
26. refonte site web Evreux
27. hébergement site web inclus
28. nom de domaine + création site
29. site internet responsive
30. optimisation SEO Evreux

**Longue traîne (conversionnels)** :
31. créateur site internet auto-entrepreneur Evreux
32. site web clé en main restaurant
33. développeur freelance Evreux
34. création site one-page Eure
35. site vitrine 5 pages prix
36. formule tout compris site internet
37. site web avec rédaction contenu
38. création site rapide 2 semaines
39. développeur web local Normandie
40. agence web tarifs transparents

**Géo-localisés (élargissement)** :
41. création site internet Louviers
42. développeur web Vernon
43. site vitrine Bernay
44. agence web Pont-Audemer
45. création site Val-de-Reuil
46. site internet Gisors
47. développeur web Les Andelys
48. création site Pacy-sur-Eure
49. site web Conches-en-Ouche
50. agence web Normandie

### B. Idées de contenu blog supplémentaires (20 idées)

1. "Top 10 des erreurs sur les sites d'artisans (et comment les éviter)"
2. "Votre site internet est-il mobile-friendly ? Test et conseils"
3. "SEO local : comment apparaître en premier sur Google à Evreux"
4. "Hébergement web : pourquoi je gère tout pour vous"
5. "Site vitrine ou site e-commerce : quelle différence ?"
6. "Les 5 plugins WordPress à éviter absolument"
7. "Nom de domaine : .fr, .com ou .net pour votre entreprise ?"
8. "Combien de temps pour créer un site internet professionnel ?"
9. "Design web 2025 : les tendances à suivre"
10. "Comment choisir les bonnes images pour votre site"
11. "Formulaire de contact : les champs indispensables"
12. "Google Business Profile : le guide complet pour TPE"
13. "Site internet : investissement ou dépense pour votre entreprise ?"
14. "Les avis Google : pourquoi et comment en obtenir"
15. "Maintenance site web : ce qui est vraiment nécessaire"
16. "WordPress, Wix ou site sur-mesure : mon comparatif honnête"
17. "Sécurité site web : SSL, sauvegardes, mises à jour"
18. "Comment rédiger une bonne page 'À propos'"
19. "Vitesse site web : pourquoi c'est crucial pour votre business"
20. "Analytics : comprendre les statistiques de votre site"

### C. Templates emails auto-réponse

**Email confirmation demande de devis** :
```
Objet : Votre demande de devis Pertec.fr - Confirmation de réception

Bonjour [NOM],

Merci pour votre demande de devis pour la création de votre site internet !

J'ai bien reçu votre message et je vais l'étudier avec attention. Je reviendrai vers vous sous 24h maximum avec une proposition personnalisée.

En attendant, n'hésitez pas à consulter mes réalisations : https://pertec.fr/realisations

Si vous avez des questions urgentes, vous pouvez me joindre au [TÉLÉPHONE].

À très bientôt,
[VOTRE NOM]
Pertec.fr - Création de sites internet clé en main
📞 [TÉLÉPHONE]
📧 contact@pertec.fr
🌐 https://pertec.fr
```

---

## RÉSUMÉ EXÉCUTIF

**Objectif** : Créer un site vitrine performant pour pertec.fr, optimisé SEO, attractif et générateur de leads.

**Cibles** : Artisans, commerces, restaurants, professions libérales, TPE dans l'Eure (27).

**Proposition de valeur** : Site internet clé en main (domaine + hébergement + création + rédaction) à tarif transparent.

**Technologie recommandée** : Astro + Tailwind CSS (performances et SEO maximaux).

**Pages principales** : Accueil, Services, 6 pages sectorielles, Portfolio, Blog, Contact, Zone intervention, À propos.

**SEO** : Focus sur référencement local Evreux/Eure, 50 mots-clés ciblés, Google Business Profile.

**Délai** : 4-6 semaines de la conception à la mise en ligne.

**Maintenance** : ~100€/an (domaine + hébergement).

---

**Date de rédaction** : Janvier 2025
**Version** : 1.0
**Statut** : Prêt pour développement avec Claude Code

---

FIN DU CAHIER DES CHARGES

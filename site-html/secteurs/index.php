<?php
$pageTitle = 'Création de site internet par secteur d\'activité | Pertec.fr';
$pageDescription = 'Sites internet sur mesure pour artisans, commerces, restaurants, auto-écoles, thérapeutes et professionnels de Normandie. Devis gratuit.';
$canonicalUrl = '/secteurs/';
$activeMenu = '';

require_once dirname(__DIR__) . '/includes/header.php';

$secteurs = [
    ['slug' => 'artisan', 'label' => 'Artisans', 'desc' => 'Plombiers, électriciens, maçons, menuisiers...'],
    ['slug' => 'restaurant', 'label' => 'Restaurants', 'desc' => 'Carte en ligne, réservations, horaires'],
    ['slug' => 'commerces', 'label' => 'Commerces', 'desc' => 'Boutiques, épiceries, fleuristes...'],
    ['slug' => 'coiffeur', 'label' => 'Coiffeurs', 'desc' => 'Salons de coiffure et barbiers'],
    ['slug' => 'professions-liberales', 'label' => 'Professions libérales', 'desc' => 'Avocats, notaires, comptables, architectes'],
    ['slug' => 'therapeute', 'label' => 'Thérapeutes', 'desc' => 'Kinés, ostéopathes, psychologues...'],
    ['slug' => 'plombier', 'label' => 'Plombiers', 'desc' => 'Chauffagistes, dépannage, entretien'],
    ['slug' => 'gite', 'label' => 'Gîtes et chambres d\'hôtes', 'desc' => 'Hébergements touristiques'],
    ['slug' => 'auto-ecole', 'label' => 'Auto-écoles', 'desc' => 'Permis, planning, inscription en ligne'],
    ['slug' => 'securite-privee', 'label' => 'Sécurité privée', 'desc' => 'Sociétés de gardiennage et surveillance'],
    ['slug' => 'traiteur', 'label' => 'Traiteurs', 'desc' => 'Événementiel, réceptions, buffets'],
    ['slug' => 'pompes-funebres', 'label' => 'Pompes funèbres', 'desc' => 'Services funéraires'],
    ['slug' => 'eglise', 'label' => 'Églises et paroisses', 'desc' => 'Sites pour communautés religieuses'],
    ['slug' => 'agriculteur', 'label' => 'Agriculteurs', 'desc' => 'Producteurs locaux, vente directe'],
    ['slug' => 'club-sportif', 'label' => 'Clubs sportifs', 'desc' => 'Associations, planning, inscriptions'],
    ['slug' => 'artiste', 'label' => 'Artistes', 'desc' => 'Portfolio, galerie, événements'],
    ['slug' => 'assistante-maternelle', 'label' => 'Assistantes maternelles', 'desc' => 'Présentation, disponibilités, contact'],
];
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Secteurs
      </div>
      <h1 data-animate>Sites internet par <span class="gradient-text">secteur d'activité</span></h1>
      <p data-animate>Chaque métier a ses besoins spécifiques. Découvrez nos solutions adaptées à votre activité.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="secteurs-grid">
<?php foreach ($secteurs as $s): ?>
        <a href="/secteurs/<?= $s['slug'] ?>" class="secteur-card glass" data-animate>
          <div class="secteur-card-icon">
            <img src="<?= asset('/images/secteurs/' . $s['slug'] . '.svg') ?>" alt="" loading="lazy" width="64" height="64">
          </div>
          <div class="secteur-card-content">
            <h2><?= htmlspecialchars($s['label']) ?></h2>
            <p><?= htmlspecialchars($s['desc']) ?></p>
            <span class="btn-link">En savoir plus →</span>
          </div>
        </a>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Votre secteur n'est pas listé ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Je crée des sites pour tous types d'activités. Contactez-moi pour en discuter.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis</a>
          <a href="/tarifs" class="btn btn-secondary">Voir les tarifs</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

<?php
/**
 * Pertec - Page Tarifs
 * Modèle abonnement 24 mois
 */

$tarifs = require __DIR__ . '/config/tarifs.php';
$formules = $tarifs['formule']['options'];

$pageTitle = 'Tarifs Création Site Internet | À partir de 59€/mois | Pertec.fr';
$pageDescription = 'Créez votre site internet à partir de 59€/mois sur 24 mois. Site vitrine, site complet ou e-commerce. Tout inclus : design, hébergement, maintenance.';
$canonicalUrl = '/tarifs';
$activeMenu = 'tarifs';

require_once __DIR__ . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Tarifs transparents
      </div>
      <h1 data-animate>Votre site internet <span class="gradient-text">tout compris</span></h1>
      <p data-animate>Abonnement 24 mois, puis maintenance. Pas de frais cachés, pas de surprise.</p>
    </div>
  </section>

  <!-- Grille tarifaire -->
  <section>
    <div class="container">
      <div class="pricing-grid">
        <?php foreach ($formules as $id => $formule): ?>
        <div class="pricing-card glass<?= $formule['badge'] ? ' pricing-featured' : '' ?>" data-animate>
          <?php if ($formule['badge']): ?>
          <div class="pricing-badge"><?= htmlspecialchars($formule['badge']) ?></div>
          <?php endif; ?>
          <h3 class="pricing-title"><?= htmlspecialchars($formule['label']) ?></h3>
          <p class="pricing-desc"><?= htmlspecialchars($formule['description']) ?></p>
          <div class="pricing-price">
            <span class="pricing-amount"><?= $formule['mensuel'] ?></span>
            <span class="pricing-unit">€/mois</span>
          </div>
          <p class="pricing-engagement">Engagement 24 mois</p>
          <a href="/contact" class="btn <?= $formule['badge'] ? 'btn-primary' : 'btn-secondary' ?>">Demander un devis</a>
        </div>
        <?php endforeach; ?>
      </div>

      <p class="pricing-note" data-animate>
        Après 24 mois : maintenance à <strong><?= $tarifs['maintenance']['mensuel'] ?>€/mois</strong>, résiliable à tout moment.<br>
        <small style="color: var(--gray-500);"><?= htmlspecialchars($tarifs['config']['tva_mention']) ?></small>
      </p>
    </div>
  </section>

  <!-- Comparateur -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Comparez les <span class="gradient-text">formules</span></h2>
      </div>

      <div class="comparateur-wrapper" data-animate>
        <table class="comparateur">
          <thead>
            <tr>
              <th></th>
              <?php foreach ($formules as $id => $formule): ?>
              <th class="<?= $formule['badge'] ? 'col-featured' : '' ?>">
                <?= htmlspecialchars($formule['label']) ?>
                <span class="comparateur-price"><?= $formule['mensuel'] ?>€/mois</span>
              </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tarifs['comparatif'] as $feature => $values): ?>
            <tr>
              <td class="feature-name"><?= htmlspecialchars($feature) ?></td>
              <?php foreach (['vitrine', 'complet', 'ecommerce'] as $plan): ?>
              <td class="<?= $formules[$plan]['badge'] ? 'col-featured' : '' ?>">
                <?php
                $val = $values[$plan];
                if ($val === true) {
                    echo '<svg class="check-icon" width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                } elseif ($val === false) {
                    echo '<span class="no-feature">—</span>';
                } else {
                    echo '<span class="feature-text">' . htmlspecialchars($val) . '</span>';
                }
                ?>
              </td>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Ce qui est inclus -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tout est <span class="gradient-text">inclus</span></h2>
        <p class="section-subtitle" data-animate>Dans chaque formule, sans supplément.</p>
      </div>

      <div class="inclus-grid">
        <?php foreach ($tarifs['inclus'] as $item): ?>
        <div class="inclus-item glass" data-animate>
          <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span><?= htmlspecialchars($item) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Options mensuelles -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Options <span class="gradient-text">mensuelles</span></h2>
        <p class="section-subtitle" data-animate>Ajoutez des services récurrents à votre abonnement.</p>
      </div>

      <div class="features-grid">
        <?php foreach ($tarifs['options_recurrentes']['options'] as $id => $opt): ?>
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($opt['label']) ?></h3>
          <p><?= htmlspecialchars($opt['description']) ?></p>
          <p class="option-pour"><em><?= htmlspecialchars($opt['pour']) ?></em></p>
          <p class="option-price">+ <?= $opt['mensuel'] ?> €/mois</p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Options uniques -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Options <span class="gradient-text">à la carte</span></h2>
        <p class="section-subtitle" data-animate>Prestations ponctuelles pour compléter votre site.</p>
      </div>

      <div class="features-grid">
        <?php foreach ($tarifs['options_uniques']['options'] as $id => $opt): ?>
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($opt['label']) ?></h3>
          <p><?= htmlspecialchars($opt['description']) ?></p>
          <p class="option-price"><?= $opt['prix'] ?> €<?= isset($opt['unite']) ? ' <small>' . htmlspecialchars($opt['unite']) . '</small>' : '' ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Comment ça marche -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Comment ça <span class="gradient-text">fonctionne</span></h2>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="how-it-works">
          <div class="how-step">
            <div class="how-number">1</div>
            <div>
              <h4>On échange</h4>
              <p>Vous me décrivez votre projet, je vous envoie un devis personnalisé sous 24h.</p>
            </div>
          </div>
          <div class="how-step">
            <div class="how-number">2</div>
            <div>
              <h4>Je crée votre site</h4>
              <p>Design, rédaction, développement. Vous validez chaque étape. Mise en ligne sous 3-4 semaines.</p>
            </div>
          </div>
          <div class="how-step">
            <div class="how-number">3</div>
            <div>
              <h4>Vous payez chaque mois</h4>
              <p>Prélèvement mensuel pendant 24 mois. Hébergement et modifications inclus.</p>
            </div>
          </div>
          <div class="how-step">
            <div class="how-number">4</div>
            <div>
              <h4>Après 24 mois</h4>
              <p>Vous passez en maintenance (<?= $tarifs['maintenance']['mensuel'] ?>€/mois) ou vous récupérez votre site.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Questions <span class="gradient-text">fréquentes</span></h2>
      </div>

      <div class="faq-list">
        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Pourquoi un abonnement plutôt qu'un paiement unique ?</span>
            <span class="faq-toggle">+</span>
          </button>
          <div class="faq-answer">
            <p>L'abonnement rend le site accessible à tous les budgets : 59€/mois au lieu de 1400€ d'un coup. Et tout est inclus : hébergement, maintenance, modifications. Pas de mauvaise surprise.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Que se passe-t-il après 24 mois ?</span>
            <span class="faq-toggle">+</span>
          </button>
          <div class="faq-answer">
            <p>Vous passez en maintenance à <?= $tarifs['maintenance']['mensuel'] ?>€/mois (hébergement + modifications). Résiliable à tout moment. Vous pouvez aussi récupérer votre site et l'héberger ailleurs.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Puis-je résilier avant 24 mois ?</span>
            <span class="faq-toggle">+</span>
          </button>
          <div class="faq-answer">
            <p>L'engagement de 24 mois couvre le coût de création du site. Une résiliation anticipée implique le paiement des mensualités restantes.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Qu'est-ce qui différencie Vitrine et Complet ?</span>
            <span class="faq-toggle">+</span>
          </button>
          <div class="faq-answer">
            <p><strong>Vitrine</strong> (59€/mois) : jusqu'à 5 pages, idéal pour présenter votre activité.<br>
            <strong>Complet</strong> (149€/mois) : jusqu'à 10 pages + blog, galerie photos et réservation en ligne inclus.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Les modifications sont-elles vraiment incluses ?</span>
            <span class="faq-toggle">+</span>
          </button>
          <div class="faq-answer">
            <p>Oui, illimitées. Changement de texte, ajout de photos, mise à jour des tarifs... Je m'en occupe sous 48h. Seules les refontes complètes ou ajouts de fonctionnalités majeures sont en supplément.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Le site m'appartient-il ?</span>
            <span class="faq-toggle">+</span>
          </button>
          <div class="faq-answer">
            <p>Oui. Après les 24 mois, vous êtes propriétaire du site. Vous pouvez continuer en maintenance avec moi ou récupérer les fichiers pour les héberger ailleurs.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Prêt à <span class="gradient-text">démarrer</span> ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit et personnalisé sous 24h.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

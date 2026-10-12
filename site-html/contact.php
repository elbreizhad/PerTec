<?php
session_start();
require_once __DIR__ . '/includes/turnstile.php';

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$turnstileSiteKey = get_turnstile_site_key();

$pageTitle = 'Contact | Demande de Devis Gratuit | Pertec.fr';
$pageDescription = 'Contactez-moi pour votre projet de création de site internet à Évreux et en Normandie. Devis gratuit sous 24h, sans engagement.';
$canonicalUrl = '/contact';
$activeMenu = 'contact';
$extraHead = $turnstileSiteKey ? '  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>' . "\n" : '';
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Contact
      </div>
      <h1 data-animate>Parlons de <span class="gradient-text">votre projet</span></h1>
      <p data-animate>Une question ? Un projet en tête ? Je vous réponds sous 24h maximum.</p>
    </div>
  </section>

  <!-- Contact Section -->
  <section>
    <div class="container">
      <div class="contact-grid">
        <!-- Info -->
        <div class="contact-info">
          <a href="mailto:contact@pertec.fr" class="contact-item glass" data-animate>
            <div class="contact-icon">📧</div>
            <div>
              <p style="color: var(--gray-400); font-size: 0.875rem; margin-bottom: 0.25rem;">Email</p>
              <p style="font-weight: 600;">contact@pertec.fr</p>
            </div>
          </a>

          <div class="contact-item glass" data-animate>
            <div class="contact-icon">📍</div>
            <div>
              <p style="color: var(--gray-400); font-size: 0.875rem; margin-bottom: 0.25rem;">Localisation</p>
              <p style="font-weight: 600;">Évreux, Normandie</p>
            </div>
          </div>

          <a href="<?= GOOGLE_BUSINESS_URL ?>" target="_blank" rel="noopener" class="contact-item glass" data-animate>
            <div class="contact-icon">⭐</div>
            <div>
              <p style="color: var(--gray-400); font-size: 0.875rem; margin-bottom: 0.25rem;">Google</p>
              <p style="font-weight: 600;">Voir la fiche et les avis</p>
            </div>
          </a>

          <div class="contact-item glass" data-animate>
            <div class="contact-icon">🕒</div>
            <div>
              <p style="color: var(--gray-400); font-size: 0.875rem; margin-bottom: 0.25rem;">Disponibilité</p>
              <p style="font-weight: 600;">Lun - Ven · 9h - 18h</p>
            </div>
          </div>

          <div style="display: flex; gap: 1rem; margin-top: 1rem;" data-animate>
            <div class="glass" style="padding: 0.75rem 1rem; border-radius: 50px; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; border-color: rgba(34, 197, 94, 0.3);">
              <svg width="18" height="18" viewBox="0 0 20 20" fill="#22c55e"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              Réponse sous 24h
            </div>
            <div class="glass" style="padding: 0.75rem 1rem; border-radius: 50px; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
              <svg width="18" height="18" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              Devis gratuit
            </div>
          </div>
        </div>

        <!-- Form -->
        <div class="form-box glass-strong" data-animate>
          <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Demander un devis gratuit</h2>

          <form id="contact-form" action="contact-handler.php" method="POST">
            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <!-- Honeypot anti-spam -->
            <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

            <div class="form-grid">
              <div class="form-group">
                <label for="nom">Nom et prénom <span>*</span></label>
                <input type="text" id="nom" name="nom" placeholder="Jean Dupont" required>
              </div>

              <div class="form-group">
                <label for="entreprise">Entreprise / Activité <span>*</span></label>
                <input type="text" id="entreprise" name="entreprise" placeholder="Plomberie Dupont" required>
              </div>

              <div class="form-group">
                <label for="email">Email <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="jean@exemple.fr" required>
              </div>

              <div class="form-group">
                <label for="telephone">Téléphone <span>*</span></label>
                <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78" required>
              </div>

              <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" placeholder="Votre ville">
              </div>

              <div class="form-group">
                <label for="projet">Type de projet</label>
                <select id="projet" name="projet">
                  <option value="">Sélectionnez</option>
                  <option value="creation">Création de site</option>
                  <option value="refonte">Refonte de site</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="autre">Autre</option>
                </select>
              </div>

              <div class="form-group full">
                <label for="message">Décrivez votre projet <span>*</span></label>
                <textarea id="message" name="message" placeholder="Parlez-moi de votre projet, vos besoins, vos objectifs..." required></textarea>
              </div>

              <div class="form-group full checkbox-group">
                <input type="checkbox" id="consent" name="consent" required>
                <label for="consent">J'accepte d'être recontacté concernant ma demande <span>*</span></label>
              </div>

              <?php if ($turnstileSiteKey): ?>
              <div class="form-group full">
                <div class="cf-turnstile" data-sitekey="<?= htmlspecialchars($turnstileSiteKey) ?>" data-theme="dark"></div>
              </div>
              <?php endif; ?>

              <div class="form-group full">
                <button type="submit" class="btn btn-primary form-submit">Envoyer ma demande</button>
              </div>

              <div class="form-group full">
                <p class="form-trust">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  Réponse sous 24h · Devis gratuit et sans engagement
                </p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Map -->
  <section>
    <div class="container">
      <div class="glass-strong" style="border-radius: 1.5rem; overflow: hidden;" data-animate>
        <div style="aspect-ratio: 21/9; background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.15)); display: flex; align-items: center; justify-content: center; position: relative;">
          <div class="grid-pattern" style="position: absolute; inset: 0; opacity: 0.5;"></div>
          <div style="text-align: center; position: relative; z-index: 1;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📍</div>
            <p style="font-size: 1.5rem; font-weight: 700;">Évreux, Normandie</p>
            <p style="color: var(--gray-400);">Eure et environs</p>
            <p style="color: var(--gray-500); font-size: 0.875rem; margin-top: 1rem;">Échanges en présentiel ou à distance selon vos préférences</p>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

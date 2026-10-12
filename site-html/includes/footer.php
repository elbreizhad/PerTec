  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <a href="/">
            <img src="<?= asset('/images/logo-pertec.svg') ?>" alt="Pertec" style="height: 40px;">
          </a>
          <p>Création de sites internet clé en main pour artisans et commerces. Basé à Évreux, j'accompagne les entrepreneurs de Normandie.</p>
        </div>

        <div class="footer-col">
          <h4>Secteurs</h4>
          <ul>
            <li><a href="/secteurs/artisan">Artisans</a></li>
            <li><a href="/secteurs/restaurant">Restaurants</a></li>
            <li><a href="/secteurs/commerces">Commerces</a></li>
            <li><a href="/secteurs/professions-liberales">Professions libérales</a></li>
            <li><a href="/secteurs/securite-privee">Sécurité privée</a></li>
            <li><a href="/services">Tous les services</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Entreprise</h4>
          <ul>
            <li><a href="/a-propos">À propos</a></li>
            <li><a href="/realisations">Réalisations</a></li>
            <li><a href="/tarifs">Tarifs</a></li>
            <li><a href="/zone-intervention">Zone d'intervention</a></li>
            <li><a href="/contact">Contact</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Contact</h4>
          <ul>
            <li>📍 Évreux, Normandie</li>
            <li>📧 contact@pertec.fr</li>
            <li>🕒 Lun - Ven : 9h - 18h</li>
            <li><a href="<?= GOOGLE_BUSINESS_URL ?>" target="_blank" rel="noopener">⭐ Avis et fiche Google</a></li>
          </ul>
          <a href="/contact" class="btn btn-primary" style="margin-top: 1rem; padding: 0.75rem 1.5rem; font-size: 0.875rem;">Demander un devis</a>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© <?= date('Y') ?> Pertec.fr - Création de sites internet</p>
        <div class="footer-legal">
          <a href="/mentions-legales">Mentions légales</a>
          <a href="/politique-confidentialite">Confidentialité</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scroll to top -->
  <button class="scroll-top" aria-label="Retour en haut">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
    </svg>
  </button>

  <!-- JavaScript -->
  <script src="<?= asset('/js/main.js') ?>"></script>
<?= $extraScripts ?? '' ?>
</body>
</html>

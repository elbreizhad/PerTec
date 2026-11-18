<?php
$pageTitle = 'Politique de Confidentialité | Pertec.fr';
$pageDescription = 'Politique de confidentialité et protection des données personnelles du site Pertec.fr - Conformité RGPD.';
$canonicalUrl = '/politique-confidentialite';
$activeMenu = '';

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        RGPD
      </div>
      <h1 data-animate>Politique de <span class="gradient-text">confidentialité</span></h1>
      <p data-animate>Protection de vos données personnelles - Conformité RGPD</p>
    </div>
  </section>

  <!-- Legal Content -->
  <section>
    <div class="container">
      <div class="legal-content">
        <div class="legal-section glass" data-animate>
          <h2>Introduction</h2>
          <p>
            La présente politique de confidentialité a pour objet d'informer les utilisateurs du site pertec.fr sur la manière dont leurs données personnelles sont collectées et traitées.
          </p>
          <p>
            Pertec s'engage à respecter la réglementation en vigueur applicable au traitement de données à caractère personnel et, en particulier, le Règlement (UE) 2016/679 du Parlement européen et du Conseil du 27 avril 2016 (RGPD).
          </p>
          <p>
            En utilisant ce site, vous acceptez les termes de cette politique de confidentialité.
          </p>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Données collectées</h2>
          <p>
            Nous collectons les données personnelles suivantes lorsque vous utilisez notre site :
          </p>
          <ul>
            <li><strong>Données d'identification :</strong> nom, prénom, adresse email, numéro de téléphone</li>
            <li><strong>Données professionnelles :</strong> nom de l'entreprise, activité, ville</li>
            <li><strong>Données de navigation :</strong> adresse IP, type de navigateur, pages visitées, durée de visite</li>
            <li><strong>Données de communication :</strong> contenu des messages envoyés via le formulaire de contact</li>
          </ul>
          <p>
            Ces données sont collectées lorsque vous remplissez le formulaire de contact, demandez un devis ou vous inscrivez à notre newsletter.
          </p>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Finalité du traitement</h2>
          <p>
            Les données personnelles collectées sont utilisées pour :
          </p>
          <ul>
            <li>Répondre à vos demandes de contact et de devis</li>
            <li>Vous fournir les services demandés</li>
            <li>Vous envoyer des informations commerciales (avec votre consentement)</li>
            <li>Améliorer nos services et l'expérience utilisateur du site</li>
            <li>Établir des statistiques de fréquentation</li>
            <li>Respecter nos obligations légales</li>
          </ul>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Durée de conservation</h2>
          <p>
            Vos données personnelles sont conservées pendant une durée qui n'excède pas la durée nécessaire aux finalités pour lesquelles elles sont collectées :
          </p>
          <ul>
            <li><strong>Données de contact :</strong> 3 ans à compter du dernier contact</li>
            <li><strong>Données clients :</strong> durée de la relation commerciale + 5 ans (obligations légales)</li>
            <li><strong>Données de navigation :</strong> 13 mois maximum</li>
            <li><strong>Newsletter :</strong> jusqu'à votre désinscription</li>
          </ul>
          <p>
            À l'expiration de ces délais, vos données sont supprimées ou anonymisées.
          </p>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Cookies</h2>
          <p>
            Notre site utilise uniquement des cookies strictement nécessaires au fonctionnement du site. Un cookie est un petit fichier texte stocké sur votre appareil.
          </p>
          <p><strong>Cookies utilisés :</strong></p>
          <ul>
            <li><strong>Cookies de session :</strong> nécessaires au fonctionnement des formulaires (protection CSRF, anti-spam Cloudflare Turnstile)</li>
          </ul>
          <p>
            Nous n'utilisons pas de cookies de pistage publicitaire ni de cookies d'analyse d'audience. Les cookies de session sont automatiquement supprimés à la fermeture de votre navigateur.
          </p>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Mesure d'audience</h2>
          <p>
            Pour améliorer le site, nous mesurons comment il est utilisé, directement sur notre serveur, sans cookie de mesure et sans outil tiers : pages consultées dans l'ordre, liens et boutons cliqués, défilement et temps passé sur chaque page, site de provenance (par exemple « Google »), type d'appareil et de navigateur, et le fait qu'un formulaire ait été commencé ou envoyé. Le contenu de ce que vous saisissez n'est jamais enregistré.
          </p>
          <p>
            Votre adresse IP n'est pas enregistrée. Pour regrouper les pages d'une même visite, une empreinte chiffrée est calculée à partir de l'adresse IP et du navigateur avec une clé qui change chaque jour : elle ne permet pas de vous identifier ni de relier vos visites d'un jour à l'autre. Ces données servent uniquement à des statistiques consultées par l'éditeur du site, ne sont transmises à personne et sont effacées au bout de 90 jours. Cette mesure entre dans le cadre de l'exemption de consentement prévue par la CNIL.
          </p>
          <p>
            Vous pouvez vous y opposer à tout moment : le réglage « Ne pas me suivre » (Do Not Track ou Global Privacy Control) de votre navigateur est respecté, ou cliquez ci-dessous (un petit cookie retient votre choix pendant 13 mois).
          </p>
          <p>
            <button type="button" class="btn btn-secondary" id="suivi-opposition">Ne plus mesurer ma navigation</button>
            <span id="suivi-opposition-etat" role="status" style="margin-left: 0.75rem;"></span>
          </p>
          <script>
            (function () {
              var bouton = document.getElementById('suivi-opposition');
              var etat = document.getElementById('suivi-opposition-etat');
              function afficher() {
                var refuse = /(^|; )pertec_nosuivi=1/.test(document.cookie);
                bouton.textContent = refuse ? 'Accepter à nouveau la mesure' : 'Ne plus mesurer ma navigation';
                etat.textContent = refuse ? 'Votre navigation n\'est plus mesurée sur ce navigateur.' : '';
              }
              bouton.addEventListener('click', function () {
                var refuse = /(^|; )pertec_nosuivi=1/.test(document.cookie);
                document.cookie = 'pertec_nosuivi=' + (refuse ? '; max-age=0' : '1; max-age=' + 13 * 30 * 86400) + '; path=/; SameSite=Lax; Secure';
                afficher();
              });
              afficher();
            })();
          </script>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Vos droits</h2>
          <p>
            Conformément au RGPD, vous disposez des droits suivants concernant vos données personnelles :
          </p>
          <ul>
            <li><strong>Droit d'accès :</strong> obtenir la confirmation que vos données sont traitées et en recevoir une copie</li>
            <li><strong>Droit de rectification :</strong> demander la correction de données inexactes ou incomplètes</li>
            <li><strong>Droit à l'effacement :</strong> demander la suppression de vos données dans certains cas</li>
            <li><strong>Droit à la limitation :</strong> demander la limitation du traitement de vos données</li>
            <li><strong>Droit à la portabilité :</strong> recevoir vos données dans un format structuré et lisible</li>
            <li><strong>Droit d'opposition :</strong> vous opposer au traitement de vos données</li>
            <li><strong>Droit de retrait du consentement :</strong> retirer votre consentement à tout moment</li>
          </ul>
          <p>
            Pour exercer ces droits, contactez-nous à l'adresse : <strong>contact@pertec.fr</strong>
          </p>
          <p>
            Vous disposez également du droit d'introduire une réclamation auprès de la CNIL (Commission Nationale de l'Informatique et des Libertés).
          </p>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Sécurité des données</h2>
          <p>
            Nous mettons en œuvre toutes les mesures techniques et organisationnelles appropriées pour assurer la sécurité de vos données personnelles :
          </p>
          <ul>
            <li>Chiffrement SSL des données transmises</li>
            <li>Accès restreint aux données personnelles</li>
            <li>Sauvegardes régulières et sécurisées</li>
            <li>Mise à jour régulière des systèmes de sécurité</li>
          </ul>
        </div>

        <div class="legal-section glass" data-animate>
          <h2>Contact</h2>
          <p>
            Pour toute question concernant cette politique de confidentialité ou pour exercer vos droits, vous pouvez nous contacter :
          </p>
          <p>
            <strong>Par email :</strong> contact@pertec.fr<br>
            <strong>Par courrier :</strong> Pertec - Évreux, 27000, France
          </p>
          <p>
            <strong>Dernière mise à jour :</strong> <?= date('F Y') ?>
          </p>
        </div>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

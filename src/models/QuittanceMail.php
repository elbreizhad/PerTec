<?php
declare(strict_types=1);

/**
 * Modèle de l'email d'envoi de quittance (objet + message), modifiable dans
 * Paramètres et stocké en base (réglages mail_quittance_subject / _body).
 * Les champs entre accolades sont remplacés automatiquement.
 */
class QuittanceMail
{
    public const DEFAULT_SUBJECT = 'Quittance de loyer — {periode}';
    public const DEFAULT_BODY = "Bonjour {prenom},\n\n"
        . "Veuillez trouver ci-joint votre quittance de loyer pour {periode} "
        . "(montant réglé : {montant}).\n\n"
        . "Je reste à votre disposition pour toute question.\n\n"
        . "Cordialement,\n{bailleur}";

    /** Champs disponibles => description (affichée dans Paramètres). */
    public static function tags(): array
    {
        return [
            '{prenom}'   => 'prénom du locataire',
            '{nom}'      => 'nom du locataire',
            '{locataire}' => 'prénom + nom du locataire',
            '{periode}'  => 'mois concerné (ex. Octobre 2026)',
            '{montant}'  => 'total réglé',
            '{loyer}'    => 'loyer hors charges',
            '{charges}'  => 'charges',
            '{date_paiement}' => 'date du paiement',
            '{numero}'   => 'numéro de quittance',
            '{logement}' => 'adresse du logement',
            '{bailleur}' => 'votre nom (Paramètres)',
        ];
    }

    public static function subjectTemplate(array $s): string
    {
        return trim((string) ($s['mail_quittance_subject'] ?? '')) ?: self::DEFAULT_SUBJECT;
    }

    public static function bodyTemplate(array $s): string
    {
        return trim((string) ($s['mail_quittance_body'] ?? '')) ?: self::DEFAULT_BODY;
    }

    /** Valeurs des champs pour une échéance donnée. */
    public static function vars(array $payment, array $lease, array $s): array
    {
        $rent = (float) $payment['amount_rent'];
        $charges = (float) $payment['amount_charges'];
        $logement = trim(($lease['address'] ?? '') . ', ' . ($lease['postal_code'] ?? '') . ' ' . ($lease['city'] ?? ''), ', ');
        return [
            '{prenom}'    => (string) $lease['first_name'],
            '{nom}'       => (string) $lease['last_name'],
            '{locataire}' => trim($lease['first_name'] . ' ' . $lease['last_name']),
            '{periode}'   => ucfirst(moisFr((int) $payment['period_month'])) . ' ' . $payment['period_year'],
            '{montant}'   => euros($rent + $charges),
            '{loyer}'     => euros($rent),
            '{charges}'   => euros($charges),
            '{date_paiement}' => fdate($payment['paid_date'] ?? null),
            '{numero}'    => (string) ($payment['receipt_number'] ?? ''),
            '{logement}'  => $logement ?: (string) ($lease['property_label'] ?? ''),
            '{bailleur}'  => (string) ($s['landlord_name'] ?? ''),
        ];
    }

    /** Valeurs d'exemple pour l'aperçu dans Paramètres. */
    public static function sampleVars(array $s): array
    {
        return [
            '{prenom}' => 'Marie', '{nom}' => 'Martin', '{locataire}' => 'Marie Martin',
            '{periode}' => ucfirst(moisFr((int) date('n'))) . ' ' . date('Y'),
            '{montant}' => euros(650), '{loyer}' => euros(600), '{charges}' => euros(50),
            '{date_paiement}' => date('d/m/Y'), '{numero}' => date('Y') . '-0001',
            '{logement}' => '12 rue de la Paix, 29000 Quimper',
            '{bailleur}' => (string) ($s['landlord_name'] ?? '') ?: 'Votre nom',
        ];
    }

    public static function render(string $template, array $vars): string
    {
        return strtr($template, $vars);
    }

    /** Nom du fichier PDF joint (ex. Quittance-Octobre-2026.pdf). */
    public static function attachmentName(array $payment): string
    {
        $name = 'Quittance-' . ucfirst(moisFr((int) $payment['period_month'])) . '-' . $payment['period_year'];
        $ascii = strtr($name, ['é' => 'e', 'è' => 'e', 'ê' => 'e', 'û' => 'u', 'ô' => 'o', 'à' => 'a', 'ç' => 'c', 'É' => 'E']);
        return preg_replace('/[^A-Za-z0-9-]/', '', $ascii) . '.pdf';
    }

    /** Adresse qui reçoit la copie des envois (votre email). */
    public static function copyAddress(array $s): string
    {
        return trim((string) ($s['landlord_email'] ?? '')) ?: trim((string) ($s['mail_from'] ?? ''));
    }
}

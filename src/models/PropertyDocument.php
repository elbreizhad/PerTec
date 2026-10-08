<?php
declare(strict_types=1);

/**
 * Documents légaux d'un logement, remis au locataire avec le bail
 * (dossier de diagnostic technique, art. 3-3 de la loi du 6 juillet 1989,
 * notice d'information, extraits du règlement de copropriété…).
 * Fichiers stockés en base (aucun dossier serveur à protéger ni à préserver).
 */
class PropertyDocument
{
    public const MAX_SIZE = 10 * 1024 * 1024; // 10 Mo
    public const ALLOWED = ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'];

    /** type => [libellé, aide, obligatoire toujours ?, validité en mois (null = sans limite)] */
    public static function types(): array
    {
        return [
            'dpe'         => ['DPE — Diagnostic de performance énergétique', 'Obligatoire. Valable 10 ans.', true, 120],
            'erp'         => ['ERP — État des risques et pollutions', 'Obligatoire. Doit dater de moins de 6 mois à la signature du bail.', true, 6],
            'notice'      => ['Notice d\'information', 'Obligatoire : droits et obligations des locataires et des bailleurs (modèle de l\'arrêté du 29 mai 2015).', true, null],
            'electricite' => ['État de l\'installation intérieure d\'électricité', 'Obligatoire si l\'installation a plus de 15 ans. Valable 6 ans.', false, 72],
            'gaz'         => ['État de l\'installation intérieure de gaz', 'Obligatoire si l\'installation de gaz a plus de 15 ans. Valable 6 ans.', false, 72],
            'crep'        => ['CREP — Constat de risque d\'exposition au plomb', 'Obligatoire si le logement a été construit avant 1949.', false, null],
            'amiante'     => ['État d\'amiante (parties privatives)', 'Permis de construire antérieur au 1er juillet 1997 : à tenir à disposition du locataire.', false, null],
            'bruit'       => ['Diagnostic bruit', 'Si le logement est situé dans une zone d\'exposition au bruit d\'un aéroport.', false, null],
            'copro'       => ['Extraits du règlement de copropriété', 'Si le logement est en copropriété.', false, null],
            'autre'       => ['Autre document', 'Tout autre document à remettre au locataire.', false, null],
        ];
    }

    public static function label(array $doc): string
    {
        $t = self::types()[$doc['doc_type']][0] ?? 'Document';
        return $doc['doc_type'] === 'autre' && !empty($doc['title']) ? $doc['title'] : $t;
    }

    /** Liste (sans le contenu binaire), la plus récente d'abord. */
    public static function forProperty(int $propertyId): array
    {
        try {
            return Database::all(
                'SELECT id, property_id, doc_type, title, doc_date, filename, mime, size, uploaded_at
                 FROM property_documents WHERE property_id = ? ORDER BY doc_type, doc_date DESC, id DESC',
                [$propertyId]
            );
        } catch (Throwable $e) {
            return []; // migration pas encore appliquée
        }
    }

    /** Document le plus récent de chaque type (« autre » : tous). */
    public static function current(int $propertyId): array
    {
        $out = [];
        foreach (self::forProperty($propertyId) as $d) {
            if ($d['doc_type'] === 'autre') { $out[] = $d; continue; }
            if (!isset($out[$d['doc_type']])) $out[$d['doc_type']] = $d;
        }
        return array_values($out);
    }

    public static function find(int $id): ?array
    {
        return Database::one('SELECT * FROM property_documents WHERE id = ?', [$id]);
    }

    /** Date de fin de validité (ou null si sans limite / date inconnue). */
    public static function expiry(array $doc): ?string
    {
        $months = self::types()[$doc['doc_type']][3] ?? null;
        if (!$months || empty($doc['doc_date'])) return null;
        return date('Y-m-d', strtotime($doc['doc_date'] . " +$months months"));
    }

    /**
     * Enregistre un fichier envoyé ($_FILES['…']).
     * @throws RuntimeException message lisible
     */
    public static function store(int $propertyId, string $type, ?string $title, ?string $date, array $file): int
    {
        if (!isset(self::types()[$type])) throw new RuntimeException('Type de document inconnu.');
        $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE || (int) ($file['size'] ?? 0) > self::MAX_SIZE) {
            throw new RuntimeException('Fichier trop volumineux (10 Mo maximum).');
        }
        if ($err !== UPLOAD_ERR_OK || !is_uploaded_file((string) $file['tmp_name'])) {
            throw new RuntimeException('Aucun fichier reçu. Choisissez un fichier PDF ou une photo.');
        }
        $mime = (string) (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) throw new RuntimeException('Format non accepté : PDF, JPG ou PNG uniquement.');
        $name = preg_replace('/[^\w.\- ]+/u', '_', basename((string) $file['name'])) ?: ('document.' . self::ALLOWED[$mime]);
        return Database::insert('property_documents', [
            'property_id' => $propertyId,
            'doc_type'    => $type,
            'title'       => $title !== null && trim($title) !== '' ? trim(mb_substr($title, 0, 255)) : null,
            'doc_date'    => $date ?: null,
            'filename'    => mb_substr($name, 0, 255),
            'mime'        => $mime,
            'size'        => (int) $file['size'],
            'content'     => (string) file_get_contents($file['tmp_name']),
        ]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM property_documents WHERE id = ?', [$id]);
    }

    /**
     * Points à vérifier pour la remise des documents au locataire.
     * @param string|null $signDate date de signature du bail (contrôle ERP < 6 mois, validité)
     */
    public static function issues(int $propertyId, ?string $signDate = null): array
    {
        $ref = $signDate ?: date('Y-m-d');
        $current = self::current($propertyId);
        $byType = [];
        foreach ($current as $d) $byType[$d['doc_type']] = $d;
        $out = [];
        foreach (self::types() as $type => [$label, , $required]) {
            if ($required && !isset($byType[$type])) $out[] = "$label manquant (logement → Documents légaux).";
        }
        foreach ($current as $d) {
            $exp = self::expiry($d);
            if ($exp && $exp < $ref) $out[] = self::label($d) . ' expiré le ' . fdate($exp) . ' : à renouveler.';
        }
        return $out;
    }
}

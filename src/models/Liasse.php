<?php
declare(strict_types=1);

/**
 * « Liasse » remise au locataire : bail (signé si possible), actes de
 * cautionnement, documents légaux du logement et bordereau de remise.
 */
class Liasse
{
    /**
     * Fichiers de la liasse, dans l'ordre.
     * @return array<int, array{name:string,type:string,data:string,label:string}>
     */
    public static function files(array $lease, array $settings): array
    {
        $files = [];
        $n = 1;
        $num = function () use (&$n): string { return sprintf('%02d', $n++); };

        // 1. Bail : version signée archivée si les deux parties ont signé cette version.
        $signed = count(LeaseSignature::valid($lease, $settings)) === count(LeaseSignature::ROLES)
            ? LeaseSignature::signedPdf((int) $lease['id']) : null;
        $files[] = [
            'name'  => $num() . ($signed ? '-Bail-signe.pdf' : '-Bail.pdf'),
            'type'  => 'application/pdf',
            'data'  => $signed ? $signed['pdf'] : Pdf::renderDocument(LeaseSignature::template($lease), ['lease' => $lease, 'settings' => $settings]),
            'label' => ($lease['lease_type'] === 'meuble' ? 'Contrat de location meublée et inventaire du mobilier' : 'Contrat de location')
                . ($signed ? ' (signé électroniquement)' : ' (non signé)'),
        ];

        // 2. Actes de cautionnement (garants personnes physiques).
        foreach (Lease::guarantors($lease) as $i => $g) {
            $data = $lease;
            foreach ($g as $k => $v) $data['guarantor_' . $k] = $v;
            $files[] = [
                'name'  => $num() . '-Acte-de-cautionnement-' . self::slug($g['name']) . '.pdf',
                'type'  => 'application/pdf',
                'data'  => Pdf::renderDocument('documents/cautionnement', ['lease' => $data, 'settings' => $settings]),
                'label' => 'Acte de cautionnement solidaire — ' . $g['name'],
            ];
        }

        // 3. Documents légaux du logement (version la plus récente de chaque type).
        foreach (PropertyDocument::current((int) $lease['property_id']) as $d) {
            $full = PropertyDocument::find((int) $d['id']);
            $ext = PropertyDocument::ALLOWED[$d['mime']] ?? 'pdf';
            $files[] = [
                'name'  => $num() . '-' . self::slug(PropertyDocument::label($d)) . '.' . $ext,
                'type'  => $d['mime'],
                'data'  => (string) $full['content'],
                'label' => PropertyDocument::label($d) . (!empty($d['doc_date']) ? ' (du ' . fdate($d['doc_date']) . ')' : ''),
            ];
        }

        // 4. Bordereau de remise (liste de ce qui précède, à signer par le locataire).
        $files[] = [
            'name'  => $num() . '-Bordereau-de-remise.pdf',
            'type'  => 'application/pdf',
            'data'  => Pdf::renderDocument('documents/bordereau', ['lease' => $lease, 'settings' => $settings, 'items' => array_column($files, 'label')]),
            'label' => 'Bordereau de remise des documents',
        ];
        return $files;
    }

    /** Archive ZIP de la liasse (contenu binaire). */
    public static function zip(array $files): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'liasse');
        $zip = new ZipArchive();
        if ($zip->open($tmp, ZipArchive::OVERWRITE) !== true) throw new RuntimeException('Création du ZIP impossible.');
        foreach ($files as $f) $zip->addFromString($f['name'], $f['data']);
        $zip->close();
        $data = (string) file_get_contents($tmp);
        @unlink($tmp);
        return $data;
    }

    public static function zipName(array $lease): string
    {
        return 'Dossier-location-' . self::slug($lease['first_name'] . '-' . $lease['last_name']) . '.zip';
    }

    private static function slug(string $s): string
    {
        $s = strtr($s, ['à'=>'a','â'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','î'=>'i','ï'=>'i','ô'=>'o','ö'=>'o',
            'ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c','É'=>'E','È'=>'E','À'=>'A','Ç'=>'C','’'=>'-',"'"=>'-',' '=>'-']);
        return trim(preg_replace('/[^A-Za-z0-9-]+/', '', preg_replace('/-+/', '-', $s)), '-') ?: 'document';
    }
}

<?php
declare(strict_types=1);

/**
 * Génération de PDF à partir des gabarits HTML de documents (Dompdf).
 * La librairie est vendorée dans /vendor (aucun composer requis en prod).
 */
class Pdf
{
    /** Dompdf est-il disponible ? */
    public static function available(): bool
    {
        return class_exists(\Dompdf\Dompdf::class);
    }

    /**
     * Rend un gabarit de document en PDF et le renvoie au navigateur.
     *
     * @param string $template ex: 'documents/quittance'
     * @param array  $data     variables passées au gabarit
     * @param string $filename nom du fichier téléchargé
     * @param bool   $download true = téléchargement, false = affichage inline
     */
    public static function streamDocument(string $template, array $data, string $filename, bool $download = true): void
    {
        self::build($template, $data)->stream($filename, ['Attachment' => $download]);
        exit;
    }

    /** Rend un gabarit de document et renvoie le contenu binaire du PDF (pièce jointe email…). */
    public static function renderDocument(string $template, array $data): string
    {
        return (string) self::build($template, $data)->output();
    }

    private static function build(string $template, array $data): \Dompdf\Dompdf
    {
        $inner = render_template($template, $data);
        $css = file_get_contents(__DIR__ . '/../assets/pdf.css');

        $html = '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><style>'
            . $css . '</style></head><body><div class="sheet">' . $inner . '</div></body></html>';

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Serif');
        // Dossiers inscriptibles pour le cache de polices (compatibilité mutualisé).
        $tmp = sys_get_temp_dir();
        $options->set('tempDir', $tmp);
        $options->set('fontCache', $tmp);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf;
    }
}

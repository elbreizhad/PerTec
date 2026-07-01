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
        $dompdf->stream($filename, ['Attachment' => $download]);
        exit;
    }
}

-- Un état des lieux signé par le locataire (ou par les deux parties) est visible dans son
-- espace, même s'il a été signé sur place sans avoir été « mis à disposition ».
UPDATE inspections SET shared_at = NOW()
    WHERE shared_at IS NULL
      AND (id IN (SELECT inspection_id FROM inspection_signatures WHERE role = 'locataire')
           OR id IN (SELECT inspection_id FROM inspection_signed_pdf))

-- Suivi de l'envoi des quittances par email au locataire.
ALTER TABLE rent_payments
    ADD COLUMN emailed_at DATETIME     NULL,
    ADD COLUMN emailed_to VARCHAR(180) NULL

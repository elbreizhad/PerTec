-- Métadonnées de contrat : mode de charges, date de signature, adresse
-- actuelle du locataire, et second garant (caution solidaire).
-- Réf. loi n° 89-462 du 6 juillet 1989 (art. 8-1, 17-1, 22-1) ; loi ALUR n° 2014-366.
ALTER TABLE leases
    ADD COLUMN charge_type            VARCHAR(20)   NOT NULL DEFAULT 'provisions', -- provisions | forfait
    ADD COLUMN signature_date         DATE          NULL,
    ADD COLUMN tenant_current_address TEXT          NULL,
    ADD COLUMN guarantor2_name        VARCHAR(150)  NULL,
    ADD COLUMN guarantor2_address     TEXT          NULL,
    ADD COLUMN guarantor2_birth_date  DATE          NULL,
    ADD COLUMN guarantor2_birth_place VARCHAR(120)  NULL,
    ADD COLUMN guarantor2_email       VARCHAR(150)  NULL,
    ADD COLUMN guarantor2_phone       VARCHAR(40)   NULL,
    ADD COLUMN guarantor2_max_amount  DECIMAL(10,2) NULL,
    ADD COLUMN guarantor2_duration    VARCHAR(20)   NOT NULL DEFAULT 'indeterminee';

-- Garant / caution solidaire attaché au bail (acte de cautionnement)
ALTER TABLE leases
    ADD COLUMN guarantor_name        VARCHAR(150)  NULL,
    ADD COLUMN guarantor_address     TEXT          NULL,
    ADD COLUMN guarantor_birth_date  DATE          NULL,
    ADD COLUMN guarantor_birth_place VARCHAR(120)  NULL,
    ADD COLUMN guarantor_email       VARCHAR(150)  NULL,
    ADD COLUMN guarantor_phone       VARCHAR(40)   NULL,
    ADD COLUMN guarantor_max_amount  DECIMAL(10,2) NULL,
    ADD COLUMN guarantor_duration    VARCHAR(20)   NOT NULL DEFAULT 'indeterminee';

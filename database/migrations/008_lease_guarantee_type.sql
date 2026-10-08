-- Type de garantie du bail : aucune, garant(s) personne physique, ou Visale (Action Logement).
-- Visale ne se cumule pas avec un autre cautionnement : un seul type par bail.
ALTER TABLE leases
    ADD COLUMN guarantee_type         VARCHAR(20)   NOT NULL DEFAULT 'aucune', -- aucune | garant | visale
    ADD COLUMN visale_visa_number     VARCHAR(60)   NULL,
    ADD COLUMN visale_visa_expiry     DATE          NULL,
    ADD COLUMN visale_contract_number VARCHAR(60)   NULL,
    ADD COLUMN visale_max_rent        DECIMAL(10,2) NULL;
-- Baux existants : ceux qui ont déjà un garant passent en type « garant ».
UPDATE leases SET guarantee_type = 'garant'
    WHERE guarantor_name IS NOT NULL AND TRIM(guarantor_name) <> ''

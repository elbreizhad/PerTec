-- Paramètres fiscaux LMNP par bien
ALTER TABLE properties
    ADD COLUMN land_share_pct        DECIMAL(5,2)  NOT NULL DEFAULT 15.00,
    ADD COLUMN amort_years_building  SMALLINT UNSIGNED NOT NULL DEFAULT 30,
    ADD COLUMN amort_years_furniture SMALLINT UNSIGNED NOT NULL DEFAULT 7,
    ADD COLUMN amort_years_works     SMALLINT UNSIGNED NOT NULL DEFAULT 10,
    ADD COLUMN accountant_fees       DECIMAL(10,2) NOT NULL DEFAULT 0,
    ADD COLUMN tax_regime            VARCHAR(20)   NOT NULL DEFAULT 'reel';

-- Schéma de la base PerTec (gestion locative)
-- Compatible MySQL 5.7+ / MariaDB 10.2+

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username      VARCHAR(80)  NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Coordonnées du bailleur + réglages (paires clé/valeur)
CREATE TABLE IF NOT EXISTS settings (
    `key`   VARCHAR(80)  NOT NULL,
    `value` TEXT         NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Biens immobiliers
CREATE TABLE IF NOT EXISTS properties (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    label          VARCHAR(150) NOT NULL,           -- nom court du bien
    type           VARCHAR(40)  NOT NULL DEFAULT 'appartement',
    address        VARCHAR(255) NULL,
    postal_code    VARCHAR(10)  NULL,
    city           VARCHAR(120) NULL,
    surface_m2     DECIMAL(8,2) NULL,
    rooms          TINYINT UNSIGNED NULL,           -- nombre de pièces
    purchase_date  DATE         NULL,
    purchase_price DECIMAL(12,2) NOT NULL DEFAULT 0, -- prix d'achat FAI ou net vendeur
    notary_fees    DECIMAL(12,2) NOT NULL DEFAULT 0, -- frais de notaire
    agency_fees    DECIMAL(12,2) NOT NULL DEFAULT 0, -- frais d'agence
    works_cost     DECIMAL(12,2) NOT NULL DEFAULT 0, -- travaux
    other_costs    DECIMAL(12,2) NOT NULL DEFAULT 0, -- autres frais (mobilier...)
    loan_amount        DECIMAL(12,2) NOT NULL DEFAULT 0, -- capital emprunté
    loan_rate          DECIMAL(6,3)  NOT NULL DEFAULT 0, -- taux annuel %
    loan_duration_months INT UNSIGNED NOT NULL DEFAULT 0,
    loan_monthly       DECIMAL(12,2) NOT NULL DEFAULT 0, -- mensualité (assurance incluse)
    property_tax   DECIMAL(12,2) NOT NULL DEFAULT 0, -- taxe foncière annuelle
    insurance_year DECIMAL(12,2) NOT NULL DEFAULT 0, -- assurance PNO annuelle
    charges_year   DECIMAL(12,2) NOT NULL DEFAULT 0, -- charges de copro non récupérables (annuel)
    mgmt_fees_pct  DECIMAL(6,3)  NOT NULL DEFAULT 0, -- frais de gestion en % du loyer
    -- Paramètres fiscaux LMNP
    land_share_pct        DECIMAL(5,2)  NOT NULL DEFAULT 15.00, -- part du terrain (non amortissable)
    amort_years_building  SMALLINT UNSIGNED NOT NULL DEFAULT 30, -- durée amort. bâti
    amort_years_furniture SMALLINT UNSIGNED NOT NULL DEFAULT 7,  -- durée amort. mobilier
    amort_years_works     SMALLINT UNSIGNED NOT NULL DEFAULT 10, -- durée amort. travaux
    accountant_fees       DECIMAL(10,2) NOT NULL DEFAULT 0,      -- frais de comptable annuels
    tax_regime            VARCHAR(20)   NOT NULL DEFAULT 'reel', -- reel / micro
    notes          TEXT         NULL,
    created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dépenses détaillées par bien (travaux, achat, aménagement, mobilier…)
CREATE TABLE IF NOT EXISTS property_expenses (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    property_id  INT UNSIGNED NOT NULL,
    category     VARCHAR(40)  NOT NULL DEFAULT 'travaux',
    label        VARCHAR(200) NOT NULL,
    amount       DECIMAL(12,2) NOT NULL DEFAULT 0,
    expense_date DATE         NULL,
    notes        VARCHAR(255) NULL,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_expenses_property (property_id),
    CONSTRAINT fk_expenses_property FOREIGN KEY (property_id)
        REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Locataires
CREATE TABLE IF NOT EXISTS tenants (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(120) NOT NULL,
    last_name  VARCHAR(120) NOT NULL,
    email      VARCHAR(180) NULL,
    phone      VARCHAR(40)  NULL,
    notes      TEXT         NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Baux (contrats de location)
CREATE TABLE IF NOT EXISTS leases (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    property_id   INT UNSIGNED NOT NULL,
    tenant_id     INT UNSIGNED NOT NULL,
    lease_type    VARCHAR(30)  NOT NULL DEFAULT 'vide', -- vide / meuble
    start_date    DATE         NOT NULL,
    end_date      DATE         NULL,
    rent_amount   DECIMAL(10,2) NOT NULL DEFAULT 0,  -- loyer hors charges
    charges_amount DECIMAL(10,2) NOT NULL DEFAULT 0, -- provisions pour charges
    deposit_amount DECIMAL(10,2) NOT NULL DEFAULT 0, -- dépôt de garantie
    payment_day   TINYINT UNSIGNED NOT NULL DEFAULT 1, -- jour d'échéance
    status        VARCHAR(20)  NOT NULL DEFAULT 'active', -- active / terminated
    notes         TEXT         NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_leases_property (property_id),
    KEY idx_leases_tenant (tenant_id),
    CONSTRAINT fk_leases_property FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    CONSTRAINT fk_leases_tenant   FOREIGN KEY (tenant_id)   REFERENCES tenants(id)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Loyers / échéances (une ligne par mois, sert aussi à générer les quittances)
CREATE TABLE IF NOT EXISTS rent_payments (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    lease_id       INT UNSIGNED NOT NULL,
    period_year    SMALLINT UNSIGNED NOT NULL,
    period_month   TINYINT UNSIGNED NOT NULL,        -- 1..12
    due_date       DATE         NULL,
    amount_rent    DECIMAL(10,2) NOT NULL DEFAULT 0,
    amount_charges DECIMAL(10,2) NOT NULL DEFAULT 0,
    amount_paid    DECIMAL(10,2) NOT NULL DEFAULT 0,
    paid_date      DATE         NULL,
    payment_method VARCHAR(40)  NULL,
    status         VARCHAR(20)  NOT NULL DEFAULT 'pending', -- pending / paid / partial / late
    receipt_number VARCHAR(40)  NULL,
    notes          VARCHAR(255) NULL,
    created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_payment_period (lease_id, period_year, period_month),
    KEY idx_payments_lease (lease_id),
    CONSTRAINT fk_payments_lease FOREIGN KEY (lease_id) REFERENCES leases(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET foreign_key_checks = 1;

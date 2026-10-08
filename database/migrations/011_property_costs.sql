-- Charges et impôts réels d'un bien : appels de charges de copropriété,
-- régularisation annuelle du syndic, taxe foncière (dont TEOM), assurance PNO…
-- recoverable = part récupérable auprès du locataire (décret n° 87-713).
CREATE TABLE IF NOT EXISTS property_costs (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    property_id INT UNSIGNED  NOT NULL,
    kind        VARCHAR(30)   NOT NULL,
    label       VARCHAR(200)  NULL,
    year        SMALLINT      NOT NULL,
    cost_date   DATE          NULL,
    amount      DECIMAL(12,2) NOT NULL DEFAULT 0,
    recoverable DECIMAL(12,2) NOT NULL DEFAULT 0,
    notes       VARCHAR(255)  NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_costs_property_year (property_id, year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

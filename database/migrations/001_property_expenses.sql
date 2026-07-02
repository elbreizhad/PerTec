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

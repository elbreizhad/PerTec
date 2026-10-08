-- Inventaire du mobilier (annexe 1 du bail meublé) coché dans l'application.
CREATE TABLE IF NOT EXISTS lease_inventory (
    lease_id   INT UNSIGNED NOT NULL,
    item_key   VARCHAR(80)  NOT NULL,
    label      VARCHAR(255) NULL,
    present    VARCHAR(3)   NULL,
    notes      VARCHAR(255) NULL,
    updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (lease_id, item_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

-- Checklist de conformité par bail (éléments cochés uniquement)
CREATE TABLE IF NOT EXISTS lease_checklist (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    lease_id   INT UNSIGNED NOT NULL,
    item_key   VARCHAR(50)  NOT NULL,
    updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_lease_item (lease_id, item_key),
    KEY idx_checklist_lease (lease_id),
    CONSTRAINT fk_checklist_lease FOREIGN KEY (lease_id)
        REFERENCES leases(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

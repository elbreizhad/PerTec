-- Espace locataire : codes de connexion à usage unique envoyés par email.
CREATE TABLE IF NOT EXISTS tenant_login_codes (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email      VARCHAR(180) NOT NULL,
    code_hash  VARCHAR(255) NOT NULL,
    expires_at DATETIME     NOT NULL,
    attempts   TINYINT UNSIGNED NOT NULL DEFAULT 0,
    used_at    DATETIME     NULL,
    ip         VARCHAR(45)  NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE tenants ADD COLUMN portal_last_login DATETIME NULL

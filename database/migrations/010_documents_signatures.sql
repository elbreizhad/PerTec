-- Documents légaux du logement (diagnostics, notice…), stockés en base pour
-- ne dépendre d'aucun dossier du serveur (non touchés par les déploiements).
CREATE TABLE IF NOT EXISTS property_documents (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    property_id INT UNSIGNED NOT NULL,
    doc_type    VARCHAR(30)  NOT NULL,
    title       VARCHAR(255) NULL,
    doc_date    DATE         NULL,
    filename    VARCHAR(255) NOT NULL,
    mime        VARCHAR(100) NOT NULL,
    size        INT UNSIGNED NOT NULL,
    content     LONGBLOB     NOT NULL,
    uploaded_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_property (property_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Signatures électroniques du bail (une par partie), avec preuve : date, IP,
-- navigateur et empreinte SHA-256 du contrat signé.
CREATE TABLE IF NOT EXISTS lease_signatures (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    lease_id    INT UNSIGNED NOT NULL,
    role        VARCHAR(20)  NOT NULL,
    signer_name VARCHAR(150) NOT NULL,
    image       MEDIUMTEXT   NOT NULL,
    signed_at   DATETIME     NOT NULL,
    ip          VARCHAR(45)  NULL,
    user_agent  VARCHAR(255) NULL,
    doc_hash    CHAR(64)     NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_lease_role (lease_id, role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contrat signé archivé (PDF figé au moment de la dernière signature).
CREATE TABLE IF NOT EXISTS lease_signed_pdf (
    lease_id   INT UNSIGNED NOT NULL,
    pdf        LONGBLOB     NOT NULL,
    sha256     CHAR(64)     NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (lease_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Lien de signature à distance pour le locataire.
ALTER TABLE leases
    ADD COLUMN sign_token         CHAR(64) NULL,
    ADD COLUMN sign_token_expires DATETIME NULL

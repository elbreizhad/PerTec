-- États des lieux d'entrée et de sortie (loi n° 89-462 art. 3-2, décret n° 2016-382).
-- Le contenu (pièces, compteurs, clés, inventaire…) est stocké en JSON dans « data » :
-- la liste des pièces et des éléments est propre à chaque logement et modifiable.
CREATE TABLE IF NOT EXISTS inspections (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    lease_id     INT UNSIGNED NOT NULL,
    kind         VARCHAR(10)  NOT NULL,             -- entree | sortie
    inspected_at DATETIME     NULL,                 -- date et heure de l'état des lieux
    data         MEDIUMTEXT   NULL,                 -- contenu (JSON)
    shared_at    DATETIME     NULL,                 -- mis à disposition dans l'espace locataire
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_lease_kind (lease_id, kind)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Signatures électroniques de l'état des lieux (une par partie), avec preuve.
CREATE TABLE IF NOT EXISTS inspection_signatures (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    inspection_id INT UNSIGNED NOT NULL,
    role          VARCHAR(20)  NOT NULL,
    signer_name   VARCHAR(150) NOT NULL,
    image         MEDIUMTEXT   NOT NULL,
    signed_at     DATETIME     NOT NULL,
    ip            VARCHAR(45)  NULL,
    user_agent    VARCHAR(255) NULL,
    doc_hash      CHAR(64)     NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_inspection_role (inspection_id, role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- État des lieux signé archivé (PDF figé au moment de la dernière signature).
CREATE TABLE IF NOT EXISTS inspection_signed_pdf (
    inspection_id INT UNSIGNED NOT NULL,
    pdf           LONGBLOB     NOT NULL,
    sha256        CHAR(64)     NOT NULL,
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (inspection_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
;

-- Modèle d'état des lieux propre à chaque logement (désignation, pièces et
-- éléments, clés, inventaire du mobilier) : repris à chaque nouvel état des lieux.
ALTER TABLE properties ADD COLUMN inspection_template MEDIUMTEXT NULL

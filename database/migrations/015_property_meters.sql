-- Compteurs du logement, communiqués au locataire (espace locataire) :
-- n° du compteur d'eau et point de livraison (PDL) Enedis.
ALTER TABLE properties
    ADD COLUMN water_meter VARCHAR(50) NULL,
    ADD COLUMN enedis_pdl  VARCHAR(20) NULL

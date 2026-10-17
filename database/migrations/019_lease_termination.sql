-- Fin de bail : date de fin effective (fin du préavis / remise des clés), motif,
-- date de réception du congé. Champs distincts de end_date (fin de la période
-- initiale, imprimée dans le contrat) pour ne pas invalider les signatures du bail.
ALTER TABLE leases
    ADD COLUMN termination_date   DATE         NULL,
    ADD COLUMN termination_reason VARCHAR(30)  NULL,
    ADD COLUMN notice_date        DATE         NULL,
    ADD COLUMN termination_notes  VARCHAR(255) NULL

-- Équipements complémentaires (meublé) listés en annexe 1, en plus du mobilier obligatoire
ALTER TABLE leases ADD COLUMN furniture_extra TEXT NULL AFTER notes;

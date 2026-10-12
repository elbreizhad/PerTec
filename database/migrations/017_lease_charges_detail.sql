-- Détail des charges récupérables par nature (JSON : [{nature, label, amount}]).
ALTER TABLE leases ADD COLUMN charges_detail TEXT NULL

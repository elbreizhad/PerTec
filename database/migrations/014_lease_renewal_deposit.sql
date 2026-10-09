-- Tacite reconduction du bail (par défaut oui) et encaissement du dépôt de garantie.
ALTER TABLE leases
    ADD COLUMN auto_renew        TINYINT(1) NOT NULL DEFAULT 1,
    ADD COLUMN deposit_paid_date DATE       NULL;
-- Première échéance : jamais avant la date d'effet du bail.
UPDATE rent_payments rp JOIN leases l ON l.id = rp.lease_id
    SET rp.due_date = l.start_date
    WHERE rp.due_date IS NOT NULL AND rp.due_date < l.start_date
      AND rp.period_year = YEAR(l.start_date) AND rp.period_month = MONTH(l.start_date)

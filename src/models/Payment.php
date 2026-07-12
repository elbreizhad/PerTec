<?php
declare(strict_types=1);

class Payment
{
    public static function find(int $id): ?array
    {
        return Database::one(
            "SELECT rp.*, l.rent_amount AS lease_rent, l.charges_amount AS lease_charges,
                    l.payment_day, l.property_id, l.tenant_id
             FROM rent_payments rp JOIN leases l ON l.id = rp.lease_id
             WHERE rp.id = ?",
            [$id]
        );
    }

    /** Toutes les échéances d'un bail, plus récentes d'abord. */
    public static function forLease(int $leaseId): array
    {
        return Database::all(
            'SELECT * FROM rent_payments WHERE lease_id = ?
             ORDER BY period_year DESC, period_month DESC',
            [$leaseId]
        );
    }

    /** Vue globale des loyers (avec bien + locataire). */
    public static function overview(?int $year = null): array
    {
        $sql =
            "SELECT rp.*, p.label AS property_label, t.first_name, t.last_name
             FROM rent_payments rp
             JOIN leases l     ON l.id = rp.lease_id
             JOIN properties p ON p.id = l.property_id
             JOIN tenants t    ON t.id = l.tenant_id";
        $params = [];
        if ($year !== null) {
            $sql .= ' WHERE rp.period_year = ?';
            $params[] = $year;
        }
        $sql .= ' ORDER BY rp.period_year DESC, rp.period_month DESC, p.label';
        return Database::all($sql, $params);
    }

    public static function exists(int $leaseId, int $year, int $month): bool
    {
        return (bool) Database::one(
            'SELECT id FROM rent_payments WHERE lease_id = ? AND period_year = ? AND period_month = ?',
            [$leaseId, $year, $month]
        );
    }

    /** Crée une échéance pour un mois donné à partir du bail. */
    public static function createForPeriod(array $lease, int $year, int $month): ?int
    {
        if (self::exists((int)$lease['id'], $year, $month)) {
            return null;
        }
        $day = min(28, max(1, (int)$lease['payment_day']));
        $due = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $pro = self::prorata($lease, $year, $month);
        return Database::insert('rent_payments', [
            'lease_id'       => (int) $lease['id'],
            'period_year'    => $year,
            'period_month'   => $month,
            'due_date'       => $due,
            'amount_rent'    => $pro['rent'],
            'amount_charges' => $pro['charges'],
            'amount_paid'    => 0,
            'status'         => 'pending',
            'notes'          => $pro['note'],
        ]);
    }

    /**
     * Calcule le loyer/charges d'un mois en tenant compte d'une entrée (ou
     * d'une sortie) en cours de mois : prorata au nombre de jours d'occupation.
     * Renvoie ['rent' => float, 'charges' => float, 'note' => ?string].
     */
    public static function prorata(array $lease, int $year, int $month): array
    {
        $rent    = (float) $lease['rent_amount'];
        $charges = (float) $lease['charges_amount'];
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
        $firstDay = 1;
        $lastDay  = $daysInMonth;

        // Entrée en cours de mois (mois de la date de début).
        if (!empty($lease['start_date'])) {
            $start = new DateTime($lease['start_date']);
            if ((int) $start->format('Y') === $year && (int) $start->format('n') === $month) {
                $firstDay = (int) $start->format('j');
            }
        }
        // Sortie en cours de mois (mois de la date de fin, si renseignée).
        if (!empty($lease['end_date'])) {
            $end = new DateTime($lease['end_date']);
            if ((int) $end->format('Y') === $year && (int) $end->format('n') === $month) {
                $lastDay = (int) $end->format('j');
            }
        }

        $occupied = $lastDay - $firstDay + 1;
        if ($occupied >= $daysInMonth || $occupied <= 0) {
            return ['rent' => $rent, 'charges' => $charges, 'note' => null];
        }

        $factor = $occupied / $daysInMonth;
        return [
            'rent'    => round($rent * $factor, 2),
            'charges' => round($charges * $factor, 2),
            'note'    => sprintf(
                'Prorata : %d/%d jours (du %d au %d)',
                $occupied, $daysInMonth, $firstDay, $lastDay
            ),
        ];
    }

    /** Génère les échéances manquantes pour tous les baux actifs jusqu'au mois courant. */
    public static function generateDue(): int
    {
        $leases = Database::all("SELECT * FROM leases WHERE status = 'active'");
        $created = 0;
        $now = new DateTime('first day of this month');
        foreach ($leases as $lease) {
            $start = new DateTime($lease['start_date']);
            $start->modify('first day of this month');
            $end = $lease['end_date'] ? new DateTime($lease['end_date']) : clone $now;
            if ($end > $now) $end = clone $now;
            $cursor = clone $start;
            while ($cursor <= $end) {
                $y = (int) $cursor->format('Y');
                $m = (int) $cursor->format('n');
                if (self::createForPeriod($lease, $y, $m) !== null) {
                    $created++;
                }
                $cursor->modify('+1 month');
            }
        }
        return $created;
    }

    public static function markPaid(int $id, ?string $paidDate, ?string $method): void
    {
        $p = self::find($id);
        if (!$p) return;
        $total = (float)$p['amount_rent'] + (float)$p['amount_charges'];
        Database::update('rent_payments', [
            'amount_paid'    => $total,
            'paid_date'      => $paidDate ?: date('Y-m-d'),
            'payment_method' => $method ?: 'Virement',
            'status'         => 'paid',
            'receipt_number' => $p['receipt_number'] ?: self::nextReceiptNumber(),
        ], 'id = :id', ['id' => $id]);
    }

    public static function markPending(int $id): void
    {
        Database::update('rent_payments', [
            'amount_paid' => 0,
            'paid_date'   => null,
            'status'      => 'pending',
        ], 'id = :id', ['id' => $id]);
    }

    private static function nextReceiptNumber(): string
    {
        $year = date('Y');
        $row = Database::one(
            "SELECT COUNT(*) AS n FROM rent_payments
             WHERE receipt_number IS NOT NULL AND receipt_number LIKE ?",
            [$year . '-%']
        );
        $n = ((int) ($row['n'] ?? 0)) + 1;
        return sprintf('%s-%04d', $year, $n);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM rent_payments WHERE id = ?', [$id]);
    }

    /** Montant encaissé par mois (1..12) sur une année, base de caisse. */
    public static function monthlyPaid(int $year): array
    {
        $rows = Database::all(
            "SELECT amount_paid, paid_date, period_year, period_month
             FROM rent_payments WHERE status = 'paid'"
        );
        $out = array_fill(1, 12, 0.0);
        foreach ($rows as $r) {
            $y = $r['paid_date'] ? (int) substr($r['paid_date'], 0, 4) : (int) $r['period_year'];
            $m = $r['paid_date'] ? (int) substr($r['paid_date'], 5, 2) : (int) $r['period_month'];
            if ($y === $year && $m >= 1 && $m <= 12) {
                $out[$m] += (float) $r['amount_paid'];
            }
        }
        return $out;
    }

    /** Total encaissé + total dû sur une année. */
    public static function yearStats(int $year): array
    {
        $row = Database::one(
            "SELECT
                COALESCE(SUM(amount_rent + amount_charges),0) AS due,
                COALESCE(SUM(amount_paid),0) AS paid,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS nb_paid,
                COUNT(*) AS nb_total
             FROM rent_payments WHERE period_year = ?",
            [$year]
        );
        return $row ?: ['due'=>0,'paid'=>0,'nb_paid'=>0,'nb_total'=>0];
    }
}

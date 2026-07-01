<?php
declare(strict_types=1);

/**
 * Calculs d'investissement et de rentabilité pour un bien.
 */
class Finance
{
    /** Coût total d'acquisition. */
    public static function totalCost(array $p): float
    {
        return (float)$p['purchase_price'] + (float)$p['notary_fees']
            + (float)$p['agency_fees'] + (float)$p['works_cost'] + (float)$p['other_costs'];
    }

    /** Loyer mensuel hors charges actuellement en cours pour ce bien (bail actif). */
    public static function currentMonthlyRent(int $propertyId): float
    {
        $row = Database::one(
            "SELECT rent_amount FROM leases
             WHERE property_id = ? AND status = 'active'
             ORDER BY start_date DESC LIMIT 1",
            [$propertyId]
        );
        return $row ? (float) $row['rent_amount'] : 0.0;
    }

    /** Charges annuelles récurrentes (hors emprunt). */
    public static function annualCharges(array $p, float $annualRent): float
    {
        $mgmt = $annualRent * ((float)$p['mgmt_fees_pct'] / 100);
        return (float)$p['property_tax'] + (float)$p['insurance_year']
            + (float)$p['charges_year'] + $mgmt;
    }

    /**
     * Indicateurs de rentabilité.
     * - brute = loyers annuels / coût total
     * - nette = (loyers annuels - charges) / coût total
     * - cashflow mensuel = loyer - charges/12 - mensualité d'emprunt
     */
    public static function indicators(array $p): array
    {
        $monthlyRent = self::currentMonthlyRent((int)$p['id']);
        $annualRent  = $monthlyRent * 12;
        $totalCost   = self::totalCost($p);
        $annualCharges = self::annualCharges($p, $annualRent);
        $annualLoan  = (float)$p['loan_monthly'] * 12;

        $gross = $totalCost > 0 ? ($annualRent / $totalCost) * 100 : 0.0;
        $net   = $totalCost > 0 ? (($annualRent - $annualCharges) / $totalCost) * 100 : 0.0;
        $cashflowMonthly = $monthlyRent - ($annualCharges / 12) - (float)$p['loan_monthly'];

        return [
            'monthly_rent'     => $monthlyRent,
            'annual_rent'      => $annualRent,
            'total_cost'       => $totalCost,
            'annual_charges'   => $annualCharges,
            'annual_loan'      => $annualLoan,
            'gross_yield'      => $gross,
            'net_yield'        => $net,
            'cashflow_monthly' => $cashflowMonthly,
            'cashflow_annual'  => $cashflowMonthly * 12,
        ];
    }
}

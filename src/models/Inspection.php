<?php
declare(strict_types=1);

/**
 * États des lieux d'entrée et de sortie (art. 3-2 de la loi n° 89-462,
 * décret n° 2016-382 du 30 mars 2016 ; inventaire : décret n° 2015-981).
 *
 * Chaque logement a son propre modèle (désignation, pièces et éléments, clés,
 * inventaire du mobilier), saisi en texte sur la fiche du bien. Un état des
 * lieux recopie ce modèle à sa création ; celui de sortie reprend l'état des
 * lieux d'entrée s'il existe (colonnes « Entrée »).
 *
 * Signature électronique simple des deux parties, comme pour le bail :
 * empreinte SHA-256 du document signé, IP, navigateur ; PDF archivé une fois
 * les deux signatures recueillies. Le locataire consulte, complète (nouvelle
 * adresse, IBAN, réserves) et signe depuis son espace.
 */
class Inspection
{
    public const KINDS = [
        'entree' => "État des lieux d'entrée",
        'sortie' => 'État des lieux de sortie',
    ];
    public const ROLES = ['bailleur' => 'Le bailleur', 'locataire' => 'Le locataire'];

    /** États possibles d'un élément (« Neuf » seulement à l'entrée). */
    public const STATES = ['neuf' => 'Neuf', 'bon' => 'Bon', 'usage' => 'Usagé', 'degrade' => 'Dégradé'];

    /** Lignes libres ajoutées en fin de chaque pièce / catégorie pour un élément imprévu. */
    public const FREE_ROWS = 1;

    /* ---------------------------------------------------------------------
     * Modèle du logement
     * ------------------------------------------------------------------- */

    public const DEFAULT_ROOMS = <<<TXT
# Entrée
Sol
Murs / peinture
Plafond
Porte d'entrée + serrure
Interrupteurs / prises
Éclairage

# Séjour / pièce principale
Sol
Murs / peinture
Plafond
Fenêtre / occultation
Chauffage
Interrupteurs / prises
Éclairage
Détecteur de fumée (DAAF)

# Cuisine
Meubles haut/bas + plan de travail
Évier + robinetterie
Plaques de cuisson
Hotte / extraction
Sol / murs

# Salle d'eau
Sol
Murs / faïence
Lavabo / vasque + robinetterie
Douche / baignoire
VMC / aération
Chauffe-eau

# WC
Cuvette + abattant
Chasse d'eau
Sol / murs
TXT;

    public const DEFAULT_KEYS = <<<TXT
Clé porte d'entrée logement | 2
Clé boîte aux lettres | 1
Clé / badge parties communes | 1
TXT;

    public const DEFAULT_INVENTORY = <<<TXT
# Literie et couchage
Lit / couchage | 1 | Bon
Couette ou couverture | 1 | Bon
Oreillers | 2 | Bon

# Table, sièges et rangement
Table | 1 | Bon
Chaises | 2 | Bon
Étagères de rangement | 1 | Bon

# Électroménager
Plaques de cuisson | 1 | Bon
Four ou four à micro-ondes | 1 | Bon
Réfrigérateur / congélateur | 1 | Bon

# Vaisselle et ustensiles
Assiettes | 2 | Bon
Verres | 2 | Bon
Couverts (jeux) | 2 | Bon
Casserole | 1 | Bon
Poêle | 1 | Bon

# Luminaires et occultation
Luminaires | 1 | Bon
Rideaux / occultation des chambres | 1 | Bon

# Entretien ménager
Aspirateur | 1 | Bon
Balai + pelle | 1 | Bon
TXT;

    /** Exemple complet : studio meublé (repris d'un état des lieux réel), chargeable sur la fiche du bien. */
    public const EXAMPLE_ROOMS = <<<TXT
# Entrée
Sol (parquet stratifié)
Murs / peinture
Plafond
Porte d'entrée + serrure
Interrupteurs / prises
Éclairage

# Séjour / pièce principale
Sol (parquet stratifié gris)
Murs / peinture
Plafond
Fenêtre / occultation
Radiateur électrique
Interrupteurs / prises
Éclairage (plafonnier)
Détecteur de fumée (DAAF)

# Salle d'eau
Sol (carrelage gris)
Murs / crédence effet marbre
Plafond
Meuble sous-vasque 2 portes + vasque
Mitigeur / robinetterie
Miroir
Sèche-serviettes électrique
VMC / aération
Chauffe-eau (ballon)

# WC
Cuvette WC + abattant
Plaque de commande double touche
Sol / murs

# Coin cuisine (kitchenette)
Meubles haut/bas + plan de travail
Évier inox + mitigeur
Plaques de cuisson
Hotte / extraction
Réfrigérateur
Four à micro-ondes
Prises / branchements
TXT;

    public const EXAMPLE_KEYS = <<<TXT
Clé porte d'entrée logement | 2
Clé / badge hall — parties communes | 2
Clé boîte aux lettres | 2
Télécommande / badge parking | 2
Autre (à préciser) |
TXT;

    public const EXAMPLE_INVENTORY = <<<TXT
# Literie et couchage
Banquette-lit gigogne blanche (sommier à lattes) | 1 | Bon
Matelas | 3 | Bon | dont surmatelas
Grand coussin dossier / tête de lit gris | 1 | Bon
Couette 140×200 (4 saisons) | 2 | Bon
Coussins décoratifs | 4 | Bon | 2 beiges frangés + 1 écru + 1 gris
Plaid / jeté de lit beige | 1 | Bon

# Rangement et surfaces
Commode blanche 6 tiroirs (boutons noirs) | 1 | Bon
Étagères murales bois | 2 | Bon
Meuble TV / console bois | 1 | Bon
Buffet / meuble bas bois | 1 | Bon
Table basse | 1 | Bon
Tapis | 1 | Bon

# Table, sièges et arts de la table
Table à manger ronde blanche | 1 | Bon
Chaise coque (lilas) | 3 | Bon
Sets de table (jute, motif mandala) | 4 | Bon | dont 1 sur le meuble blanc

# Électroménager et multimédia
Téléviseur CHiQ (~40") + télécommande | 1 | Bon
Réfrigérateur / congélateur | 1 | Bon
Lave-linge | 1 | Bon
Four à micro-ondes | 1 | Bon
Hotte aspirante | 1 | Bon
Plaques de cuisson (vitrocéramique) | 1 | Bon

# Salle d'eau et WC
Sèche-serviettes électrique (équipement fixe) | 1 | Bon
Patère bois 4 crochets | 1 | Bon
Ardoise mémo décorative | 1 | Bon
Étagère bois murale | 1 | Bon
Distributeur de savon + porte-brosse à dents | 2 | Bon
Poubelle de salle de bain | 1 | Bon
Porte-balai WC (bambou) + balayette | 1 | Bon
Dérouleur papier toilette | 1 | Bon

# Luminaires
Lampe à poser (abat-jour taupe) | 1 | Bon
Éclairages plafonniers / spots (fixes) | plus. | Bon

# Vaisselle et couverts
Bols | 3 | Bon
Assiettes | 3 | Bon
Verres | 3 | Bon
Tasses | 3 | Bon
Fourchettes | 4 | Bon
Cuillères à soupe | 4 | Bon
Cuillères à café | 6 | Bon
Couteaux à viande | 4 | Bon
Couteaux de table (ronds) | 2 | Bon

# Ustensiles de cuisine
Louche | 1 | Bon
Cuillère de service | 1 | Bon
Économe (éplucheur) | 1 | Bon
Pelle à tarte | 1 | Bon
Passoire verte | 1 | Bon
Mixeur | 1 | Bon
Saladier / bol mélangeur | 1 | Bon
Casserole | 1 | Bon
Poêle | 1 | Bon
Ciseaux (paires) | 2 | Bon
Récipient à ustensiles (pot inox) | 1 | Bon

# Entretien ménager et divers (décret 2015-981)
Aspirateur | 1 | Bon
Balai | 1 | Bon
Serpillère | 1 | Bon
Seau | 1 | Bon
Brosse et pelle | 1 | Bon
Poubelles (tri sélectif) | 2 | Bon
Rideaux / voilages | 3 | Bon
TXT;

    /** Modèle d'un logement : saisi sur la fiche du bien, sinon valeurs par défaut. */
    public static function template(array $property): array
    {
        $saved = json_decode((string) ($property['inspection_template'] ?? ''), true);
        $t = is_array($saved) ? $saved : [];
        $surface = !empty($property['surface_m2']) ? ' — ' . rtrim(rtrim(number_format((float) $property['surface_m2'], 2, ',', ' '), '0'), ',') . ' m²' : '';
        return [
            'residence'   => (string) ($t['residence'] ?? ''),
            'designation' => (string) ($t['designation'] ?? ucfirst((string) ($property['type'] ?? 'appartement')) . $surface),
            'annexe'      => (string) ($t['annexe'] ?? ''),
            'heating'     => (string) ($t['heating'] ?? ''),
            'rooms'       => (string) ($t['rooms'] ?? self::DEFAULT_ROOMS),
            'keys'        => (string) ($t['keys'] ?? self::DEFAULT_KEYS),
            'inventory'   => (string) ($t['inventory'] ?? self::DEFAULT_INVENTORY),
            'custom'      => is_array($saved),
        ];
    }

    public static function saveTemplate(int $propertyId, array $in): void
    {
        $clean = fn($k) => str_replace("\r\n", "\n", trim((string) ($in[$k] ?? '')));
        $t = [];
        foreach (['residence', 'designation', 'annexe', 'heating', 'rooms', 'keys', 'inventory'] as $k) $t[$k] = $clean($k);
        Database::update('properties', ['inspection_template' => json_encode($t, JSON_UNESCAPED_UNICODE)], 'id = :id', ['id' => $propertyId]);
    }

    /**
     * Texte « # Titre » + lignes « libellé | qté | état | observation » → groupes.
     * @return array<int, array{name:string, items:array<int, array>}>
     */
    public static function parseGroups(string $text, string $defaultName = 'Divers'): array
    {
        $groups = [];
        foreach (preg_split('/\R/', $text) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            if ($line[0] === '#') {
                $groups[] = ['name' => trim(ltrim($line, '#')), 'items' => []];
                continue;
            }
            if (!$groups) $groups[] = ['name' => $defaultName, 'items' => []];
            $groups[count($groups) - 1]['items'][] = self::parseLine($line);
        }
        return array_values(array_filter($groups, fn($g) => $g['name'] !== '' || $g['items']));
    }

    /** « libellé | qté | état | observation » → tableau. */
    public static function parseLine(string $line): array
    {
        $p = array_map('trim', explode('|', $line));
        return [
            'label' => mb_substr($p[0], 0, 200),
            'qty'   => mb_substr($p[1] ?? '', 0, 20),
            'state' => self::stateKey($p[2] ?? ''),
            'notes' => mb_substr($p[3] ?? '', 0, 255),
        ];
    }

    /** « Bon », « usagé »… → clé d'état (ou ''). */
    public static function stateKey(?string $s): string
    {
        $s = trim(mb_strtolower((string) $s));
        if ($s === '') return '';
        if (isset(self::STATES[$s])) return $s;
        foreach (self::STATES as $k => $label) if (mb_strtolower($label) === $s) return $k;
        return '';
    }

    public static function stateLabel(?string $k): string
    {
        return self::STATES[(string) $k] ?? '';
    }

    /* ---------------------------------------------------------------------
     * États des lieux
     * ------------------------------------------------------------------- */

    public static function find(int $id): ?array
    {
        try {
            $i = Database::one('SELECT * FROM inspections WHERE id = ?', [$id]);
        } catch (Throwable $e) {
            return null; // migration en attente
        }
        if (!$i) return null;
        $i['data'] = self::normalize(json_decode((string) $i['data'], true) ?: []);
        return $i;
    }

    /** États des lieux d'un bail, indexés par type (entree / sortie). */
    public static function forLease(int $leaseId): array
    {
        try {
            $rows = Database::all('SELECT id, kind FROM inspections WHERE lease_id = ?', [$leaseId]);
        } catch (Throwable $e) {
            return [];
        }
        $out = [];
        foreach (['entree', 'sortie'] as $k) {
            foreach ($rows as $r) if ($r['kind'] === $k) $out[$k] = self::find((int) $r['id']);
        }
        return array_filter($out);
    }

    /** Crée l'état des lieux (modèle du logement, ou état des lieux d'entrée pour la sortie). */
    public static function create(array $lease, string $kind): int
    {
        if (!isset(self::KINDS[$kind])) throw new RuntimeException("Type d'état des lieux inconnu.");
        $existing = self::forLease((int) $lease['id']);
        if (isset($existing[$kind])) return (int) $existing[$kind]['id'];

        $property = Property::find((int) $lease['property_id']) ?? [];
        $entry = $kind === 'sortie' ? ($existing['entree'] ?? null) : null;
        $data = $entry ? self::fromEntry($entry['data']) : self::fromTemplate(self::template($property), $lease['lease_type'] === 'meuble');
        $data['meters']['elec_number'] = $data['meters']['elec_number'] ?: (string) ($property['enedis_pdl'] ?? '');
        $data['meters']['water_number'] = $data['meters']['water_number'] ?: (string) ($property['water_meter'] ?? '');
        if ($kind === 'sortie') {
            $data['deposit']['amount'] = (string) (float) $lease['deposit_amount'];
        }
        return Database::insert('inspections', [
            'lease_id'     => (int) $lease['id'],
            'kind'         => $kind,
            'inspected_at' => $kind === 'entree' && $lease['start_date'] >= date('Y-m-d') ? $lease['start_date'] . ' 10:00:00' : null,
            'data'         => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
    }

    /** Données d'un nouvel état des lieux à partir du modèle du logement. */
    private static function fromTemplate(array $t, bool $furnished): array
    {
        $rooms = array_map(fn($g) => ['name' => $g['name'], 'items' => array_map(fn($it) => [
            'label' => $it['label'], 'in_state' => '', 'in_notes' => '', 'out_state' => '', 'out_notes' => '',
        ], $g['items'])], self::parseGroups($t['rooms'], 'Logement'));
        $keys = array_map(fn($it) => ['label' => $it['label'], 'in' => $it['qty'], 'out' => '', 'notes' => ''],
            array_merge(...array_map(fn($g) => $g['items'], self::parseGroups($t['keys'], 'Clés') ?: [['items' => []]])));
        $inventory = $furnished ? array_map(fn($g) => ['name' => $g['name'], 'items' => array_map(fn($it) => [
            'label' => $it['label'], 'in_qty' => $it['qty'], 'in_state' => $it['state'],
            'out_qty' => '', 'out_state' => '', 'notes' => $it['notes'],
        ], $g['items'])], self::parseGroups($t['inventory'])) : [];
        return self::normalize([
            'residence' => $t['residence'], 'designation' => $t['designation'], 'annexe' => $t['annexe'], 'heating' => $t['heating'],
            'rooms' => $rooms, 'keys' => $keys, 'inventory' => $inventory,
        ]);
    }

    /** Données de l'état des lieux de sortie à partir de celui d'entrée (colonnes « Entrée » reprises). */
    private static function fromEntry(array $in): array
    {
        $d = self::normalize([]);
        foreach (['residence', 'designation', 'annexe', 'heating'] as $k) $d[$k] = $in[$k];
        $d['meters']['elec_number']  = $in['meters']['elec_number'];
        $d['meters']['water_number'] = $in['meters']['water_number'];
        $d['meters']['elec_hp_in']   = $in['meters']['elec_hp'];
        $d['meters']['elec_hc_in']   = $in['meters']['elec_hc'];
        $d['meters']['water_in']     = $in['meters']['water'];
        foreach ($in['rooms'] as $r) {
            $d['rooms'][] = ['name' => $r['name'], 'items' => array_map(fn($it) => [
                'label' => $it['label'], 'in_state' => $it['in_state'], 'in_notes' => $it['in_notes'], 'out_state' => '', 'out_notes' => '',
            ], $r['items'])];
        }
        foreach ($in['keys'] as $k) $d['keys'][] = ['label' => $k['label'], 'in' => $k['in'], 'out' => '', 'notes' => ''];
        foreach ($in['inventory'] as $g) {
            $d['inventory'][] = ['name' => $g['name'], 'items' => array_map(fn($it) => [
                'label' => $it['label'], 'in_qty' => $it['in_qty'], 'in_state' => $it['in_state'],
                'out_qty' => '', 'out_state' => '', 'notes' => $it['notes'],
            ], $g['items'])];
        }
        return $d;
    }

    /** Complète les clés manquantes (données anciennes ou partielles). */
    public static function normalize(array $d): array
    {
        $s = fn($v) => is_scalar($v) ? (string) $v : '';
        $out = [
            'residence'   => $s($d['residence'] ?? ''),
            'designation' => $s($d['designation'] ?? ''),
            'annexe'      => $s($d['annexe'] ?? ''),
            'heating'     => $s($d['heating'] ?? ''),
            'meters'      => [],
            'keys'        => [],
            'rooms'       => [],
            'inventory'   => [],
            'cleanliness' => $s($d['cleanliness'] ?? ''),
            'cleanliness_notes' => $s($d['cleanliness_notes'] ?? ''),
            'observations' => $s($d['observations'] ?? ''),
            'tenant'      => [
                'new_address'  => $s($d['tenant']['new_address'] ?? ''),
                'iban'         => $s($d['tenant']['iban'] ?? ''),
                'observations' => $s($d['tenant']['observations'] ?? ''),
            ],
            'deposit'     => [
                'amount'     => $s($d['deposit']['amount'] ?? ''),
                'deductions' => $s($d['deposit']['deductions'] ?? ''),
                'deducted'   => $s($d['deposit']['deducted'] ?? ''),
            ],
        ];
        foreach (['elec_number', 'elec_hp', 'elec_hc', 'elec_hp_in', 'elec_hc_in', 'water_number', 'water', 'water_in', 'date'] as $k) {
            $out['meters'][$k] = $s($d['meters'][$k] ?? '');
        }
        foreach ((array) ($d['keys'] ?? []) as $k) {
            $out['keys'][] = ['label' => $s($k['label'] ?? ''), 'in' => $s($k['in'] ?? ''), 'out' => $s($k['out'] ?? ''), 'notes' => $s($k['notes'] ?? '')];
        }
        foreach ((array) ($d['rooms'] ?? []) as $r) {
            $items = [];
            foreach ((array) ($r['items'] ?? []) as $it) {
                $items[] = ['label' => $s($it['label'] ?? ''), 'in_state' => $s($it['in_state'] ?? ''), 'in_notes' => $s($it['in_notes'] ?? ''),
                            'out_state' => $s($it['out_state'] ?? ''), 'out_notes' => $s($it['out_notes'] ?? '')];
            }
            $out['rooms'][] = ['name' => $s($r['name'] ?? ''), 'items' => $items];
        }
        foreach ((array) ($d['inventory'] ?? []) as $g) {
            $items = [];
            foreach ((array) ($g['items'] ?? []) as $it) {
                $items[] = ['label' => $s($it['label'] ?? ''), 'in_qty' => $s($it['in_qty'] ?? ''), 'in_state' => $s($it['in_state'] ?? ''),
                            'out_qty' => $s($it['out_qty'] ?? ''), 'out_state' => $s($it['out_state'] ?? ''), 'notes' => $s($it['notes'] ?? '')];
            }
            $out['inventory'][] = ['name' => $s($g['name'] ?? ''), 'items' => $items];
        }
        return $out;
    }

    /**
     * Enregistre le formulaire du bailleur. Les lignes existantes sont mises à jour
     * par position ; une ligne libre renseignée devient un nouvel élément.
     */
    public static function saveAdmin(array $insp, array $post): void
    {
        $d = $insp['data'];
        $sortie = $insp['kind'] === 'sortie';
        $txt = fn($v, int $max = 255) => trim(mb_substr(str_replace("\r\n", "\n", is_scalar($v) ? (string) $v : ''), 0, $max));
        $state = fn($v) => isset(self::STATES[(string) $v]) ? (string) $v : '';

        foreach (['residence', 'designation', 'annexe', 'heating'] as $k) $d[$k] = $txt($post[$k] ?? '');
        foreach (array_keys($d['meters']) as $k) {
            if (array_key_exists($k, (array) ($post['meters'] ?? []))) $d['meters'][$k] = $txt($post['meters'][$k], 40);
        }

        // Clés
        $pk = (array) ($post['keys'] ?? []);
        $keys = [];
        foreach ($d['keys'] as $i => $k) {
            $p = (array) ($pk[$i] ?? []);
            $keys[] = ['label' => $k['label'], 'in' => $txt($p['in'] ?? $k['in'], 20),
                       'out' => $sortie ? $txt($p['out'] ?? '', 20) : $k['out'], 'notes' => $txt($p['notes'] ?? '')];
        }
        foreach ($pk['new'] ?? [] as $p) {
            if (($label = $txt($p['label'] ?? '', 200)) === '') continue;
            $keys[] = ['label' => $label, 'in' => $txt($p['in'] ?? '', 20), 'out' => $sortie ? $txt($p['out'] ?? '', 20) : '', 'notes' => $txt($p['notes'] ?? '')];
        }
        $d['keys'] = $keys;

        // Pièces
        $pr = (array) ($post['rooms'] ?? []);
        foreach ($d['rooms'] as $ri => &$room) {
            $items = (array) ($pr[$ri]['items'] ?? []);
            foreach ($room['items'] as $ii => &$it) {
                $p = (array) ($items[$ii] ?? []);
                if ($sortie) {
                    $it['out_state'] = $state($p['state'] ?? '');
                    $it['out_notes'] = $txt($p['notes'] ?? '');
                } else {
                    $it['in_state'] = $state($p['state'] ?? '');
                    $it['in_notes'] = $txt($p['notes'] ?? '');
                }
            }
            unset($it);
            foreach ((array) ($items['new'] ?? []) as $p) {
                if (($label = $txt($p['label'] ?? '', 200)) === '') continue;
                $room['items'][] = ['label' => $label, 'in_state' => $sortie ? '' : $state($p['state'] ?? ''), 'in_notes' => $sortie ? '' : $txt($p['notes'] ?? ''),
                                    'out_state' => $sortie ? $state($p['state'] ?? '') : '', 'out_notes' => $sortie ? $txt($p['notes'] ?? '') : ''];
            }
        }
        unset($room);
        if (($name = $txt($post['new_room']['name'] ?? '', 100)) !== '') {
            $items = array_filter(array_map(fn($l) => $txt($l, 200), preg_split('/\R/', (string) ($post['new_room']['items'] ?? ''))));
            $d['rooms'][] = ['name' => $name, 'items' => array_values(array_map(fn($l) => [
                'label' => $l, 'in_state' => '', 'in_notes' => '', 'out_state' => '', 'out_notes' => '',
            ], $items))];
        }

        // Inventaire
        $pi = (array) ($post['inventory'] ?? []);
        foreach ($d['inventory'] as $gi => &$g) {
            $items = (array) ($pi[$gi]['items'] ?? []);
            foreach ($g['items'] as $ii => &$it) {
                $p = (array) ($items[$ii] ?? []);
                $it['in_qty']   = $txt($p['in_qty'] ?? $it['in_qty'], 20);
                $it['in_state'] = $state($p['in_state'] ?? $it['in_state']);
                if ($sortie) {
                    $it['out_qty']   = $txt($p['out_qty'] ?? '', 20);
                    $it['out_state'] = $state($p['out_state'] ?? '');
                }
                $it['notes'] = $txt($p['notes'] ?? '');
            }
            unset($it);
            foreach ((array) ($items['new'] ?? []) as $p) {
                if (($label = $txt($p['label'] ?? '', 200)) === '') continue;
                $g['items'][] = ['label' => $label, 'in_qty' => $txt($p['in_qty'] ?? '', 20), 'in_state' => $state($p['in_state'] ?? ''),
                                 'out_qty' => $sortie ? $txt($p['out_qty'] ?? '', 20) : '', 'out_state' => $sortie ? $state($p['out_state'] ?? '') : '',
                                 'notes' => $txt($p['notes'] ?? '')];
            }
        }
        unset($g);

        $d['cleanliness'] = in_array($post['cleanliness'] ?? '', ['propre', 'a_nettoyer'], true) ? $post['cleanliness'] : '';
        $d['cleanliness_notes'] = $txt($post['cleanliness_notes'] ?? '');
        $d['observations'] = $txt($post['observations'] ?? '', 5000);
        if ($sortie) {
            $d['deposit']['amount']     = $txt($post['deposit']['amount'] ?? '', 20);
            $d['deposit']['deductions'] = $txt($post['deposit']['deductions'] ?? '', 2000);
            $d['deposit']['deducted']   = $txt($post['deposit']['deducted'] ?? '', 20);
            $d['tenant']['new_address'] = $txt($post['tenant']['new_address'] ?? '', 500);
            $d['tenant']['iban']        = self::cleanIban($post['tenant']['iban'] ?? '');
        }

        $at = (string) ($post['inspected_at'] ?? '');
        $at = preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $at) ? str_replace('T', ' ', $at) . ':00' : null;
        Database::update('inspections', ['inspected_at' => $at, 'data' => json_encode($d, JSON_UNESCAPED_UNICODE)],
            'id = :id', ['id' => (int) $insp['id']]);
    }

    /** Ce que le locataire complète lui-même : nouvelle adresse, IBAN (sortie) et ses réserves. */
    public static function saveTenant(array $insp, array $post): void
    {
        $d = $insp['data'];
        $txt = fn($v, int $max) => trim(mb_substr(str_replace("\r\n", "\n", is_scalar($v) ? (string) $v : ''), 0, $max));
        if ($insp['kind'] === 'sortie') {
            $d['tenant']['new_address'] = $txt($post['new_address'] ?? '', 500);
            $d['tenant']['iban'] = self::cleanIban($post['iban'] ?? '');
        }
        $d['tenant']['observations'] = $txt($post['tenant_observations'] ?? '', 3000);
        Database::update('inspections', ['data' => json_encode($d, JSON_UNESCAPED_UNICODE)], 'id = :id', ['id' => (int) $insp['id']]);
    }

    /** IBAN en majuscules, par groupes de 4. */
    public static function cleanIban($v): string
    {
        $v = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', is_scalar($v) ? (string) $v : ''));
        return trim(chunk_split(substr($v, 0, 34), 4, ' '));
    }

    /** Montant à restituer (dépôt − retenues), ou null si le dépôt n'est pas renseigné. */
    public static function refund(array $d): ?float
    {
        if (trim($d['deposit']['amount']) === '') return null;
        return max(0.0, num($d['deposit']['amount']) - num($d['deposit']['deducted']));
    }

    /** [éléments renseignés, éléments à renseigner] pour la colonne de ce type d'état des lieux. */
    public static function progress(array $insp): array
    {
        $col = $insp['kind'] === 'sortie' ? 'out_state' : 'in_state';
        $done = $total = 0;
        foreach ($insp['data']['rooms'] as $r) foreach ($r['items'] as $it) { $total++; if ($it[$col] !== '') $done++; }
        foreach ($insp['data']['inventory'] as $g) foreach ($g['items'] as $it) {
            $total++;
            if ($insp['kind'] === 'sortie' ? $it['out_state'] !== '' : $it['in_state'] !== '') $done++;
        }
        return [$done, $total];
    }

    public static function setShared(int $id, bool $shared): void
    {
        Database::update('inspections', ['shared_at' => $shared ? date('Y-m-d H:i:s') : null], 'id = :id', ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM inspection_signatures WHERE inspection_id = ?', [$id]);
        Database::query('DELETE FROM inspection_signed_pdf WHERE inspection_id = ?', [$id]);
        Database::query('DELETE FROM inspections WHERE id = ?', [$id]);
    }

    /** Titre court, ex. « État des lieux de sortie ». */
    public static function title(array $insp): string
    {
        return self::KINDS[$insp['kind']] ?? 'État des lieux';
    }

    /** Pied de page du PDF, ex. « État des lieux de sortie — Studio Les Airelles — 73 av. …, 27930 Gravigny ». */
    public static function footer(array $insp, array $lease): string
    {
        $adresse = trim(($lease['address'] ?? '') . ', ' . ($lease['postal_code'] ?? '') . ' ' . ($lease['city'] ?? ''), ', ');
        return implode(' — ', array_filter([self::title($insp), (string) $lease['property_label'], $adresse], 'strlen'));
    }

    /** Nom du fichier PDF. */
    public static function filename(array $insp, array $lease, bool $signed = false): string
    {
        $name = preg_replace('/[^A-Za-z0-9-]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', (string) $lease['last_name']) ?: '');
        return 'etat-des-lieux-' . $insp['kind'] . ($name ? '-' . strtolower(trim($name, '-')) : '') . ($signed ? '-signe' : '') . '.pdf';
    }

    /* ---------------------------------------------------------------------
     * Signatures
     * ------------------------------------------------------------------- */

    /** Variables du gabarit du document. */
    public static function docData(array $insp, array $lease, array $settings, bool $forHash = false): array
    {
        return ['insp' => $insp, 'lease' => $lease, 'settings' => $settings, 'forHash' => $forHash];
    }

    /**
     * Empreinte du document (rendu sans les signatures).
     * La signature du bailleur ne couvre pas ce que le locataire saisit lui-même
     * (réserves, nouvelle adresse, IBAN) : le locataire peut les compléter en
     * signant après le bailleur sans rendre caduque la signature de celui-ci.
     * La signature du locataire couvre l'ensemble du document.
     */
    public static function hash(array $insp, array $lease, array $settings, string $role): string
    {
        if ($role === 'bailleur') {
            $insp['data']['tenant'] = array_map(fn() => '', $insp['data']['tenant']);
        }
        return hash('sha256', render_template('documents/etat_des_lieux', self::docData($insp, $lease, $settings, true)));
    }

    public static function signatures(int $id): array
    {
        $out = [];
        foreach (Database::all('SELECT * FROM inspection_signatures WHERE inspection_id = ?', [$id]) as $r) $out[$r['role']] = $r;
        return $out;
    }

    /** Signatures valables pour la version actuelle du document. */
    public static function valid(array $insp, array $lease, array $settings): array
    {
        $all = self::signatures((int) $insp['id']);
        if (!$all) return [];
        static $cache = [];
        $key = md5(serialize([$insp, $lease, $settings, $all]));
        if (!isset($cache[$key])) {
            $cache[$key] = array_filter($all, fn($s, $role) => isset(self::ROLES[$role])
                && hash_equals($s['doc_hash'], self::hash($insp, $lease, $settings, $role)), ARRAY_FILTER_USE_BOTH);
        }
        return $cache[$key];
    }

    public static function isComplete(array $insp, array $lease, array $settings): bool
    {
        return count(self::valid($insp, $lease, $settings)) === count(self::ROLES);
    }

    /**
     * Enregistre la signature d'une partie ; archive le PDF quand les deux ont signé.
     * @return bool true si l'état des lieux est désormais signé par les deux parties
     * @throws RuntimeException
     */
    public static function sign(array $insp, array $lease, string $role, string $name, string $image): bool
    {
        if (!isset(self::ROLES[$role])) throw new RuntimeException('Rôle de signataire inconnu.');
        $name = trim(mb_substr($name, 0, 150));
        if ($name === '') throw new RuntimeException('Indiquez le nom du signataire.');
        $prefix = 'data:image/png;base64,';
        $raw = str_starts_with($image, $prefix) ? base64_decode(substr($image, strlen($prefix)), true) : false;
        if ($raw === false || substr($raw, 0, 8) !== "\x89PNG\r\n\x1a\n" || strlen($image) > 400000) {
            throw new RuntimeException('Signature invalide : merci de la redessiner.');
        }
        if (empty($insp['inspected_at'])) {
            Database::update('inspections', ['inspected_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => (int) $insp['id']]);
            $insp = self::find((int) $insp['id']);
        }
        $settings = Setting::all();
        Database::query('DELETE FROM inspection_signatures WHERE inspection_id = ? AND role = ?', [(int) $insp['id'], $role]);
        Database::insert('inspection_signatures', [
            'inspection_id' => (int) $insp['id'],
            'role'          => $role,
            'signer_name'   => $name,
            'image'         => $image,
            'signed_at'     => date('Y-m-d H:i:s'),
            'ip'            => substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
            'user_agent'    => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            'doc_hash'      => self::hash($insp, $lease, $settings, $role),
        ]);
        if (!self::isComplete($insp, $lease, $settings)) return false;

        if (Pdf::available()) {
            $pdf = Pdf::renderDocument('documents/etat_des_lieux', self::docData($insp, $lease, $settings), self::footer($insp, $lease));
            Database::query('DELETE FROM inspection_signed_pdf WHERE inspection_id = ?', [(int) $insp['id']]);
            Database::insert('inspection_signed_pdf', ['inspection_id' => (int) $insp['id'], 'pdf' => $pdf, 'sha256' => hash('sha256', $pdf)]);
        }
        // Checklist du bail : état des lieux d'entrée réalisé et signé.
        if ($insp['kind'] === 'entree' && !in_array('etat_lieux_entree', Checklist::checkedKeys((int) $lease['id']), true)) {
            Database::query("INSERT INTO lease_checklist (lease_id, item_key) VALUES (?, 'etat_lieux_entree')", [(int) $lease['id']]);
        }
        return true;
    }

    public static function signedPdf(int $id): ?array
    {
        return Database::one('SELECT * FROM inspection_signed_pdf WHERE inspection_id = ?', [$id]);
    }

    /** PDF à remettre : version signée archivée si complète, sinon rendu actuel. */
    public static function pdf(array $insp, array $lease, array $settings): array
    {
        $signed = self::isComplete($insp, $lease, $settings) ? self::signedPdf((int) $insp['id']) : null;
        return $signed
            ? [$signed['pdf'], self::filename($insp, $lease, true)]
            : [Pdf::renderDocument('documents/etat_des_lieux', self::docData($insp, $lease, $settings), self::footer($insp, $lease)), self::filename($insp, $lease)];
    }

    public static function resetSignatures(int $id): void
    {
        Database::query('DELETE FROM inspection_signatures WHERE inspection_id = ?', [$id]);
    }
}

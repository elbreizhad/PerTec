<?php
declare(strict_types=1);

/* =========================================================================
 * AUTHENTIFICATION
 * ========================================================================= */

App::get('/login', function () {
    if (Auth::check()) redirect('/');
    view('login', [], 'layout_bare');
});

App::post('/login', function () {
    csrf_check();
    if (Auth::attempt((string) post('username'), (string) post('password'))) {
        redirect('/');
    }
    flash('Identifiants incorrects.', 'error');
    view('login', [], 'layout_bare');
});

App::get('/logout', function () {
    Auth::logout();
    redirect('/login');
});

/* =========================================================================
 * TABLEAU DE BORD
 * ========================================================================= */

App::get('/', function () {
    Auth::requireLogin();
    $properties = Property::all();
    $year = (int) date('Y');

    $totals = ['cost' => 0.0, 'rent_month' => 0.0, 'cashflow' => 0.0];
    $rows = [];
    foreach ($properties as $p) {
        $ind = Finance::indicators($p);
        $totals['cost']       += $ind['total_cost'];
        $totals['rent_month'] += $ind['monthly_rent'];
        $totals['cashflow']   += $ind['cashflow_monthly'];
        $rows[] = ['p' => $p, 'ind' => $ind];
    }
    $stats = Payment::yearStats($year);

    view('dashboard', [
        'rows'    => $rows,
        'totals'  => $totals,
        'stats'   => $stats,
        'year'    => $year,
        'monthly' => Payment::monthlyPaid($year),
        'nbTenants' => count(Tenant::all()),
        'nbLeases'  => count(array_filter(Lease::all(), fn($l) => $l['status'] === 'active')),
    ]);
});

/* =========================================================================
 * BIENS
 * ========================================================================= */

App::get('/biens', function () {
    Auth::requireLogin();
    $properties = array_map(function ($p) {
        $p['_ind'] = Finance::indicators($p);
        return $p;
    }, Property::all());
    view('properties/index', ['properties' => $properties]);
});

App::get('/biens/new', function () {
    Auth::requireLogin();
    view('properties/form', ['property' => null]);
});

App::post('/biens', function () {
    Auth::requireLogin();
    csrf_check();
    $id = Property::create(Property::fromRequest());
    flash('Bien créé.');
    redirect('/biens/' . $id);
});

App::get('/biens/{id}', function ($params) {
    Auth::requireLogin();
    $property = Property::find((int) $params['id']);
    if (!$property) { redirect('/biens'); }
    view('properties/show', [
        'property' => $property,
        'ind'      => Finance::indicators($property),
        'leases'   => Lease::forProperty((int) $property['id']),
    ]);
});

App::get('/biens/{id}/edit', function ($params) {
    Auth::requireLogin();
    $property = Property::find((int) $params['id']);
    if (!$property) { redirect('/biens'); }
    view('properties/form', ['property' => $property]);
});

App::post('/biens/{id}', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $id = (int) $params['id'];
    Property::update($id, Property::fromRequest());
    flash('Bien mis à jour.');
    redirect('/biens/' . $id);
});

App::post('/biens/{id}/delete', function ($params) {
    Auth::requireLogin();
    csrf_check();
    Property::delete((int) $params['id']);
    flash('Bien supprimé.');
    redirect('/biens');
});

/* =========================================================================
 * DÉPENSES DÉTAILLÉES D'UN BIEN
 * ========================================================================= */

App::post('/biens/{id}/depenses', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $pid = (int) $params['id'];
    if (Property::find($pid)) {
        Expense::create(Expense::fromRequest($pid));
        flash('Dépense ajoutée.');
    }
    redirect('/biens/' . $pid);
});

App::post('/depenses/{id}/delete', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $exp = Expense::find((int) $params['id']);
    Expense::delete((int) $params['id']);
    flash('Dépense supprimée.');
    redirect('/biens/' . (int) ($exp['property_id'] ?? 0));
});

/* =========================================================================
 * FISCALITÉ LMNP
 * ========================================================================= */

App::get('/fiscalite', function () {
    Auth::requireLogin();
    $year = (int) ($_GET['year'] ?? date('Y'));
    $rows = [];
    foreach (Property::all() as $p) {
        $rows[] = ['p' => $p, 'lmnp' => Lmnp::compute($p, $year)];
    }
    view('lmnp/index', ['rows' => $rows, 'year' => $year]);
});

App::get('/biens/{id}/fiscalite', function ($params) {
    Auth::requireLogin();
    $property = Property::find((int) $params['id']);
    if (!$property) redirect('/biens');
    $year = (int) ($_GET['year'] ?? date('Y'));
    view('lmnp/show', [
        'property' => $property,
        'lmnp'     => Lmnp::compute($property, $year),
        'year'     => $year,
    ]);
});

/* =========================================================================
 * LOCATAIRES
 * ========================================================================= */

App::get('/locataires', function () {
    Auth::requireLogin();
    view('tenants/index', ['tenants' => Tenant::all()]);
});

App::get('/locataires/new', function () {
    Auth::requireLogin();
    view('tenants/form', ['tenant' => null]);
});

App::post('/locataires', function () {
    Auth::requireLogin();
    csrf_check();
    Tenant::create(Tenant::fromRequest());
    flash('Locataire ajouté.');
    redirect('/locataires');
});

App::get('/locataires/{id}/edit', function ($params) {
    Auth::requireLogin();
    $tenant = Tenant::find((int) $params['id']);
    if (!$tenant) redirect('/locataires');
    view('tenants/form', ['tenant' => $tenant]);
});

App::post('/locataires/{id}', function ($params) {
    Auth::requireLogin();
    csrf_check();
    Tenant::update((int) $params['id'], Tenant::fromRequest());
    flash('Locataire mis à jour.');
    redirect('/locataires');
});

App::post('/locataires/{id}/delete', function ($params) {
    Auth::requireLogin();
    csrf_check();
    Tenant::delete((int) $params['id']);
    flash('Locataire supprimé.');
    redirect('/locataires');
});

/* =========================================================================
 * BAUX
 * ========================================================================= */

App::get('/baux', function () {
    Auth::requireLogin();
    view('leases/index', ['leases' => Lease::all()]);
});

App::get('/baux/new', function () {
    Auth::requireLogin();
    view('leases/form', [
        'lease'      => null,
        'properties' => Property::all(),
        'tenants'    => Tenant::all(),
    ]);
});

App::post('/baux', function () {
    Auth::requireLogin();
    csrf_check();
    $id = Lease::create(Lease::fromRequest());
    flash('Bail créé.');
    redirect('/baux/' . $id);
});

App::get('/baux/{id}', function ($params) {
    Auth::requireLogin();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    view('leases/show', [
        'lease'    => $lease,
        'payments' => Payment::forLease((int) $lease['id']),
        'settings' => Setting::all(),
    ]);
});

App::get('/baux/{id}/edit', function ($params) {
    Auth::requireLogin();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    view('leases/form', [
        'lease'      => $lease,
        'properties' => Property::all(),
        'tenants'    => Tenant::all(),
    ]);
});

App::post('/baux/{id}', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $id = (int) $params['id'];
    Lease::update($id, Lease::fromRequest());
    flash('Bail mis à jour.');
    redirect('/baux/' . $id);
});

App::post('/baux/{id}/delete', function ($params) {
    Auth::requireLogin();
    csrf_check();
    Lease::delete((int) $params['id']);
    flash('Bail supprimé.');
    redirect('/baux');
});

// Enregistrer l'inventaire du mobilier (annexe 1 du bail meublé)
App::post('/baux/{id}/inventaire', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $lease = Lease::find((int) $params['id']);
    if ($lease) {
        Inventory::save($lease, (array) post('present', []), (array) post('inv_notes', []), (array) post('inv_label', []));
        flash('Inventaire enregistré : il apparaît coché dans l\'annexe 1 du contrat.');
    }
    redirect('/baux/' . (int) $params['id'] . '#inventaire');
});

// Enregistrer la checklist de conformité d'un bail
App::post('/baux/{id}/checklist', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $id = (int) $params['id'];
    if (Lease::find($id)) {
        Checklist::save($id, (array) post('items', []));
        flash('Checklist enregistrée.');
    }
    redirect('/baux/' . $id);
});

// Générer une échéance manuelle pour un bail (mois/année choisis)
App::post('/baux/{id}/echeance', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $lease = Lease::find((int) $params['id']);
    if ($lease) {
        $y = (int) post('year', date('Y'));
        $m = (int) post('month', date('n'));
        Payment::createForPeriod($lease, $y, $m) !== null
            ? flash('Échéance ajoutée.')
            : flash('Cette échéance existe déjà.', 'error');
    }
    redirect('/baux/' . (int) $params['id']);
});

/* =========================================================================
 * LOYERS / QUITTANCES
 * ========================================================================= */

App::get('/loyers', function () {
    Auth::requireLogin();
    $year = (int) ($_GET['year'] ?? date('Y'));
    view('payments/index', [
        'payments' => Payment::overview($year),
        'year'     => $year,
        'stats'    => Payment::yearStats($year),
    ]);
});

// Générer automatiquement toutes les échéances dues
App::post('/loyers/generer', function () {
    Auth::requireLogin();
    csrf_check();
    $n = Payment::generateDue();
    flash($n > 0 ? "$n échéance(s) générée(s)." : 'Aucune nouvelle échéance à générer.');
    redirect('/loyers');
});

App::post('/loyers/{id}/paye', function ($params) {
    Auth::requireLogin();
    csrf_check();
    Payment::markPaid((int) $params['id'], post('paid_date') ?: null, post('payment_method') ?: null);
    flash('Loyer marqué comme payé.');
    redirect($_SERVER['HTTP_REFERER'] ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) . (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY) ? '?' . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY) : '') : '/loyers');
});

App::post('/loyers/{id}/annuler', function ($params) {
    Auth::requireLogin();
    csrf_check();
    Payment::markPending((int) $params['id']);
    flash('Paiement annulé.');
    redirect('/loyers');
});

// Modifier le montant d'une échéance (prorata de fin/début de bail, ajustement)
App::get('/loyers/{id}/modifier', function ($params) {
    Auth::requireLogin();
    $payment = Payment::find((int) $params['id']);
    if (!$payment) redirect('/loyers');
    $lease = Lease::find((int) $payment['lease_id']);
    view('payments/edit', [
        'payment' => $payment,
        'lease'   => $lease,
        'prorata' => Payment::prorata($lease, (int) $payment['period_year'], (int) $payment['period_month']),
    ]);
});

App::post('/loyers/{id}/modifier', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $id = (int) $params['id'];
    $payment = Payment::find($id);
    if (!$payment) redirect('/loyers');
    Payment::updateAmounts($id, num(post('amount_rent')), num(post('amount_charges')), post('notes'));
    flash('Échéance mise à jour.');
    redirect('/baux/' . (int) $payment['lease_id']);
});

App::post('/loyers/{id}/delete', function ($params) {
    Auth::requireLogin();
    csrf_check();
    Payment::delete((int) $params['id']);
    flash('Échéance supprimée.');
    redirect('/loyers');
});

// Quittance imprimable (page HTML avec bouton d'impression)
App::get('/quittance/{id}', function ($params) {
    Auth::requireLogin();
    $payment = Payment::find((int) $params['id']);
    if (!$payment) redirect('/loyers');
    $lease = Lease::find((int) $payment['lease_id']);
    view('documents/quittance', [
        'payment'  => $payment,
        'lease'    => $lease,
        'settings' => Setting::all(),
    ], 'layout_print');
});

// Quittance en PDF (téléchargement 1 clic via Dompdf)
App::get('/quittance/{id}/pdf', function ($params) {
    Auth::requireLogin();
    $id = (int) $params['id'];
    $payment = Payment::find($id);
    if (!$payment) redirect('/loyers');
    if (!Pdf::available()) {
        flash('Librairie PDF indisponible sur le serveur.', 'error');
        redirect('/quittance/' . $id);
    }
    $lease = Lease::find((int) $payment['lease_id']);
    $filename = 'quittance-' . ($payment['receipt_number'] ?: $id) . '.pdf';
    Pdf::streamDocument('documents/quittance', [
        'payment'  => $payment,
        'lease'    => $lease,
        'settings' => Setting::all(),
        'forPdf'   => true,
    ], $filename);
});

/* =========================================================================
 * CONTRAT DE BAIL (imprimable)
 * ========================================================================= */

App::get('/contrat/{id}', function ($params) {
    Auth::requireLogin();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    $settings = Setting::all();
    // Bail meublé (LMNP) : modèle dédié + garde-fou anti-génération partielle.
    if ($lease['lease_type'] === 'meuble') {
        $issues = Lease::contractIssues($lease, $settings);
        if ($issues['blocking']) {
            flash('Bail incomplet — corrigez avant génération : ' . implode(' · ', $issues['blocking']), 'error');
            redirect('/baux/' . (int) $params['id']);
        }
    }
    $template = $lease['lease_type'] === 'meuble' ? 'documents/contrat_meuble' : 'documents/contrat';
    view($template, ['lease' => $lease, 'settings' => $settings], 'layout_print');
});

// Contrat de bail en PDF (téléchargement 1 clic via Dompdf)
App::get('/contrat/{id}/pdf', function ($params) {
    Auth::requireLogin();
    $id = (int) $params['id'];
    $lease = Lease::find($id);
    if (!$lease) redirect('/baux');
    if (!Pdf::available()) {
        flash('Librairie PDF indisponible sur le serveur.', 'error');
        redirect('/contrat/' . $id);
    }
    $meuble = $lease['lease_type'] === 'meuble';
    $settings = Setting::all();
    // Garde-fou : pas de PDF de bail meublé avec des champs obligatoires vides.
    if ($meuble) {
        $issues = Lease::contractIssues($lease, $settings);
        if ($issues['blocking']) {
            flash('Bail incomplet — corrigez avant génération : ' . implode(' · ', $issues['blocking']), 'error');
            redirect('/baux/' . $id);
        }
    }
    $template = $meuble ? 'documents/contrat_meuble' : 'documents/contrat';
    $filename = ($meuble ? 'bail-meuble-' : 'bail-') . $id . '.pdf';
    Pdf::streamDocument($template, [
        'lease'    => $lease,
        'settings' => $settings,
    ], $filename);
});

/* =========================================================================
 * ACTE DE CAUTIONNEMENT SOLIDAIRE (garant)
 * ========================================================================= */

// Prépare les données d'un acte pour le garant n° {1|2} sans modifier le
// gabarit de l'acte : on recopie le garant sélectionné dans les clés guarantor_*.
$cautionLease = function (array $lease, int $g): ?array {
    $guarants = Lease::guarantors($lease);
    $idx = $g === 2 ? 1 : 0;
    if (!isset($guarants[$idx])) return null;
    foreach ($guarants[$idx] as $k => $v) {
        $lease['guarantor_' . $k] = $v;
    }
    return $lease;
};

// Acte de cautionnement imprimable (page HTML avec bouton d'impression)
App::get('/caution/{id}', function ($params) use ($cautionLease) {
    Auth::requireLogin();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    $g = (int) ($_GET['g'] ?? 1);
    $data = $cautionLease($lease, $g);
    if (!$data) {
        flash('Renseignez d\'abord un garant sur ce bail.', 'error');
        redirect('/baux/' . (int) $params['id']);
    }
    view('documents/cautionnement', [
        'lease'    => $data,
        'settings' => Setting::all(),
    ], 'layout_print');
});

// Acte de cautionnement en PDF (téléchargement 1 clic via Dompdf)
App::get('/caution/{id}/pdf', function ($params) use ($cautionLease) {
    Auth::requireLogin();
    $id = (int) $params['id'];
    $lease = Lease::find($id);
    if (!$lease) redirect('/baux');
    $g = (int) ($_GET['g'] ?? 1);
    $data = $cautionLease($lease, $g);
    if (!$data) {
        flash('Renseignez d\'abord un garant sur ce bail.', 'error');
        redirect('/baux/' . $id);
    }
    if (!Pdf::available()) {
        flash('Librairie PDF indisponible sur le serveur.', 'error');
        redirect('/caution/' . $id);
    }
    Pdf::streamDocument('documents/cautionnement', [
        'lease'    => $data,
        'settings' => Setting::all(),
    ], 'acte-cautionnement-' . $id . ($g === 2 ? '-2' : '') . '.pdf');
});

/* =========================================================================
 * PARAMÈTRES (bailleur + mot de passe)
 * ========================================================================= */

App::get('/parametres', function () {
    Auth::requireLogin();
    view('settings', ['settings' => Setting::all(), 'user' => Auth::user()]);
});

App::post('/parametres', function () {
    Auth::requireLogin();
    csrf_check();
    Setting::saveMany([
        'landlord_name'    => post('landlord_name'),
        'landlord_address' => post('landlord_address'),
        'landlord_city'    => post('landlord_city'),
        'landlord_email'   => post('landlord_email'),
        'landlord_phone'   => post('landlord_phone'),
        'landlord_siret'   => post('landlord_siret'),
        'signature_city'   => post('signature_city'),
        'irl_quarter'      => post('irl_quarter'),
        'irl_year'         => post('irl_year'),
    ]);
    flash('Paramètres enregistrés.');
    redirect('/parametres');
});

// Envoi de la quittance (PDF en pièce jointe) par email au locataire
App::post('/quittance/{id}/email', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $id = (int) $params['id'];
    $payment = Payment::find($id);
    if (!$payment) redirect('/loyers');
    if ($payment['status'] !== 'paid') {
        flash("Marquez d'abord ce loyer comme payé avant d'envoyer la quittance.", 'error');
        redirect('/quittance/' . $id);
    }
    if (!Pdf::available()) {
        flash('Librairie PDF indisponible sur le serveur.', 'error');
        redirect('/quittance/' . $id);
    }
    $lease = Lease::find((int) $payment['lease_id']);
    $settings = Setting::all();
    $to = trim((string) post('to'));
    $pdf = Pdf::renderDocument('documents/quittance', [
        'payment'  => $payment,
        'lease'    => $lease,
        'settings' => $settings,
        'forPdf'   => true,
    ]);
    try {
        $copy = post('copy') ? QuittanceMail::copyAddress($settings) : null;
        Mailer::send($to, (string) post('subject'), (string) post('message'), [[
            'name' => QuittanceMail::attachmentName($payment),
            'type' => 'application/pdf',
            'data' => $pdf,
        ]], null, $copy);
        Database::update('rent_payments', ['emailed_at' => date('Y-m-d H:i:s'), 'emailed_to' => $to], 'id = :id', ['id' => $id]);
        flash("Quittance envoyée à $to." . ($copy ? " Une copie vous a été envoyée ($copy)." : ''));
    } catch (Throwable $e) {
        flash("Échec de l'envoi : " . $e->getMessage(), 'error');
    }
    redirect('/quittance/' . $id);
});

// Réglages d'envoi des emails (stockés en base : non écrasés par les déploiements)
App::post('/parametres/email', function () {
    Auth::requireLogin();
    csrf_check();
    $secure = post('smtp_secure');
    Setting::saveMany([
        'smtp_host'      => trim((string) post('smtp_host')),
        'smtp_port'      => trim((string) post('smtp_port')),
        'smtp_secure'    => in_array($secure, ['ssl', 'tls', 'none'], true) ? $secure : 'ssl',
        'smtp_user'      => trim((string) post('smtp_user')),
        'mail_from'      => trim((string) post('mail_from')),
    ]);
    // Mot de passe : champ vide = on garde l'actuel.
    if ((string) post('smtp_pass') !== '') {
        $pass = trim((string) post('smtp_pass'));
        // Mot de passe d'application Google : affiché « abcd efgh ijkl mnop », à saisir sans espaces.
        if (stripos((string) post('smtp_host'), 'gmail.com') !== false) {
            $pass = str_replace(' ', '', $pass);
        }
        Setting::set('smtp_pass', $pass);
    }
    if (post('action') === 'test') {
        $s = Setting::all();
        $to = trim((string) ($s['landlord_email'] ?? '')) ?: trim((string) ($s['mail_from'] ?? ''));
        try {
            Mailer::send($to, 'Email de test — envoi des quittances', "Bonjour,\n\nCet email confirme que l'envoi des quittances fonctionne.\n");
            flash("Réglages enregistrés. Email de test envoyé à $to.");
        } catch (Throwable $e) {
            flash("Réglages enregistrés, mais le test a échoué : " . $e->getMessage(), 'error');
        }
    } else {
        flash("Réglages d'envoi enregistrés.");
    }
    redirect('/parametres');
});

// Modèle de l'email de quittance (objet, message, copie)
App::post('/parametres/modele-email', function () {
    Auth::requireLogin();
    csrf_check();
    if (post('action') === 'reset') {
        Setting::saveMany(['mail_quittance_subject' => null, 'mail_quittance_body' => null]);
        flash('Modèle d\'email remis par défaut.');
        redirect('/parametres#modele-email');
    }
    Setting::saveMany([
        'mail_from_name'         => trim((string) post('mail_from_name')),
        'mail_quittance_subject' => trim((string) post('mail_quittance_subject')),
        'mail_quittance_body'    => str_replace("\r\n", "\n", trim((string) post('mail_quittance_body'))),
        'mail_quittance_copy'    => post('mail_quittance_copy') ? '1' : '0',
    ]);
    flash('Modèle d\'email enregistré.');
    redirect('/parametres#modele-email');
});

// Signature manuscrite du bailleur (dessinée à la souris), ajoutée automatiquement aux quittances
App::post('/signature', function () {
    Auth::requireLogin();
    csrf_check();
    $back = (string) post('back', '/parametres');
    if (!preg_match('#^/[A-Za-z0-9/_-]*$#', $back)) $back = '/parametres';

    if (post('action') === 'delete') {
        Setting::set('landlord_signature', null);
        flash('Signature supprimée.');
        redirect($back);
    }

    $data = (string) post('signature');
    $prefix = 'data:image/png;base64,';
    $raw = str_starts_with($data, $prefix) ? base64_decode(substr($data, strlen($prefix)), true) : false;
    if ($raw === false || substr($raw, 0, 8) !== "\x89PNG\r\n\x1a\n") {
        flash('Signature invalide, veuillez la redessiner.', 'error');
    } elseif (strlen($data) > 60000) {
        flash('Signature trop volumineuse, veuillez la redessiner plus simplement.', 'error');
    } else {
        Setting::set('landlord_signature', $data);
        flash('Signature enregistrée : elle sera ajoutée automatiquement sur les quittances.');
    }
    redirect($back);
});

App::post('/parametres/motdepasse', function () {
    Auth::requireLogin();
    csrf_check();
    $current = (string) post('current_password');
    $new     = (string) post('new_password');
    $user = Database::one('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
    if (!$user || !password_verify($current, $user['password_hash'])) {
        flash('Mot de passe actuel incorrect.', 'error');
    } elseif (strlen($new) < 6) {
        flash('Le nouveau mot de passe doit faire au moins 6 caractères.', 'error');
    } else {
        Database::update('users', ['password_hash' => password_hash($new, PASSWORD_DEFAULT)], 'id = :id', ['id' => $user['id']]);
        flash('Mot de passe modifié.');
    }
    redirect('/parametres');
});

/* =========================================================================
 * DOCUMENTS LÉGAUX DU LOGEMENT
 * ========================================================================= */

/** Envoie un fichier binaire au navigateur (affichage ou téléchargement). */
$sendFile = function (string $data, string $mime, string $name, bool $inline = false): void {
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . strlen($data));
    header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . str_replace('"', '', $name) . '"');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, no-store');
    echo $data;
    exit;
};

App::post('/biens/{id}/documents', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $id = (int) $params['id'];
    if (!Property::find($id)) redirect('/biens');
    try {
        PropertyDocument::store($id, (string) post('doc_type'), post('title'), post('doc_date'), $_FILES['file'] ?? []);
        flash('Document ajouté.');
    } catch (Throwable $e) {
        flash('Document non ajouté : ' . $e->getMessage(), 'error');
    }
    redirect('/biens/' . $id . '#documents');
});

App::get('/biens/{id}/documents/{doc}', function ($params) use ($sendFile) {
    Auth::requireLogin();
    $doc = PropertyDocument::find((int) $params['doc']);
    if (!$doc || (int) $doc['property_id'] !== (int) $params['id']) redirect('/biens/' . (int) $params['id']);
    $sendFile($doc['content'], $doc['mime'], $doc['filename'], true);
});

App::post('/biens/{id}/documents/{doc}/delete', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $doc = PropertyDocument::find((int) $params['doc']);
    if ($doc && (int) $doc['property_id'] === (int) $params['id']) {
        PropertyDocument::delete((int) $doc['id']);
        flash('Document supprimé.');
    }
    redirect('/biens/' . (int) $params['id'] . '#documents');
});

/* =========================================================================
 * LIASSE LOCATAIRE (bail + annexes + documents légaux)
 * ========================================================================= */

App::get('/baux/{id}/liasse', function ($params) use ($sendFile) {
    Auth::requireLogin();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    if (!Pdf::available() || !class_exists('ZipArchive')) {
        flash('Génération impossible : extension PDF ou ZIP absente du serveur.', 'error');
        redirect('/baux/' . (int) $lease['id']);
    }
    $files = Liasse::files($lease, Setting::all());
    $sendFile(Liasse::zip($files), 'application/zip', Liasse::zipName($lease));
});

App::post('/baux/{id}/liasse/email', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    $settings = Setting::all();
    $to = trim((string) post('to'));
    try {
        $files = Liasse::files($lease, $settings);
        $total = array_sum(array_map(fn($f) => strlen($f['data']), $files));
        if ($total > 18 * 1024 * 1024) {
            throw new RuntimeException('documents trop volumineux pour un email (' . round($total / 1048576, 1) . ' Mo) : téléchargez le ZIP et transmettez-le autrement.');
        }
        $list = implode("\n", array_map(fn($f) => '- ' . $f['label'], $files));
        $body = trim((string) post('message')) . "\n\nDocuments joints :\n" . $list . "\n";
        Mailer::send($to, (string) post('subject'), $body, $files, null,
            post('copy') ? QuittanceMail::copyAddress($settings) : null);
        flash("Dossier envoyé à $to (" . count($files) . ' documents).');
    } catch (Throwable $e) {
        flash("Échec de l'envoi : " . $e->getMessage(), 'error');
    }
    redirect('/baux/' . (int) $lease['id'] . '#liasse');
});

/* =========================================================================
 * SIGNATURE ÉLECTRONIQUE DU BAIL
 * ========================================================================= */

// Page de signature (bailleur, ou locataire présent sur cet appareil)
App::get('/baux/{id}/signer/{role}', function ($params) {
    Auth::requireLogin();
    $lease = Lease::find((int) $params['id']);
    $role = (string) $params['role'];
    if (!$lease || !isset(LeaseSignature::ROLES[$role])) redirect('/baux');
    $settings = Setting::all();
    view('leases/sign', [
        'lease'       => $lease,
        'role'        => $role,
        'settings'    => $settings,
        'contractUrl' => url('/contrat/' . (int) $lease['id']),
        'action'      => url('/baux/' . (int) $lease['id'] . '/signer/' . $role),
        'defaultName' => $role === 'bailleur' ? ($settings['landlord_name'] ?? '') : trim($lease['first_name'] . ' ' . $lease['last_name']),
        'savedSignature' => $role === 'bailleur' ? ($settings['landlord_signature'] ?? null) : null,
        'public'      => false,
    ]);
});

App::post('/baux/{id}/signer/{role}', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $lease = Lease::find((int) $params['id']);
    $role = (string) $params['role'];
    if (!$lease || !isset(LeaseSignature::ROLES[$role])) redirect('/baux');
    if ($lease['lease_type'] === 'meuble' && ($issues = Lease::contractIssues($lease, Setting::all())['blocking'])) {
        flash('Bail incomplet — corrigez avant signature : ' . implode(' · ', $issues), 'error');
        redirect('/baux/' . (int) $lease['id']);
    }
    try {
        if (!post('approve')) throw new RuntimeException('Cochez « Lu et approuvé » pour signer.');
        $image = post('use_saved') && $role === 'bailleur' ? (string) Setting::get('landlord_signature') : (string) post('signature');
        $done = LeaseSignature::sign($lease, $role, (string) post('signer_name'), $image);
        flash($done ? 'Bail signé par les deux parties : le PDF signé est archivé.' : LeaseSignature::ROLES[$role] . ' a signé le bail.');
    } catch (Throwable $e) {
        flash('Signature non enregistrée : ' . $e->getMessage(), 'error');
        redirect('/baux/' . (int) $lease['id'] . '/signer/' . $role);
    }
    redirect('/baux/' . (int) $lease['id'] . '#signature');
});

// Lien de signature à distance pour le locataire (créé et, si demandé, envoyé par email)
App::post('/baux/{id}/lien-signature', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    $settings = Setting::all();
    if ($lease['lease_type'] === 'meuble' && ($issues = Lease::contractIssues($lease, $settings)['blocking'])) {
        flash('Bail incomplet — corrigez avant de l\'envoyer à la signature : ' . implode(' · ', $issues), 'error');
        redirect('/baux/' . (int) $lease['id']);
    }
    $link = LeaseSignature::absoluteUrl('/signature/' . LeaseSignature::newToken((int) $lease['id']));
    if (post('send')) {
        $to = trim((string) post('to'));
        $name = trim((string) ($settings['mail_from_name'] ?? '')) ?: ($settings['landlord_name'] ?? '');
        try {
            Mailer::send($to, 'Votre bail à signer', "Bonjour " . $lease['first_name'] . ",\n\n"
                . "Votre contrat de location est prêt. Vous pouvez le lire et le signer en ligne à cette adresse :\n\n$link\n\n"
                . "Ce lien est personnel et valable " . LeaseSignature::TOKEN_DAYS . " jours.\n\nCordialement,\n$name", [], null,
                post('copy') ? QuittanceMail::copyAddress($settings) : null);
            flash("Lien de signature envoyé à $to.");
        } catch (Throwable $e) {
            flash("Lien créé, mais l'email n'est pas parti : " . $e->getMessage() . ' — copiez le lien ci-dessous pour l\'envoyer autrement.', 'error');
        }
    } else {
        flash('Lien de signature créé : copiez-le pour l\'envoyer au locataire (SMS, messagerie…).');
    }
    $_SESSION['sign_link_' . (int) $lease['id']] = $link;
    redirect('/baux/' . (int) $lease['id'] . '#signature');
});

App::post('/baux/{id}/signatures/reset', function ($params) {
    Auth::requireLogin();
    csrf_check();
    LeaseSignature::reset((int) $params['id']);
    flash('Signatures annulées : le bail peut être signé à nouveau (le PDF signé précédent reste archivé).');
    redirect('/baux/' . (int) $params['id'] . '#signature');
});

App::get('/baux/{id}/bail-signe', function ($params) use ($sendFile) {
    Auth::requireLogin();
    $pdf = LeaseSignature::signedPdf((int) $params['id']);
    if (!$pdf) redirect('/baux/' . (int) $params['id']);
    $sendFile($pdf['pdf'], 'application/pdf', 'bail-signe-' . (int) $params['id'] . '.pdf', true);
});

/* --- Pages publiques du locataire (accès par lien personnel, sans compte) --- */

$publicLease = function (string $token): array {
    $lease = LeaseSignature::leaseForToken($token);
    if (!$lease) {
        http_response_code(404);
        exit('Ce lien de signature n\'est plus valable. Demandez un nouveau lien à votre bailleur.');
    }
    return $lease;
};

App::get('/signature/{token}', function ($params) use ($publicLease) {
    $lease = $publicLease((string) $params['token']);
    $settings = Setting::all();
    $base = '/signature/' . $params['token'];
    view('leases/sign', [
        'lease'       => $lease,
        'role'        => 'locataire',
        'settings'    => $settings,
        'contractUrl' => url($base . '/contrat'),
        'action'      => url($base),
        'pdfUrl'      => url($base . '/pdf'),
        'defaultName' => trim($lease['first_name'] . ' ' . $lease['last_name']),
        'savedSignature' => null,
        'public'      => true,
        'signatures'  => LeaseSignature::valid($lease, $settings),
    ], null);
});

App::get('/signature/{token}/contrat', function ($params) use ($publicLease) {
    $lease = $publicLease((string) $params['token']);
    $html = render_template(LeaseSignature::template($lease), ['lease' => $lease, 'settings' => Setting::all()]);
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<link rel="stylesheet" href="' . url('/assets/print.css') . '"></head><body class="print-body"><div class="sheet">' . $html . '</div></body></html>';
});

App::get('/signature/{token}/pdf', function ($params) use ($publicLease, $sendFile) {
    $lease = $publicLease((string) $params['token']);
    $settings = Setting::all();
    $signed = count(LeaseSignature::valid($lease, $settings)) === count(LeaseSignature::ROLES) ? LeaseSignature::signedPdf((int) $lease['id']) : null;
    $data = $signed ? $signed['pdf'] : Pdf::renderDocument(LeaseSignature::template($lease), ['lease' => $lease, 'settings' => $settings]);
    $sendFile($data, 'application/pdf', ($signed ? 'bail-signe' : 'bail') . '.pdf', true);
});

App::post('/signature/{token}', function ($params) use ($publicLease) {
    csrf_check();
    $lease = $publicLease((string) $params['token']);
    try {
        if (!post('approve')) throw new RuntimeException('Cochez « Lu et approuvé » pour signer.');
        $done = LeaseSignature::sign($lease, 'locataire', (string) post('signer_name'), (string) post('signature'));
        flash($done ? 'Merci, le bail est signé par les deux parties. Vous pouvez télécharger le contrat signé.'
                    : 'Merci, votre signature est enregistrée. Le bail sera complet après la signature du bailleur.');
    } catch (Throwable $e) {
        flash('Signature non enregistrée : ' . $e->getMessage(), 'error');
    }
    redirect('/signature/' . $params['token']);
});

/* =========================================================================
 * CHARGES ET IMPÔTS RÉELS — BILAN ANNUEL
 * ========================================================================= */

App::post('/biens/{id}/couts', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $id = (int) $params['id'];
    if (!Property::find($id)) redirect('/biens');
    $data = PropertyCost::fromRequest($id);
    if ($data['amount'] == 0.0) {
        flash('Indiquez un montant.', 'error');
    } else {
        PropertyCost::create($data);
        flash(PropertyCost::label($data) . ' enregistré(e) pour ' . $data['year'] . '.');
    }
    redirect('/biens/' . $id . '?annee=' . $data['year'] . '#charges');
});

App::post('/biens/{id}/couts/{cost}/delete', function ($params) {
    Auth::requireLogin();
    csrf_check();
    $c = PropertyCost::find((int) $params['cost']);
    if ($c && (int) $c['property_id'] === (int) $params['id']) {
        PropertyCost::delete((int) $c['id']);
        flash('Ligne supprimée.');
    }
    redirect('/biens/' . (int) $params['id'] . '?annee=' . (int) ($c['year'] ?? date('Y')) . '#charges');
});

/** Date d'arrêté du bilan : ?au=AAAA-MM-JJ, sinon ?year=AAAA (aujourd'hui pour l'année en cours, 31/12 sinon). */
$bilanDate = function (): array {
    $au = (string) ($_GET['au'] ?? '');
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $au) && strtotime($au)) return [(int) substr($au, 0, 4), $au];
    $year = (int) ($_GET['year'] ?? date('Y'));
    return [$year, Bilan::defaultAsOf($year)];
};

App::get('/bilan', function () use ($bilanDate) {
    Auth::requireLogin();
    [$year, $asOf] = $bilanDate();
    view('bilan/index', ['year' => $year, 'asOf' => $asOf] + Bilan::all($year, $asOf));
});

App::get('/bilan/{id}', function ($params) use ($bilanDate) {
    Auth::requireLogin();
    $property = Property::find((int) $params['id']);
    if (!$property) redirect('/bilan');
    [$year, $asOf] = $bilanDate();
    view('bilan/show', [
        'property' => $property,
        'year'     => $year,
        'asOf'     => $asOf,
        'b'        => Bilan::compute($property, $year, $asOf),
        'costs'    => PropertyCost::forProperty((int) $property['id'], $year),
    ]);
});

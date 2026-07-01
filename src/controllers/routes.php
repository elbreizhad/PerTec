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
    ], $filename);
});

/* =========================================================================
 * CONTRAT DE BAIL (imprimable)
 * ========================================================================= */

App::get('/contrat/{id}', function ($params) {
    Auth::requireLogin();
    $lease = Lease::find((int) $params['id']);
    if (!$lease) redirect('/baux');
    // Bail meublé (LMNP) : modèle dédié ; sinon bail de location vide.
    $template = $lease['lease_type'] === 'meuble'
        ? 'documents/contrat_meuble'
        : 'documents/contrat';
    view($template, [
        'lease'    => $lease,
        'settings' => Setting::all(),
    ], 'layout_print');
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
    $template = $meuble ? 'documents/contrat_meuble' : 'documents/contrat';
    $filename = ($meuble ? 'bail-meuble-' : 'bail-') . $id . '.pdf';
    Pdf::streamDocument($template, [
        'lease'    => $lease,
        'settings' => Setting::all(),
    ], $filename);
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
    ]);
    flash('Paramètres enregistrés.');
    redirect('/parametres');
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

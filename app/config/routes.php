<?php

/**
 * Routedefinities voor de applicatie.
 *
 * Formaat: 'METHOD /pad' => ['Controller', 'actie']
 */

return [
    // Authenticatie
    'GET /'                         => ['AuthController', 'loginForm'],
    'GET /login'                    => ['AuthController', 'loginForm'],
    'POST /login'                   => ['AuthController', 'login'],
    'GET /logout'                   => ['AuthController', 'logout'],
    'GET /wachtwoord-wijzigen'      => ['AuthController', 'wachtwoordWijzigenForm'],
    'POST /wachtwoord-wijzigen'     => ['AuthController', 'wachtwoordWijzigen'],

    // Dashboard
    'GET /dashboard'                => ['DashboardController', 'index'],

    // Klantenbeheer
    'GET /klanten'                  => ['KlantController', 'index'],
    'GET /klanten/detail'           => ['KlantController', 'detail'],
    'GET /klanten/aanmaken'         => ['KlantController', 'aanmakenForm'],
    'POST /klanten/aanmaken'        => ['KlantController', 'aanmaken'],
    'GET /klanten/wijzigen'         => ['KlantController', 'wijzigenForm'],
    'POST /klanten/wijzigen'        => ['KlantController', 'wijzigen'],
    'POST /klanten/verwijderen'     => ['KlantController', 'verwijderen'],
];

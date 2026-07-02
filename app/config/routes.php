<?php

/**
 * Routedefinities voor de applicatie.
 *
 * Formaat: 'METHOD /pad' => ['Controller', 'actie']
 */

return [
    // Homepagina (publiek)
    'GET /'                         => ['HomeController', 'index'],

    // Authenticatie
    'GET /login'                    => ['AuthController', 'loginForm'],
    'POST /login'                   => ['AuthController', 'login'],
    'GET /logout'                   => ['AuthController', 'logout'],
    'GET /wachtwoord-wijzigen'      => ['AuthController', 'wachtwoordWijzigenForm'],
    'POST /wachtwoord-wijzigen'     => ['AuthController', 'wachtwoordWijzigen'],

    // Publieke klantregistratie
    'GET /registreren'              => ['RegistratieController', 'registrerenForm'],
    'POST /registreren'             => ['RegistratieController', 'registreren'],

    // Dashboard
    'GET /dashboard'                => ['DashboardController', 'index'],

    // Klantenbeheer (alleen ingelogde medewerkers/eigenaar)
    'GET /klanten'                  => ['KlantController', 'index'],
    'GET /klanten/detail'           => ['KlantController', 'detail'],
    'GET /klanten/aanmaken'         => ['KlantController', 'aanmakenForm'],
    'POST /klanten/aanmaken'        => ['KlantController', 'aanmaken'],
    'GET /klanten/wijzigen'         => ['KlantController', 'wijzigenForm'],
    'POST /klanten/wijzigen'        => ['KlantController', 'wijzigen'],
    'POST /klanten/verwijderen'     => ['KlantController', 'verwijderen'],
];

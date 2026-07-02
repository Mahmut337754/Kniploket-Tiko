<?php

/**
 * PHPUnit bootstrap — laadt autoloader en initialiseert de database.
 *
 * Wordt uitgevoerd vóór alle tests (zie phpunit.xml: bootstrap="tests/bootstrap.php").
 */

declare(strict_types=1);

// ── Composer autoloader ──────────────────────────────────────────────────────
require_once dirname(__DIR__) . '/vendor/autoload.php';

// ── Databaseconfiguratie ─────────────────────────────────────────────────────
// Zorg dat de Database singleton beschikbaar is met de juiste config.
use App\Core\Database;

$config = require dirname(__DIR__) . '/app/config/database.php';
Database::initialiseer($config);

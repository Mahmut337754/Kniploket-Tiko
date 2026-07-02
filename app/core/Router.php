<?php

namespace App\Core;

/**
 * Eenvoudige front-controller router.
 *
 * Leest routes uit app/config/routes.php en dispatcht naar de juiste
 * controller-actie op basis van HTTP-methode en URI-pad.
 */
class Router
{
    /** @var array<string, array{0:string,1:string}> Geregistreerde routes */
    private array $routes = [];

    /** @var Logger */
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->routes = require dirname(__DIR__) . '/config/routes.php';
    }

    /**
     * Verwerk het huidige verzoek en roep de bijbehorende controller aan.
     */
    public function dispatch(): void
    {
        $methode = $_SERVER['REQUEST_METHOD'];
        $uri     = $this->normaliseUri($_SERVER['REQUEST_URI'] ?? '/');

        $sleutel = $methode . ' ' . $uri;

        if (!isset($this->routes[$sleutel])) {
            $this->logger->warning("Onbekende route: {$sleutel}");
            http_response_code(404);
            echo '<h1>404 – Pagina niet gevonden</h1>';
            return;
        }

        [$controllerNaam, $actie] = $this->routes[$sleutel];

        $volledigeNaam = 'App\\Controllers\\' . $controllerNaam;

        if (!class_exists($volledigeNaam)) {
            $this->logger->error("Controller niet gevonden: {$volledigeNaam}");
            http_response_code(500);
            echo '<h1>500 – Interne serverfout</h1>';
            return;
        }

        $controller = new $volledigeNaam();

        if (!method_exists($controller, $actie)) {
            $this->logger->error("Actie niet gevonden: {$volledigeNaam}::{$actie}");
            http_response_code(500);
            echo '<h1>500 – Interne serverfout</h1>';
            return;
        }

        $controller->$actie();
    }

    /**
     * Verwijder query-string en normaliseer het pad.
     */
    private function normaliseUri(string $uri): string
    {
        // Verwijder query-string
        $pad = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Verwijder eventuele submap-prefix (bijv. /Examen/public)
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptDir !== '/' && str_starts_with($pad, $scriptDir)) {
            $pad = substr($pad, strlen($scriptDir));
        }

        // Zorg voor leading slash
        $pad = '/' . ltrim($pad, '/');

        // Verwijder trailing slash (behalve root)
        if ($pad !== '/') {
            $pad = rtrim($pad, '/');
        }

        return $pad;
    }
}

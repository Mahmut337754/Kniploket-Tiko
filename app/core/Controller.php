<?php

namespace App\Core;

/**
 * Basis-controller met hulpmethoden voor views en redirects.
 */
abstract class Controller
{
    /** @var Logger Toepassingslogger */
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    /**
     * Laad een view en geef variabelen mee.
     *
     * @param string  $view   Relatief pad binnen app/views (zonder .php)
     * @param mixed[] $data   Associatief array met viewvariabelen
     * @param string  $layout Layout-bestand (default: layouts/main)
     */
    protected function view(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        // Stel variabelen beschikbaar in de view
        extract($data, EXTR_SKIP);

        $viewBestand   = dirname(__DIR__) . '/views/' . $view . '.php';
        $layoutBestand = dirname(__DIR__) . '/views/' . $layout . '.php';

        if (!file_exists($viewBestand)) {
            $this->logger->error("View niet gevonden: {$viewBestand}");
            die("View '{$view}' niet gevonden.");
        }

        // Render de view-inhoud naar een buffer
        ob_start();
        require $viewBestand;
        $inhoud = ob_get_clean();

        // Render de layout met de view-inhoud erin
        require $layoutBestand;
    }

    /**
     * Stuur de gebruiker door naar een ander URL.
     * Houdt rekening met het base-pad als de app in een submap draait.
     */
    protected function redirect(string $url): never
    {
        $base = defined('BASE_URL') ? BASE_URL : '';
        // Voorkom dubbele base-prefix
        if ($base !== '' && !str_starts_with($url, $base)) {
            $url = $base . $url;
        }
        header('Location: ' . $url);
        exit;
    }

    /**
     * Sla een flash-bericht op in de sessie.
     *
     * @param string $type    'success' of 'error'
     * @param string $bericht Berichttekst
     */
    protected function setFlash(string $type, string $bericht): void
    {
        $_SESSION['flash'] = ['type' => $type, 'bericht' => $bericht];
    }

    /**
     * Haal het flash-bericht op en verwijder het.
     *
     * @return array{type:string,bericht:string}|null
     */
    protected function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Controleer of de gebruiker is ingelogd; stuur anders naar login.
     */
    protected function vereisLogin(): void
    {
        if (empty($_SESSION['gebruiker_id'])) {
            $this->redirect('/login');
        }
    }

    /**
     * Genereer een CSRF-token en sla het op in de sessie.
     */
    protected function genereerCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Valideer het ingestuurde CSRF-token.
     */
    protected function valideerCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}

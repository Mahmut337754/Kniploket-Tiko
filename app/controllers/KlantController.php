<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Klant;

/**
 * Beheert CRUD-acties voor klanten.
 */
class KlantController extends Controller
{
    private Klant $klantModel;

    public function __construct()
    {
        parent::__construct();
        $this->klantModel = new Klant();
    }

    // -------------------------------------------------------
    // Overzicht
    // -------------------------------------------------------

    /** Toon de lijst van alle klanten. */
    public function index(): void
    {
        $this->vereisLogin();
        $klanten = $this->klantModel->overzicht();
        $flash   = $this->getFlash();
        $this->view('klanten/index', compact('klanten', 'flash'));
    }

    // -------------------------------------------------------
    // Detail
    // -------------------------------------------------------

    /** Toon de detailpagina van één klant. */
    public function detail(): void
    {
        $this->vereisLogin();

        $id    = (int) ($_GET['id'] ?? 0);
        $klant = $this->klantModel->vindOpId($id);

        if ($klant === null) {
            $this->setFlash('error', 'Klant niet gevonden.');
            $this->redirect('/klanten');
        }

        $flash = $this->getFlash();
        $this->genereerCsrfToken(); // zorg dat token beschikbaar is voor verwijder-modaal
        $this->view('klanten/detail', compact('klant', 'flash'));
    }

    // -------------------------------------------------------
    // Aanmaken
    // -------------------------------------------------------

    /** Toon het formulier voor een nieuwe klant. */
    public function aanmakenForm(): void
    {
        $this->vereisLogin();
        $csrfToken = $this->genereerCsrfToken();
        $flash     = $this->getFlash();
        $oud       = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);
        $this->view('klanten/create', compact('csrfToken', 'flash', 'oud'));
    }

    /** Verwerk het aanmaakformulier. */
    public function aanmaken(): void
    {
        $this->vereisLogin();

        $token = $_POST['csrf_token'] ?? '';
        if (!$this->valideerCsrfToken($token)) {
            $this->setFlash('error', 'Ongeldig verzoek.');
            $this->redirect('/klanten/aanmaken');
        }

        $data = $this->haalFormDataOp();
        $fouten = $this->valideerKlantData($data, true);

        if (!empty($fouten)) {
            $this->setFlash('error', implode('<br>', $fouten));
            $_SESSION['form_data'] = $data;
            $this->redirect('/klanten/aanmaken');
        }

        $resultaat = $this->klantModel->aanmaken($data);

        if ($resultaat['fout'] !== '') {
            $this->setFlash('error', $resultaat['fout']);
            $_SESSION['form_data'] = $data;
            $this->redirect('/klanten/aanmaken');
        }

        $this->setFlash('success', 'Klant succesvol aangemaakt.');
        $this->redirect('/klanten');
    }

    // -------------------------------------------------------
    // Wijzigen
    // -------------------------------------------------------

    /** Toon het ingevulde wijzigformulier. */
    public function wijzigenForm(): void
    {
        $this->vereisLogin();

        $id    = (int) ($_GET['id'] ?? 0);
        $klant = $this->klantModel->vindOpId($id);

        if ($klant === null) {
            $this->setFlash('error', 'Klant niet gevonden.');
            $this->redirect('/klanten');
        }

        $csrfToken = $this->genereerCsrfToken();
        $flash     = $this->getFlash();
        $oud       = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);

        // Gebruik sessiedata bij validatiefout, anders de echte klantdata
        $formData = !empty($oud) ? $oud : $klant;

        $this->view('klanten/edit', compact('csrfToken', 'flash', 'klant', 'formData'));
    }

    /** Verwerk het wijzigformulier. */
    public function wijzigen(): void
    {
        $this->vereisLogin();

        $token = $_POST['csrf_token'] ?? '';
        if (!$this->valideerCsrfToken($token)) {
            $this->setFlash('error', 'Ongeldig verzoek.');
            $this->redirect('/klanten');
        }

        $id   = (int) ($_POST['id'] ?? 0);
        $data = $this->haalFormDataOp();
        $fouten = $this->valideerKlantData($data, false);

        if (!empty($fouten)) {
            $this->setFlash('error', implode('<br>', $fouten));
            $_SESSION['form_data'] = $data;
            $this->redirect("/klanten/wijzigen?id={$id}");
        }

        $fout = $this->klantModel->wijzigen($id, $data);

        if ($fout !== '') {
            $this->setFlash('error', $fout);
            $_SESSION['form_data'] = $data;
            $this->redirect("/klanten/wijzigen?id={$id}");
        }

        $this->setFlash('success', 'Klant succesvol bijgewerkt.');
        $this->redirect('/klanten');
    }

    // -------------------------------------------------------
    // Verwijderen
    // -------------------------------------------------------

    /** Verwerk het verwijderverzoek (POST met bevestiging). */
    public function verwijderen(): void
    {
        $this->vereisLogin();

        $token = $_POST['csrf_token'] ?? '';
        if (!$this->valideerCsrfToken($token)) {
            $this->setFlash('error', 'Ongeldig verzoek.');
            $this->redirect('/klanten');
        }

        $id   = (int) ($_POST['id'] ?? 0);
        $fout = $this->klantModel->verwijderen($id);

        if ($fout !== '') {
            $this->setFlash('error', $fout);
        } else {
            $this->setFlash('success', 'Klant succesvol verwijderd.');
        }

        $this->redirect('/klanten');
    }

    // -------------------------------------------------------
    // Hulpmethoden
    // -------------------------------------------------------

    /**
     * Lees formuliervelden uit $_POST en saniteer ze.
     *
     * @return array<string,string>
     */
    private function haalFormDataOp(): array
    {
        return [
            'naam'           => trim($_POST['naam'] ?? ''),
            'email'          => trim($_POST['email'] ?? ''),
            'wachtwoord'     => $_POST['wachtwoord'] ?? '',
            'adres'          => trim($_POST['adres'] ?? ''),
            'telefoonnummer' => trim($_POST['telefoonnummer'] ?? ''),
            'allergieen'     => trim($_POST['allergieen'] ?? ''),
            'wensen'         => trim($_POST['wensen'] ?? ''),
        ];
    }

    /**
     * Valideer klantformulierdata.
     *
     * @param  array<string,string> $data
     * @param  bool                 $isNieuw True als nieuw, false bij wijzigen
     * @return string[]             Lijst met foutmeldingen
     */
    private function valideerKlantData(array $data, bool $isNieuw): array
    {
        $fouten = [];

        if ($data['naam'] === '') {
            $fouten[] = 'Naam is verplicht.';
        }

        if ($data['email'] === '') {
            $fouten[] = 'E-mailadres is verplicht.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $fouten[] = 'Ongeldig e-mailformaat.';
        }

        if ($isNieuw) {
            if ($data['wachtwoord'] === '') {
                $fouten[] = 'Wachtwoord is verplicht.';
            } elseif (strlen($data['wachtwoord']) < 8) {
                $fouten[] = 'Wachtwoord moet minimaal 8 tekens bevatten.';
            }
        } elseif ($data['wachtwoord'] !== '' && strlen($data['wachtwoord']) < 8) {
            $fouten[] = 'Wachtwoord moet minimaal 8 tekens bevatten.';
        }

        return $fouten;
    }
}

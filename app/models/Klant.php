<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;
use PDO;
use PDOException;

/**
 * Model voor klantenbeheer (CRUD via stored procedures).
 */
class Klant
{
    private PDO    $pdo;
    private Logger $logger;

    public function __construct()
    {
        $this->pdo    = Database::getInstance()->getPdo();
        $this->logger = new Logger();
    }

    /**
     * Geeft alle klanten terug (JOIN met gebruikers) via stored procedure.
     *
     * @return array<int, array<string,mixed>>
     */
    public function overzicht(): array
    {
        try {
            $stmt = $this->pdo->prepare('CALL sp_klanten_overzicht()');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logger->error('Klant::overzicht – ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Geeft één klant op basis van klant-id via stored procedure.
     *
     * @return array<string,mixed>|null
     */
    public function vindOpId(int $klantId): ?array
    {
        try {
            $stmt = $this->pdo->prepare('CALL sp_klant_detail(:id)');
            $stmt->bindValue(':id', $klantId, PDO::PARAM_INT);
            $stmt->execute();
            $rij = $stmt->fetch();
            return $rij ?: null;
        } catch (PDOException $e) {
            $this->logger->error('Klant::vindOpId – ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Voeg een nieuwe klant toe via stored procedure.
     *
     * @param  array<string,string> $data Velden: naam, email, wachtwoord, adres, telefoonnummer, allergieen, wensen
     * @return array{id:int, fout:string}
     */
    public function aanmaken(array $data): array
    {
        try {
            $hash = password_hash($data['wachtwoord'], PASSWORD_BCRYPT);

            $sql = 'CALL sp_klant_toevoegen(:naam, :email, :ww, :adres, :tel, :all, :wens, @nieuw_id, @fout)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':naam',  $data['naam'],                  PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'],                 PDO::PARAM_STR);
            $stmt->bindValue(':ww',    $hash,                          PDO::PARAM_STR);
            $stmt->bindValue(':adres', $data['adres'] ?? '',           PDO::PARAM_STR);
            $stmt->bindValue(':tel',   $data['telefoonnummer'] ?? '',  PDO::PARAM_STR);
            $stmt->bindValue(':all',   $data['allergieen'] ?? '',      PDO::PARAM_STR);
            $stmt->bindValue(':wens',  $data['wensen'] ?? '',          PDO::PARAM_STR);
            $stmt->execute();

            // Haal OUT-parameters op
            $res  = $this->pdo->query('SELECT @nieuw_id AS id, @fout AS fout')->fetch();
            $id   = (int)($res['id']   ?? 0);
            $fout = (string)($res['fout'] ?? '');

            if ($fout === '') {
                $this->logger->info("Klant aangemaakt met id={$id}");
            } else {
                $this->logger->warning("Klant aanmaken mislukt: {$fout}");
            }

            return ['id' => $id, 'fout' => $fout];
        } catch (PDOException $e) {
            $this->logger->error('Klant::aanmaken – ' . $e->getMessage());
            return ['id' => 0, 'fout' => 'Databasefout bij aanmaken klant.'];
        }
    }

    /**
     * Wijzig een bestaande klant via stored procedure.
     *
     * @param  int                  $klantId
     * @param  array<string,string> $data
     * @return string Lege string bij succes, foutmelding bij fout
     */
    public function wijzigen(int $klantId, array $data): string
    {
        try {
            // Hash wachtwoord alleen als ingevuld
            $hash = '';
            if (!empty($data['wachtwoord'])) {
                $hash = password_hash($data['wachtwoord'], PASSWORD_BCRYPT);
            }

            $sql = 'CALL sp_klant_wijzigen(:id, :naam, :email, :ww, :adres, :tel, :all, :wens, @fout)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id',    $klantId,                      PDO::PARAM_INT);
            $stmt->bindValue(':naam',  $data['naam'],                 PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'],                PDO::PARAM_STR);
            $stmt->bindValue(':ww',    $hash,                         PDO::PARAM_STR);
            $stmt->bindValue(':adres', $data['adres'] ?? '',          PDO::PARAM_STR);
            $stmt->bindValue(':tel',   $data['telefoonnummer'] ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':all',   $data['allergieen'] ?? '',     PDO::PARAM_STR);
            $stmt->bindValue(':wens',  $data['wensen'] ?? '',         PDO::PARAM_STR);
            $stmt->execute();

            $res  = $this->pdo->query('SELECT @fout AS fout')->fetch();
            $fout = (string)($res['fout'] ?? '');

            if ($fout === '') {
                $this->logger->info("Klant id={$klantId} gewijzigd.");
            } else {
                $this->logger->warning("Klant wijzigen mislukt: {$fout}");
            }

            return $fout;
        } catch (PDOException $e) {
            $this->logger->error('Klant::wijzigen – ' . $e->getMessage());
            return 'Databasefout bij wijzigen klant.';
        }
    }

    /**
     * Verwijder een klant (en bijbehorende gebruiker via CASCADE) via stored procedure.
     *
     * @return string Lege string bij succes, foutmelding bij fout
     */
    public function verwijderen(int $klantId): string
    {
        try {
            $stmt = $this->pdo->prepare('CALL sp_klant_verwijderen(:id, @fout)');
            $stmt->bindValue(':id', $klantId, PDO::PARAM_INT);
            $stmt->execute();

            $res  = $this->pdo->query('SELECT @fout AS fout')->fetch();
            $fout = (string)($res['fout'] ?? '');

            if ($fout === '') {
                $this->logger->info("Klant id={$klantId} verwijderd.");
            } else {
                $this->logger->warning("Klant verwijderen mislukt: {$fout}");
            }

            return $fout;
        } catch (PDOException $e) {
            $this->logger->error('Klant::verwijderen – ' . $e->getMessage());
            return 'Databasefout bij verwijderen klant.';
        }
    }

    /**
     * Haal dashboardstatistieken op via stored procedure.
     *
     * @return array<string,int>
     */
    public function statistieken(): array
    {
        try {
            $stmt = $this->pdo->prepare('CALL sp_dashboard_statistieken()');
            $stmt->execute();
            $rij = $stmt->fetch();
            return $rij ?: [];
        } catch (PDOException $e) {
            $this->logger->error('Klant::statistieken – ' . $e->getMessage());
            return [];
        }
    }
}

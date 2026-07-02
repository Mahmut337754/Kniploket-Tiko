<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;
use PDO;
use PDOException;

/**
 * Model voor gebruikersbeheer en authenticatie.
 */
class User
{
    private PDO    $pdo;
    private Logger $logger;

    public function __construct()
    {
        $this->pdo    = Database::getInstance()->getPdo();
        $this->logger = new Logger();
    }

    /**
     * Zoek een gebruiker op e-mailadres via stored procedure.
     *
     * @return array<string,mixed>|null
     */
    public function vindOpEmail(string $email): ?array
    {
        try {
            $stmt = $this->pdo->prepare('CALL sp_gebruiker_ophalen_email(:email)');
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $rij = $stmt->fetch();
            return $rij ?: null;
        } catch (PDOException $e) {
            $this->logger->error('User::vindOpEmail – ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Zoek een gebruiker op ID via stored procedure.
     *
     * @return array<string,mixed>|null
     */
    public function vindOpId(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare('CALL sp_gebruiker_ophalen_id(:id)');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rij = $stmt->fetch();
            return $rij ?: null;
        } catch (PDOException $e) {
            $this->logger->error('User::vindOpId – ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Wijzig het wachtwoord van een gebruiker.
     */
    public function wijzigWachtwoord(int $id, string $nieuwWachtwoord): bool
    {
        try {
            $hash = password_hash($nieuwWachtwoord, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare('CALL sp_gebruiker_wachtwoord_wijzigen(:id, :ww)');
            $stmt->bindValue(':id', $id,   PDO::PARAM_INT);
            $stmt->bindValue(':ww', $hash, PDO::PARAM_STR);
            $stmt->execute();
            $this->logger->info("Wachtwoord gewijzigd voor gebruiker id={$id}");
            return true;
        } catch (PDOException $e) {
            $this->logger->error('User::wijzigWachtwoord – ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Controleer of een e-mailadres al bestaat (exclusief eigen gebruiker).
     */
    public function emailBestaat(string $email, int $uitsluitId = 0): bool
    {
        try {
            $stmt = $this->pdo->prepare('CALL sp_gebruiker_email_bestaat(:email, :id)');
            $stmt->bindValue(':email', $email,      PDO::PARAM_STR);
            $stmt->bindValue(':id',    $uitsluitId, PDO::PARAM_INT);
            $stmt->execute();
            $rij = $stmt->fetch();
            return ($rij['aantal'] ?? 0) > 0;
        } catch (PDOException $e) {
            $this->logger->error('User::emailBestaat – ' . $e->getMessage());
            return false;
        }
    }
}

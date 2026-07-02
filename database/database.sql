-- =====================================================
-- Database: kniploket_tiko
-- =====================================================
CREATE DATABASE IF NOT EXISTS `kniploket_tiko`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `kniploket_tiko`;

-- -------------------------------------------------------
-- Tabel: rollen
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rollen` (
    `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `naam` VARCHAR(50)  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_naam` (`naam`)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: gebruikers
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gebruikers` (
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `naam`          VARCHAR(100)  NOT NULL,
    `email`         VARCHAR(255)  NOT NULL,
    `wachtwoord`    VARCHAR(255)  NOT NULL COMMENT 'bcrypt hash',
    `rol_id`        INT UNSIGNED  NOT NULL,
    `is_actief`     TINYINT(1)    NOT NULL DEFAULT 1,
    `aangemaakt_op` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `gewijzigd_op`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_email` (`email`),
    KEY `fk_gebruikers_rol` (`rol_id`),
    CONSTRAINT `fk_gebruikers_rol`
        FOREIGN KEY (`rol_id`)
        REFERENCES `rollen` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: klanten
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `klanten` (
    `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `gebruiker_id`   INT UNSIGNED  NOT NULL,
    `adres`          VARCHAR(255)  DEFAULT NULL,
    `telefoonnummer` VARCHAR(20)   DEFAULT NULL,
    `allergieen`     TEXT          DEFAULT NULL,
    `wensen`         TEXT          DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_gebruiker_id` (`gebruiker_id`),
    CONSTRAINT `fk_klanten_gebruiker`
        FOREIGN KEY (`gebruiker_id`)
        REFERENCES `gebruikers` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: medewerkers
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `medewerkers` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `gebruiker_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_gebruiker_id` (`gebruiker_id`),
    CONSTRAINT `fk_medewerkers_gebruiker`
        FOREIGN KEY (`gebruiker_id`)
        REFERENCES `gebruikers` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: specialisaties
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `specialisaties` (
    `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `naam` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_naam` (`naam`)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: medewerker_specialisatie
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `medewerker_specialisatie` (
    `medewerker_id`    INT UNSIGNED NOT NULL,
    `specialisatie_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`medewerker_id`, `specialisatie_id`),
    CONSTRAINT `fk_ms_medewerker`
        FOREIGN KEY (`medewerker_id`)
        REFERENCES `medewerkers` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_ms_specialisatie`
        FOREIGN KEY (`specialisatie_id`)
        REFERENCES `specialisaties` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: werktijden
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `werktijden` (
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `medewerker_id` INT UNSIGNED  NOT NULL,
    `dag_van_week`  TINYINT UNSIGNED NOT NULL COMMENT '1=maandag ... 7=zondag',
    `starttijd`     TIME          NOT NULL,
    `eindtijd`      TIME          NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_medewerker_dag` (`medewerker_id`, `dag_van_week`),
    CONSTRAINT `fk_werktijden_medewerker`
        FOREIGN KEY (`medewerker_id`)
        REFERENCES `medewerkers` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: leveranciers
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leveranciers` (
    `id`   INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `naam` VARCHAR(150)  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_naam` (`naam`)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: behandelingen
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `behandelingen` (
    `id`            INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `naam`          VARCHAR(150)   NOT NULL,
    `prijs`         DECIMAL(8,2)   NOT NULL,
    `duur_minuten`  INT UNSIGNED   NOT NULL,
    `beschrijving`  TEXT           DEFAULT NULL,
    `aangemaakt_op` DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `gewijzigd_op`  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_naam` (`naam`)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: producten
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `producten` (
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `productnaam`   VARCHAR(150)  NOT NULL,
    `categorie`     VARCHAR(100)  NOT NULL,
    `ean_code`      VARCHAR(13)   NOT NULL,
    `voorraad`      INT UNSIGNED  NOT NULL DEFAULT 0,
    `leverancier_id` INT UNSIGNED NOT NULL,
    `prijs`         DECIMAL(8,2)  NOT NULL DEFAULT 0.00,
    `aangemaakt_op` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `gewijzigd_op`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_productnaam` (`productnaam`),
    UNIQUE KEY `uk_ean_code` (`ean_code`),
    KEY `fk_producten_leverancier` (`leverancier_id`),
    CONSTRAINT `fk_producten_leverancier`
        FOREIGN KEY (`leverancier_id`)
        REFERENCES `leveranciers` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: behandeling_producten
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `behandeling_producten` (
    `behandeling_id`  INT UNSIGNED  NOT NULL,
    `product_id`      INT UNSIGNED  NOT NULL,
    `aantal_benodigd` DECIMAL(8,3)  NOT NULL,
    PRIMARY KEY (`behandeling_id`, `product_id`),
    CONSTRAINT `fk_bp_behandeling`
        FOREIGN KEY (`behandeling_id`)
        REFERENCES `behandelingen` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_bp_product`
        FOREIGN KEY (`product_id`)
        REFERENCES `producten` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: afspraken
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `afspraken` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `klant_id`       INT UNSIGNED NOT NULL,
    `medewerker_id`  INT UNSIGNED NOT NULL,
    `behandeling_id` INT UNSIGNED NOT NULL,
    `datum`          DATE         NOT NULL,
    `starttijd`      TIME         NOT NULL,
    `eindtijd`       TIME         NOT NULL,
    `status`         VARCHAR(20)  NOT NULL DEFAULT 'gepland',
    `aangemaakt_op`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `gewijzigd_op`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_afspraak_klant`       (`klant_id`),
    KEY `fk_afspraak_medewerker`  (`medewerker_id`),
    KEY `fk_afspraak_behandeling` (`behandeling_id`),
    KEY `idx_datum_starttijd`     (`datum`, `starttijd`),
    CONSTRAINT `fk_afspraak_klant`
        FOREIGN KEY (`klant_id`)       REFERENCES `klanten` (`id`)       ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_afspraak_medewerker`
        FOREIGN KEY (`medewerker_id`)  REFERENCES `medewerkers` (`id`)   ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_afspraak_behandeling`
        FOREIGN KEY (`behandeling_id`) REFERENCES `behandelingen` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: bestellingen
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bestellingen` (
    `id`                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `klant_id`             INT UNSIGNED NOT NULL,
    `orderdatum`           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `verwachte_leverdatum` DATE         DEFAULT NULL,
    `status`               VARCHAR(30)  NOT NULL DEFAULT 'in behandeling',
    `aangemaakt_op`        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `gewijzigd_op`         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_bestelling_klant` (`klant_id`),
    CONSTRAINT `fk_bestelling_klant`
        FOREIGN KEY (`klant_id`)
        REFERENCES `klanten` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel: bestelregels
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bestelregels` (
    `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `bestelling_id`  INT UNSIGNED  NOT NULL,
    `product_id`     INT UNSIGNED  NOT NULL,
    `aantal`         INT UNSIGNED  NOT NULL,
    `prijs_per_stuk` DECIMAL(8,2)  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_bestelling_product` (`bestelling_id`, `product_id`),
    CONSTRAINT `fk_br_bestelling`
        FOREIGN KEY (`bestelling_id`)
        REFERENCES `bestellingen` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_br_product`
        FOREIGN KEY (`product_id`)
        REFERENCES `producten` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TESTDATA
-- =====================================================
-- Rollen
INSERT INTO `rollen` (`id`, `naam`) VALUES
    (1, 'eigenaar'),
    (2, 'medewerker'),
    (3, 'klant');

-- Gebruikers
-- Wachtwoorden (plain):
--   lisa@kniploket.nl   -> admin123
--   erik@kniploket.nl   -> medewerker123
--   sophie@example.com  -> klant123
-- Hashes gegenereerd met password_hash('...', PASSWORD_BCRYPT, ['cost' => 12])
INSERT INTO `gebruikers` (`id`, `naam`, `email`, `wachtwoord`, `rol_id`, `is_actief`) VALUES
    (1, 'Lisa Jansen',   'lisa@kniploket.nl',   '$2y$12$KZY2twi1/ugyzNL9cD128uHbYHubd4il1ZGOltXK63jcEh/c0.wry', 1, 1),
    (2, 'Erik de Vries', 'erik@kniploket.nl',   '$2y$12$Z/IBc9NeJm.zBSZ.bU44Y.hLlYlvCqhDjPrEIi1BL.cDrWDqPzATC', 2, 1),
    (3, 'Sophie Bakker', 'sophie@example.com',  '$2y$12$PPygEluaXCStdE8loxJkruJZuDZ7NAkQ6b27A1NQtvgdCO2l2voCu', 3, 1);

-- Klanten
INSERT INTO `klanten` (`id`, `gebruiker_id`, `adres`, `telefoonnummer`, `allergieen`, `wensen`) VALUES
    (1, 3, 'Hoofdstraat 12, 1234 AB Stad', '0612345678', 'Allergisch voor ammoniak', 'Houdt van natuurlijke producten');

-- Medewerkers
INSERT INTO `medewerkers` (`id`, `gebruiker_id`) VALUES
    (1, 1),
    (2, 2);

-- Specialisaties
INSERT INTO `specialisaties` (`id`, `naam`) VALUES
    (1, 'knippen'), (2, 'kleuren'), (3, 'stylen'), (4, 'extensions'), (5, 'haarverzorging');

-- Medewerker specialisaties
INSERT INTO `medewerker_specialisatie` (`medewerker_id`, `specialisatie_id`) VALUES
    (1, 1), (1, 2), (1, 4), (2, 3), (2, 5);

-- Werktijden
INSERT INTO `werktijden` (`medewerker_id`, `dag_van_week`, `starttijd`, `eindtijd`) VALUES
    (1,1,'09:00','17:00'),(1,2,'09:00','17:00'),(1,3,'09:00','17:00'),(1,4,'09:00','17:00'),(1,5,'09:00','17:00'),
    (2,1,'09:00','17:00'),(2,2,'09:00','17:00'),(2,3,'09:00','17:00'),(2,4,'09:00','17:00'),(2,5,'09:00','17:00');

-- Leveranciers
INSERT INTO `leveranciers` (`id`, `naam`) VALUES
    (1,'HairPro Supplies'),(2,'StyleMax BV'),(3,'BeautyHouse'),(4,'ColorWorld');

-- Behandelingen
INSERT INTO `behandelingen` (`id`, `naam`, `prijs`, `duur_minuten`, `beschrijving`) VALUES
    (1,'Knippen dames',35.00,60,'Inclusief wassen en föhnen'),
    (2,'Knippen heren',25.00,30,'Knippen heren'),
    (3,'Kleuren',55.00,90,'Inclusief spoeling en styling'),
    (4,'Stylen',30.00,45,'Föhnen en stylen naar wens'),
    (5,'Extensions',120.00,120,'Inclusief haar en plaatsing');

-- Producten
INSERT INTO `producten` (`id`,`productnaam`,`categorie`,`ean_code`,`voorraad`,`leverancier_id`,`prijs`) VALUES
    (1,'Volume Shampoo','shampoo','8712345678900',15,1,12.50),
    (2,'Hydraterende Conditioner','conditioner','8712345678901',8,1,14.95),
    (3,'Styling Gel Strong','styling','8712345678902',2,2,9.99),
    (4,'Kleurbeschermer Spray','verzorging','8712345678903',20,3,18.50),
    (5,'Permanente Verf 5.0','verf','8712345678904',0,4,22.95);

-- Behandeling producten
INSERT INTO `behandeling_producten` (`behandeling_id`,`product_id`,`aantal_benodigd`) VALUES
    (1,1,0.010),(1,2,0.010),(2,1,0.005),(3,4,0.050),(3,5,0.100),(4,3,0.020),(5,2,0.030);

-- Afspraken
INSERT INTO `afspraken` (`id`,`klant_id`,`medewerker_id`,`behandeling_id`,`datum`,`starttijd`,`eindtijd`,`status`) VALUES
    (1,1,1,1,'2026-07-06','09:00','10:00','gepland'),
    (2,1,1,3,'2026-07-08','11:00','12:30','gepland');

-- Bestellingen
INSERT INTO `bestellingen` (`id`,`klant_id`,`orderdatum`,`verwachte_leverdatum`,`status`) VALUES
    (1,1,'2026-07-01 10:00:00','2026-07-05','gereed');

-- Bestelregels
INSERT INTO `bestelregels` (`id`,`bestelling_id`,`product_id`,`aantal`,`prijs_per_stuk`) VALUES
    (1,1,1,2,12.50),(2,1,3,1,9.99);

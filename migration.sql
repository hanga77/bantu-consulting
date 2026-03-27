-- ════════════════════════════════════════════════════════════════
-- MIGRATION - BANTU CONSULTING
-- Appliquer sur la base existante : bantu_consulting
-- Toutes les opérations sont idempotentes (IF NOT EXISTS)
-- ════════════════════════════════════════════════════════════════

USE `bantu_consulting`;

-- ────────────────────────────────────────────────────────────────
-- 1. TABLE projects — colonnes manquantes
-- ────────────────────────────────────────────────────────────────

ALTER TABLE `projects`
  ADD COLUMN IF NOT EXISTS `client`             VARCHAR(100)   DEFAULT NULL          AFTER `short_description`,
  ADD COLUMN IF NOT EXISTS `budget`             DECIMAL(15,2)  DEFAULT NULL          AFTER `client`,
  ADD COLUMN IF NOT EXISTS `image_width`        INT            DEFAULT 0             AFTER `image`,
  ADD COLUMN IF NOT EXISTS `image_height`       INT            DEFAULT 0             AFTER `image_width`,
  ADD COLUMN IF NOT EXISTS `image_processed_at` TIMESTAMP      NULL DEFAULT NULL     AFTER `image_height`,
  ADD COLUMN IF NOT EXISTS `updated_at`         TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ────────────────────────────────────────────────────────────────
-- 2. TABLE site_settings — colonnes réseaux sociaux + GPS
-- ────────────────────────────────────────────────────────────────

ALTER TABLE `site_settings`
  ADD COLUMN IF NOT EXISTS `latitude`       VARCHAR(50)  DEFAULT '4.0511' AFTER `address`,
  ADD COLUMN IF NOT EXISTS `longitude`      VARCHAR(50)  DEFAULT '9.7679' AFTER `latitude`,
  ADD COLUMN IF NOT EXISTS `facebook_url`   VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `twitter_url`    VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `linkedin_url`   VARCHAR(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `instagram_url`  VARCHAR(255) DEFAULT NULL;

-- Garantir la ligne id=1
INSERT IGNORE INTO `site_settings` (`id`, `site_name`, `contact_email`, `phone`, `address`, `latitude`, `longitude`)
VALUES (1, 'Bantu Consulting', 'contact@bantu-consulting.com', '+237-XXX-XXX-XXX', 'Yaoundé, Cameroun', '4.0511', '9.7679');

-- ────────────────────────────────────────────────────────────────
-- 3. TABLE contacts — colonnes status, phone, subject
-- ────────────────────────────────────────────────────────────────

ALTER TABLE `contacts`
  ADD COLUMN IF NOT EXISTS `phone`   VARCHAR(20)  DEFAULT NULL  AFTER `email`,
  ADD COLUMN IF NOT EXISTS `subject` VARCHAR(100) DEFAULT NULL  AFTER `phone`,
  ADD COLUMN IF NOT EXISTS `status`  VARCHAR(50)  DEFAULT 'new' AFTER `message`;

-- ────────────────────────────────────────────────────────────────
-- 4. TABLE carousel — colonnes image traitée
-- ────────────────────────────────────────────────────────────────

ALTER TABLE `carousel`
  ADD COLUMN IF NOT EXISTS `image_width`        INT       DEFAULT 0         AFTER `image`,
  ADD COLUMN IF NOT EXISTS `image_height`       INT       DEFAULT 0         AFTER `image_width`,
  ADD COLUMN IF NOT EXISTS `image_processed_at` TIMESTAMP NULL DEFAULT NULL AFTER `image_height`;

-- ────────────────────────────────────────────────────────────────
-- 5. TABLE teams — colonnes image traitée
-- ────────────────────────────────────────────────────────────────

ALTER TABLE `teams`
  ADD COLUMN IF NOT EXISTS `image_width`        INT       DEFAULT 0         AFTER `image`,
  ADD COLUMN IF NOT EXISTS `image_height`       INT       DEFAULT 0         AFTER `image_width`,
  ADD COLUMN IF NOT EXISTS `image_processed_at` TIMESTAMP NULL DEFAULT NULL AFTER `image_height`;

-- Colonnes réseaux sociaux (DEFAULT '' pour compatibilité données existantes)
ALTER TABLE `teams`
  ADD COLUMN IF NOT EXISTS `linkedin`  VARCHAR(255) NOT NULL DEFAULT '' AFTER `department_id`,
  ADD COLUMN IF NOT EXISTS `twitter`   VARCHAR(255) NOT NULL DEFAULT '' AFTER `linkedin`,
  ADD COLUMN IF NOT EXISTS `facebook`  VARCHAR(255) NOT NULL DEFAULT '' AFTER `twitter`,
  ADD COLUMN IF NOT EXISTS `instagram` VARCHAR(255) NOT NULL DEFAULT '' AFTER `facebook`,
  ADD COLUMN IF NOT EXISTS `website`   VARCHAR(255) NOT NULL DEFAULT '' AFTER `instagram`;

-- ────────────────────────────────────────────────────────────────
-- 6. TABLE newsletter_subscribers — créer si absente
-- ────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(255) NOT NULL,
  `name`          VARCHAR(100) DEFAULT NULL,
  `status`        VARCHAR(50)  DEFAULT 'active',
  `subscribed_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ────────────────────────────────────────────────────────────────
-- 7. TABLE about — garantir la ligne id=1 avec données correctes
-- ────────────────────────────────────────────────────────────────

ALTER TABLE `about`
  ADD COLUMN IF NOT EXISTS `mission` TEXT DEFAULT NULL AFTER `description`,
  ADD COLUMN IF NOT EXISTS `vision`  TEXT DEFAULT NULL AFTER `mission`;

INSERT IGNORE INTO `about` (`id`, `motto`, `description`, `mission`, `vision`)
VALUES (
  1,
  'Expertise et passion au service des organisations',
  'Bantu Consulting est un cabinet de conseil camerounais, spécialisé dans l\'accompagnement stratégique et opérationnel des institutions publiques, parapubliques et privées en Afrique.',
  'Accompagner les organisations dans leur transformation et leur croissance stratégique grâce à des solutions innovantes et durables.',
  'Devenir le cabinet de conseil de référence en Afrique, reconnu pour notre expertise et notre engagement envers l\'excellence.'
);

-- ────────────────────────────────────────────────────────────────
-- 8. TABLE footer_settings — garantir la ligne id=1
-- ────────────────────────────────────────────────────────────────

INSERT IGNORE INTO `footer_settings` (`id`, `address`, `phone`, `email`, `copyright`)
VALUES (
  1,
  'Yaoundé, Cameroun',
  '+237-XXX-XXX-XXX',
  'contact@bantu-consulting.com',
  '© 2026 Bantu Consulting. Tous droits réservés.'
);

-- ────────────────────────────────────────────────────────────────
-- 9. Nettoyage des données de test dans services
--    (uniquement si les champs contiennent des valeurs de test)
-- ────────────────────────────────────────────────────────────────

UPDATE `services` SET
  `benefit1_title` = NULL, `benefit1_desc` = NULL,
  `benefit2_title` = NULL, `benefit2_desc` = NULL,
  `benefit3_title` = NULL, `benefit3_desc` = NULL,
  `benefit4_title` = NULL, `benefit4_desc` = NULL,
  `process1_title` = NULL, `process1_desc` = NULL,
  `process2_title` = NULL, `process2_desc` = NULL,
  `process3_title` = NULL, `process3_desc` = NULL,
  `process4_title` = NULL, `process4_desc` = NULL,
  `fact1` = NULL, `fact2` = NULL, `fact3` = NULL, `fact4` = NULL
WHERE `benefit1_title` IN ('ds', 'test', 'xxx', 'fdf', 'dfs');

-- Corriger le titre du service avec espace en tête
UPDATE `services` SET `title` = TRIM(`title`) WHERE `title` != TRIM(`title`);

-- ────────────────────────────────────────────────────────────────
-- 10. TABLE departments — garantir les 4 pôles/départements
-- ────────────────────────────────────────────────────────────────

INSERT IGNORE INTO `departments` (`id`, `name`, `description`, `department_type`) VALUES
(1, 'Pôle LBC/FT/FP',    'Lutte contre le Blanchiment des Capitaux et le Financement du Terrorisme', 'pole'),
(2, 'Pôle DCA/DIH/DIDH', 'Droit des Conflits Armés / Droit International Humanitaire / Droit International des Droits de l\'Homme', 'pole'),
(3, 'Département RH',    'Ressources Humaines', 'department'),
(4, 'Département GCTD',  'Gestion des Collectivités Territoriales Décentralisées', 'department');

-- ────────────────────────────────────────────────────────────────
-- 11. INDEX utiles manquants
-- ────────────────────────────────────────────────────────────────

-- Index status contacts (si absent)
ALTER TABLE `contacts` ADD INDEX IF NOT EXISTS `idx_status` (`status`);

-- Index updated_at projets
ALTER TABLE `projects` ADD INDEX IF NOT EXISTS `idx_updated_at` (`updated_at`);

-- Index status newsletter
ALTER TABLE `newsletter_subscribers` ADD INDEX IF NOT EXISTS `idx_status` (`status`);

-- ════════════════════════════════════════════════════════════════
-- FIN DE LA MIGRATION
-- ════════════════════════════════════════════════════════════════

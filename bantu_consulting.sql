-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 10:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bantu_consulting`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `motto` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `motto`, `description`, `updated_at`) VALUES
(1, 'Expertise et passion au service des organisations', 'Bantu Consulting est un cabinet de conseil de droit camerounais, spécialisé dans l’accompagnement stratégique et opérationnel des institutions publiques, parapubliques et privées en Afrique.', '2026-01-21 14:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `carousel`
--

CREATE TABLE `carousel` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `order_pos` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `department_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `department_type`, `created_at`) VALUES
(1, 'Pôle LBC/FT/FP', 'Lutte contre le Blanchiment des Capitaux et le Financement du Terrorisme', 'pole', '2026-01-20 10:38:34'),
(2, 'Pôle DCA/DIH/DIDH', 'Droit des Conflits Armés / Droit International Humanitaire / Droit International des Droits de l\'Homme', 'pole', '2026-01-20 10:38:34'),
(3, 'Département RH', 'Ressources Humaines', 'department', '2026-01-20 10:38:34'),
(4, 'Département GCTD', 'Gestion des Collectivités Territoriales Décentralisées', 'department', '2026-01-20 10:38:34');

-- --------------------------------------------------------

--
-- Table structure for table `experts`
--

CREATE TABLE `experts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_settings`
--

CREATE TABLE `footer_settings` (
  `id` int(11) NOT NULL,
  `address` longtext DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  `services_title` varchar(255) DEFAULT NULL,
  `services_description` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_settings`
--

INSERT INTO `footer_settings` (`id`, `address`, `phone`, `email`, `facebook`, `twitter`, `linkedin`, `instagram`, `copyright`, `services_title`, `services_description`, `created_at`, `updated_at`) VALUES
(1, 'Kinshasa, République Démocratique du Congo', '+243 818 818 818', 'contact@bantu-consulting.com', 'https://facebook.com/bantu-consulting', 'https://twitter.com/bantu-consulting', 'https://linkedin.com/company/bantu-consulting', 'https://instagram.com/bantu-consulting', '© 2024 Bantu Consulting. Tous droits réservés.', NULL, NULL, '2026-01-21 23:01:16', '2026-01-21 23:01:16');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `bio` longtext DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `short_description`, `image`, `status`, `start_date`, `end_date`, `created_at`) VALUES
(1, 'Transformation Digitale - Banque XYZ', 'Accompagnement complet de la transformation digitale d\'une grande banque avec mise en place de nouveaux outils numériques, formation des collaborateurs et optimisation des processus.', 'Transformation digitale d\'une institution bancaire majeure', 'projet1.jpg', 'Terminé', '2023-01-15', '2023-09-30', '2026-01-20 10:38:34'),
(2, 'Restructuration Organisationnelle - Groupe Commerce', 'Réorganisation complète d\'un groupe commercial avec redéfinition des processus, optimisation des structures et accompagnement du changement.', 'Restructuration d\'un groupe commercial', 'projet2.jpg', 'Terminé', '2023-03-01', '2023-10-31', '2026-01-20 10:38:34'),
(3, 'Optimisation Logistique - Distribution Région Est', 'Analyse et optimisation de la chaîne logistique d\'une grande entreprise de distribution couvrant la région est du pays.', 'Optimisation logistique pour une entreprise de distribution', 'projet3.jpg', 'En cours', '2024-01-10', NULL, '2026-01-20 10:38:34'),
(4, 'Implémentation ERP - Groupe Textile', 'Sélection, paramétrage et implémentation d\'une solution ERP pour un groupe textile avec formation et support utilisateur.', 'Implémentation d\'une solution ERP', 'projet4.jpg', 'Terminé', '2022-06-01', '2023-05-31', '2026-01-20 10:38:34'),
(5, 'Plan d\'Action 5 Ans - Holding Familial', 'Élaboration d\'un plan stratégique 5 ans pour un holding familial avec analyse de marché, redéfinition du portefeuille et plan d\'investissement.', 'Plan stratégique 5 ans pour holding', 'projet5.jpg', 'Terminé', '2023-02-01', '2023-06-30', '2026-01-20 10:38:34');

-- --------------------------------------------------------

--
-- Table structure for table `project_images`
--

CREATE TABLE `project_images` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `order_pos` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_images`
--

INSERT INTO `project_images` (`id`, `project_id`, `image`, `title`, `description`, `order_pos`, `created_at`) VALUES
(1, 1, '1768993054_Capture_d___cran_2026-01-21_104907.png', 'test', '', 0, '2026-01-21 10:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `member_name` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `benefit1_title` varchar(255) DEFAULT NULL,
  `benefit1_desc` text DEFAULT NULL,
  `benefit2_title` varchar(255) DEFAULT NULL,
  `benefit2_desc` text DEFAULT NULL,
  `benefit3_title` varchar(255) DEFAULT NULL,
  `benefit3_desc` text DEFAULT NULL,
  `benefit4_title` varchar(255) DEFAULT NULL,
  `benefit4_desc` text DEFAULT NULL,
  `process1_title` varchar(255) DEFAULT NULL,
  `process1_desc` text DEFAULT NULL,
  `process2_title` varchar(255) DEFAULT NULL,
  `process2_desc` text DEFAULT NULL,
  `process3_title` varchar(255) DEFAULT NULL,
  `process3_desc` text DEFAULT NULL,
  `process4_title` varchar(255) DEFAULT NULL,
  `process4_desc` text DEFAULT NULL,
  `fact1` text DEFAULT NULL,
  `fact2` text DEFAULT NULL,
  `fact3` text DEFAULT NULL,
  `fact4` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `contact_email`, `contact_phone`, `website`, `benefit1_title`, `benefit1_desc`, `benefit2_title`, `benefit2_desc`, `benefit3_title`, `benefit3_desc`, `benefit4_title`, `benefit4_desc`, `process1_title`, `process1_desc`, `process2_title`, `process2_desc`, `process3_title`, `process3_desc`, `process4_title`, `process4_desc`, `fact1`, `fact2`, `fact3`, `fact4`, `icon`, `created_at`, `updated_at`) VALUES
(9, ' Gestion des ressources humaines', 'Audits RH, réorganisation, \r\nPolitique de recrutement, \r\nPaie et administration du personnel, \r\nFormation et développement des compétences ;', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-21 14:24:06', '2026-01-22 21:40:49'),
(10, 'Gestion des collectivités territoriales décentralisées', 'Gouvernance locale, \r\nPlanification stratégique, \r\nIngénierie institutionnelle, et appui à la décentralisation ;', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-21 14:25:01', '2026-01-22 21:40:49'),
(11, 'Lutte contre le blanchiment des capitaux et le financement du terrorisme (LBC/FT)', 'Formation des acteurs publics et privés,\r\nRenforcement des capacités institutionnelles, conformité réglementaire,\r\nAppui à la mise en œuvre des dispositifs nationaux et régionaux.', 'depot@example.com', '+237564545', '', 'ds', 'fdf', 'dfs', 'fds', 'fdsf', 'fds', 'fd', 'fd', 'k,l', '', 'njk', '', 'n;n', '', 'k,l', '', '', '', '', '', NULL, '2026-01-21 14:25:31', '2026-01-22 22:03:08');

-- --------------------------------------------------------

--
-- Table structure for table `service_files`
--

CREATE TABLE `service_files` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_files`
--

INSERT INTO `service_files` (`id`, `service_id`, `file_path`, `file_name`, `file_type`, `sort_order`, `created_at`) VALUES
(1, 11, '1769119388_69729e9c490f9_cv anglais.pdf', 'cv anglais.pdf', 'pdf', 0, '2026-01-22 22:03:08'),
(2, 11, '1769119388_69729e9c4b3bc_cv french.pdf', 'cv french.pdf', 'pdf', 0, '2026-01-22 22:03:08');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Bantu Consulting', '2026-01-23 10:44:35', '2026-01-23 10:44:35'),
(2, 'site_description', '', '2026-01-23 10:44:35', '2026-01-23 10:44:35'),
(3, 'contact_email', 'hangajean3@gmail.com', '2026-01-23 10:44:35', '2026-01-23 10:44:35'),
(4, 'phone', '697675618', '2026-01-23 10:44:35', '2026-01-23 10:44:35'),
(5, 'address', 'Nkoabang,Yaounde 7085', '2026-01-23 10:44:35', '2026-01-23 10:44:35'),
(6, 'facebook_url', 'https://www.linkedin.com/in/jean-francois-hanga/', '2026-01-23 10:44:35', '2026-01-23 11:45:07'),
(7, 'twitter_url', 'https://www.linkedin.com/in/jean-francois-hanga/', '2026-01-23 10:44:35', '2026-01-23 11:45:07'),
(8, 'linkedin_url', 'https://www.linkedin.com/in/jean-francois-hanga/', '2026-01-23 10:44:35', '2026-01-23 11:45:07'),
(9, 'instagram_url', 'https://www.linkedin.com/in/jean-francois-hanga/', '2026-01-23 10:44:35', '2026-01-23 11:45:07'),
(13, 'contact_email2', 'hangajean3@gmail.com', '2026-01-23 11:45:06', '2026-01-23 11:45:06'),
(20, 'meta_title', '', '2026-01-23 11:45:07', '2026-01-23 11:45:07'),
(21, 'meta_description', '', '2026-01-23 11:45:07', '2026-01-23 11:45:07'),
(22, 'site_keywords', '', '2026-01-23 11:45:07', '2026-01-23 11:45:07'),
(23, 'presentation_video', 'uploads/videos/video_1769357146.mp4', '2026-01-23 11:45:07', '2026-01-25 16:05:46'),
(24, 'footer_text', ' © 2024 Bantu Consulting. Tous droits réservés.', '2026-01-23 11:45:07', '2026-01-23 11:45:07'),
(40, 'site_logo', 'logo_1769169538.jpeg', '2026-01-23 11:58:58', '2026-01-23 11:58:58'),
(41, 'site_favicon', 'favicon_1769169538.jpeg', '2026-01-23 11:58:58', '2026-01-23 11:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) DEFAULT NULL,
  `site_logo` varchar(255) DEFAULT NULL,
  `site_favicon` varchar(255) DEFAULT NULL,
  `site_description` text DEFAULT NULL,
  `site_keywords` varchar(255) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_email2` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `presentation_video` varchar(500) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_logo`, `site_favicon`, `site_description`, `site_keywords`, `contact_email`, `contact_email2`, `phone`, `address`, `presentation_video`, `meta_title`, `meta_description`, `footer_text`, `logo`, `favicon`, `created_at`, `updated_at`) VALUES
(1, 'Bantu Consulting', '1768906746_logo_WhatsApp_Image_2025-08-11____15.08.49_822ded2c.jpg', '1768906494_favicon_WhatsApp_Image_2025-08-11____15.08.49_822ded2c.jpg', '', '', 'hangajean3@gmail.com', 'hangajean3@gmail.com', '697675618', 'Nkoabang,Yaounde 7085', 'uploads/1769004881_video_WhatsApp_Vid__o_2025-03-08____09.40.09_0745f27a.mp4', '', '', ' © 2024 Bantu Consulting. Tous droits réservés.', NULL, NULL, '2026-01-20 10:38:34', '2026-01-21 14:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `experience` text NOT NULL,
  `importance` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `leader_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `linkedin` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `image_width` int(11) DEFAULT 0,
  `image_height` int(11) DEFAULT 0,
  `image_processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `position`, `role`, `experience`, `importance`, `image`, `department_id`, `leader_id`, `created_at`, `linkedin`, `twitter`, `facebook`, `instagram`, `website`) VALUES
(13, 'jean François Hanga', 'Java developer and IT support specialist ', 'dsd', '0', 'Responsable', '1768995474_Capture_d___cran_2026-01-21_102443.png', 4, NULL, '2026-01-21 11:37:54', '', '', '', '', ''),
(14, 'LOUANGA NDTOUNGOU Annie Michelle', 'Assistante informatique', '', '', 'Manager', '1769438292_WhatsApp_Image_2026-01-26_at_3.29.54_PM.jpeg', NULL, NULL, '2026-01-26 14:38:12', 'https://www.linkedin.com/in/jean-francois-hanga/', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$3udDAKPLB12rhFEZh8cb5.KojUHruKoAOj.bMaQEDkwtn6v1xyy0W', 'admin@bantu-consulting.com', '2026-01-20 10:38:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experts`
--
ALTER TABLE `experts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_settings`
--
ALTER TABLE `footer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_images`
--
ALTER TABLE `project_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_files`
--
ALTER TABLE `service_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carousel`
--
ALTER TABLE `carousel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `experts`
--
ALTER TABLE `experts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_images`
--
ALTER TABLE `project_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `service_files`
--
ALTER TABLE `service_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_images`
--
ALTER TABLE `project_images`
  ADD CONSTRAINT `project_images_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_files`
--
ALTER TABLE `service_files`
  ADD CONSTRAINT `service_files_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

-- ========================================
-- Modifications pour images traitées
-- ========================================

ALTER TABLE `teams` ADD COLUMN `image_width` INT DEFAULT 0 AFTER `image`;
ALTER TABLE `teams` ADD COLUMN `image_height` INT DEFAULT 0 AFTER `image_width`;
ALTER TABLE `teams` ADD COLUMN `image_processed_at` TIMESTAMP NULL AFTER `image_height`;

ALTER TABLE `carousel` ADD COLUMN `image_width` INT DEFAULT 0 AFTER `image`;
ALTER TABLE `carousel` ADD COLUMN `image_height` INT DEFAULT 0 AFTER `image_width`;
ALTER TABLE `carousel` ADD COLUMN `image_processed_at` TIMESTAMP NULL AFTER `image_height`;

ALTER TABLE `projects` ADD COLUMN `image_width` INT DEFAULT 0 AFTER `image`;
ALTER TABLE `projects` ADD COLUMN `image_height` INT DEFAULT 0 AFTER `image_width`;
ALTER TABLE `projects` ADD COLUMN `image_processed_at` TIMESTAMP NULL AFTER `image_height`;

-- ========================================
-- Table Newsletter
-- ========================================

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(255) UNIQUE NOT NULL,
  `name` varchar(100),
  `status` varchar(50) DEFAULT 'active',
  `subscribed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- Ajouter les colonnes GPS à site_settings
-- ========================================

ALTER TABLE `site_settings` ADD COLUMN `latitude` VARCHAR(50) DEFAULT '4.0511' AFTER `address`;
ALTER TABLE `site_settings` ADD COLUMN `longitude` VARCHAR(50) DEFAULT '9.7679' AFTER `latitude`;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ========================================
-- Correction table contacts
-- ========================================

ALTER TABLE `contacts` ADD COLUMN `phone` VARCHAR(20) AFTER `email`;
ALTER TABLE `contacts` ADD COLUMN `subject` VARCHAR(100) AFTER `phone`;
ALTER TABLE `contacts` ADD COLUMN `status` VARCHAR(50) DEFAULT 'new' AFTER `message`;

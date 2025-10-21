-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 21 oct. 2025 à 09:40
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `aquacole`
--

-- --------------------------------------------------------

--
-- Structure de la table `coordinates`
--

CREATE TABLE `coordinates` (
  `id` int(11) NOT NULL,
  `nom_zone` varchar(255) DEFAULT NULL,
  `zone` varchar(100) DEFAULT NULL,
  `wilaya_name_ascii` varchar(255) DEFAULT NULL,
  `commune_name_ascii` varchar(255) DEFAULT NULL,
  `code_wilaya` varchar(5) DEFAULT NULL,
  `code_commune` varchar(5) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT 1,
  `distance_voi_acces` int(11) DEFAULT NULL,
  `code_concession` varchar(255) DEFAULT NULL,
  `superficie` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `statut` enum('active','inactive','en_attente') DEFAULT 'inactive',
  `coordonnee_a` varchar(100) NOT NULL,
  `coordonnee_b` varchar(100) NOT NULL,
  `coordonnee_c` varchar(100) NOT NULL,
  `coordonnee_d` varchar(100) NOT NULL,
  `format_coordonnees` varchar(20) DEFAULT 'decimal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `coordinates`
--

INSERT INTO `coordinates` (`id`, `nom_zone`, `zone`, `wilaya_name_ascii`, `commune_name_ascii`, `code_wilaya`, `code_commune`, `visible`, `distance_voi_acces`, `code_concession`, `superficie`, `description`, `statut`, `coordonnee_a`, `coordonnee_b`, `coordonnee_c`, `coordonnee_d`, `format_coordonnees`, `created_at`) VALUES
(1, 'Ferme pilote – Bab El Oued', 'coastal', 'Alger', 'Bab El Oued', '16', '1605', 1, 250, '161605101', 2500.00, 'Ferme pilote côtière pour la daurade. الموقع: باب الوادي – مشروع استزراع بحري نموذجي.', 'inactive', '36.792500 3.053000', '36.792500 3.053550', '36.792050 3.053550', '36.792050 3.053000', 'decimal', '2025-09-20 09:15:00'),
(2, 'Concession Aïn El Turk', 'coastal', 'Oran', 'Ain El Turk', '31', '3103', 1, 600, '313103202', 2500.00, 'Élevage de loup de mer près d’Aïn El Turk. تربية القاروص بالقرب من عين الترك.', 'inactive', '35.748300 -0.763000', '35.748300 -0.762450', '35.747850 -0.762450', '35.747850 -0.763000', 'decimal', '2025-09-20 10:20:00'),
(3, 'Parc marin – Tichy', 'marine', 'Bejaia', 'Tichy', '06', '0607', 1, 900, '060607303', 2500.00, 'Parc marin pour conchyliculture. حظيرة بحرية لتربية المحاريات – تيشي.', 'inactive', '36.668900 5.116000', '36.668900 5.116550', '36.668450 5.116550', '36.668450 5.116000', 'decimal', '2025-09-20 11:05:00'),
(4, 'Plateforme littorale Annaba', 'coastal', 'Annaba', 'Seraidi', '23', '2307', 1, 1200, '232307404', 2500.00, 'Plateforme d’algoculture – démonstration. منصة لزراعة الطحالب – عرض توضيحي.', 'inactive', '36.977500 7.740000', '36.977500 7.740550', '36.977050 7.740550', '36.977050 7.740000', 'decimal', '2025-09-21 08:45:00'),
(5, 'Zone côtière – Cherchell', 'coastal', 'Tipaza', 'Cherchell', '42', '4204', 1, 1500, '424204505', 2500.00, 'Zone côtière adaptée à la mytiliculture. منطقة ساحلية مناسبة لتربية بلح البحر.', 'inactive', '36.607800 2.190000', '36.607800 2.190550', '36.607350 2.190550', '36.607350 2.190000', 'decimal', '2025-09-21 09:10:00'),
(6, 'Collo – Parc de grossissement', 'marine', 'Skikda', 'Collo', '21', '2106', 1, 800, '212106606', 2500.00, 'Parc de grossissement multi-espèces. حظيرة تسمين متعددة الأنواع – القل.', 'inactive', '37.001200 6.563000', '37.001200 6.563550', '37.000750 6.563550', '37.000750 6.563000', 'decimal', '2025-09-21 10:00:00'),
(7, 'Honaine – Concession côtière', 'coastal', 'Tlemcen', 'Honaine', '13', '1309', 1, 2200, '131309707', 2500.00, 'Concession dédiée à la dorade et au bar. امتياز مخصص للدنيس والقاروص – هنين.', 'inactive', '35.171500 -1.670000', '35.171500 -1.669450', '35.171050 -1.669450', '35.171050 -1.670000', 'decimal', '2025-09-22 08:20:00'),
(8, 'Site démonstration – El Aouana', 'marine', 'Jijel', 'El Aouana', '18', '1803', 1, 1400, '181803808', 2500.00, 'Site de démonstration pour cages flottantes. موقع عرض لأقفاص عائمة – العوانة.', 'inactive', '36.811500 5.667000', '36.811500 5.667550', '36.811050 5.667550', '36.811050 5.667000', 'decimal', '2025-09-22 09:30:00'),
(9, 'Périmètre Sidi Lakhdar', 'marine', 'Mostaganem', 'Sidi Lakhdar', '27', '2707', 1, 1800, '272707909', 2500.00, 'Périmètre pour élevage intensif. محيط للاستزراع المكثف – سيدي الأخضر.', 'inactive', '36.038400 0.426000', '36.038400 0.426550', '36.037950 0.426550', '36.037950 0.426000', 'decimal', '2025-09-22 10:00:00'),
(10, 'Cap Djinet – Module algues', 'coastal', 'Boumerdes', 'Djinet', '35', '3507', 1, 500, '353507010', 2500.00, 'Module de culture d’algues comestibles. وحدة لزراعة الطحالب الصالحة للأكل – جنات.', 'active', '36.911000 3.885000', '36.911000 3.885550', '36.910550 3.885550', '36.910550 3.885000', 'decimal', '2025-09-23 08:55:00'),
(11, 'Berriane – Bassins eau douce3', 'freshwater', 'Ghardaia', 'Berriane', '47', '4702', 1, 7002, '474702111', 25002.00, 'Bassins d’aquaculture en eau douce (tilapia). أحواض استزراع مائي بالمياه العذبة – بريان.', 'active', '32.833000 3.766000', '32.833000 3.766550', '32.832550 3.766550', '32.832550 3.766000', 'decimal', '2025-09-23 09:40:00'),
(12, 'El Kala – Réserve littorale', 'marine', 'El Taref', 'El Kala', '36', '3602', 1, 300, '363602212', 2500.00, 'Concession littorale proche du parc. امتياز ساحلي قرب الحديقة – القالة.', 'inactive', '36.898800 8.442000', '36.898800 8.442550', '36.898350 8.442550', '36.898350 8.442000', 'decimal', '2025-09-23 11:10:00'),
(13, 'Ténès – Baie nord3', 'marine', 'Chlef', 'Tenes', '02', '0203', 1, 950, '020203313', 2500.00, 'Baie abritée pour mytiliculture. خليج محمي لتربية بلح البحر – تنس.', 'active', '36.802323, 3.688252', '36.802773, 3.688252', '36.802773, 3.688702', '36.802323, 3.688702', 'decimal', '2025-09-24 08:20:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `coordinates`
--
ALTER TABLE `coordinates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `coordinates`
--
ALTER TABLE `coordinates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

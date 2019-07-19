-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 19 juil. 2019 à 14:53
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `imali`
--

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`id`, `name`, `address`, `start_date`, `end_date`, `description`) VALUES
(1, 'Cristal Party', '12 rue de Suffren', '2019-07-22 17:00:00', '2019-07-22 23:00:00', 'Petit apéro au soleil et avec de la bonne chouffe'),
(2, 'Pyjama Party', '118 rue de Paris', '2019-07-23 19:00:00', '2019-07-24 05:00:00', 'Soirée à la maison '),
(3, '88', 'hjkghkg', '2014-01-08 00:00:00', '2014-01-08 00:00:00', '8'),
(4, '8', '98', '2014-01-09 00:00:00', '2014-01-09 00:00:00', '9'),
(5, '8', '98', '2014-01-09 00:00:00', '2014-01-09 00:00:00', '9');

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

DROP TABLE IF EXISTS `picture`;
CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `team` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_8D93D649C05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `name`, `prename`, `birth_date`, `nickname`, `team`) VALUES
(2, 'chewi', 'chewi', 'andres@urzua.fr', 'andres@urzua.fr', 1, NULL, '$2y$13$CBt0j6NPjeBHuCLSXsLniuN8DqyMC0rNM4mxZJBtViaLdSXDB5e0u', '2019-07-18 12:21:16', NULL, NULL, 'a:0:{}', 'Urzua', 'Andres', '1997-01-17', 'chewi', 'imali'),
(4, 'suri', 'suri', 'hadrien@suri.fr', 'hadrien@suri.fr', 1, NULL, '$2y$13$CBt0j6NPjeBHuCLSXsLniuN8DqyMC0rNM4mxZJBtViaLdSXDB5e0u', '2019-07-19 10:02:15', NULL, NULL, 'a:0:{}', 'Vinay', 'Hadrien', '1996-08-24', 'suri', 'imali'),
(5, 'vagabond', 'vagabond', 'arthur@vagabond.fr', 'arthur@vagabond.fr', 1, NULL, '$2y$13$CBt0j6NPjeBHuCLSXsLniuN8DqyMC0rNM4mxZJBtViaLdSXDB5e0u', '2019-07-18 12:21:16', NULL, NULL, 'a:0:{}', 'Falcon', 'Arthur', '1996-08-14', 'vagabond', 'imali'),
(7, 'jazzy', 'jazzy', 'basile@jazzy.fr', 'basile@jazzy.fr', 1, NULL, '$2y$13$CBt0j6NPjeBHuCLSXsLniuN8DqyMC0rNM4mxZJBtViaLdSXDB5e0u', '2019-07-18 12:21:16', NULL, NULL, 'a:0:{}', 'Falcon', 'Basile', '1996-08-14', 'jazzy', 'imali');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

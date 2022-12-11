-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 11 déc. 2022 à 14:19
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `symphony_eval`
--

-- --------------------------------------------------------

--
-- Structure de la table `contenu_panier`
--

DROP TABLE IF EXISTS `contenu_panier`;
CREATE TABLE IF NOT EXISTS `contenu_panier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produit_id` int NOT NULL,
  `panier_id` int NOT NULL,
  `quantite` int NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_80507DC0F347EFB` (`produit_id`),
  KEY `IDX_80507DC0F77D927C` (`panier_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contenu_panier`
--

INSERT INTO `contenu_panier` (`id`, `produit_id`, `panier_id`, `quantite`, `date`) VALUES
(1, 3, 1, 2, '2022-12-09 10:12:51'),
(2, 1, 1, 1, '2022-12-09 10:18:51'),
(3, 4, 3, 1, '2022-12-09 10:34:47'),
(6, 1, 4, 1, '2022-12-10 00:23:08'),
(7, 3, 4, 4, '2022-12-10 00:24:08'),
(8, 4, 4, 2, '2022-12-10 01:00:52'),
(9, 1, 5, 1, '2022-12-11 02:26:08'),
(10, 3, 5, 2, '2022-12-11 02:26:16');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20221208140250', '2022-12-08 14:23:24', 2839),
('DoctrineMigrations\\Version20221209091103', '2022-12-09 09:11:11', 3301);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` datetime NOT NULL,
  `etat` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24CC0DF2A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `user_id`, `date`, `etat`) VALUES
(1, 1, '2022-12-09 10:11:16', 1),
(3, 1, '2022-12-09 10:34:32', 1),
(4, 1, '2022-12-10 00:16:08', 0),
(5, 2, '2022-12-11 02:26:08', 0);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `prix` double NOT NULL,
  `stock` int NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `prix`, `stock`, `photo`) VALUES
(1, 'Produit 1', 'AAAAAAAAA', 10, 56, NULL),
(3, 'Produit 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec vehicula est. In eget semper ipsum. Etiam vitae erat et purus faucibus faucibus. Nam urna turpis, mollis a neque ut, fermentum luctus libero. Fusce eros ex, blandit vel felis in, varius auctor dui. Suspendisse consequat odio et leo maximus congue.', 65, 48, '6393bcff52f1c.png'),
(4, 'Produit 3', '(._.)', 100, 42, '6393bc5c1f91d.jpg'),
(5, 'Produit 4', 'ifjodsiojv', 45, 21, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_inscription` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `date_inscription`) VALUES
(1, 'superadmin@test.com', '[\"ROLE_USER\", \"ROLE_ADMIN\", \"ROLE_SUPERADMIN\"]', '$2y$13$eENbgeR26sNlKX50GYD8LuYk9FMietIxTC.yv1t2QkRfjHqzQupm2', 'Entine', 'Clem', '2022-12-09 09:18:00'),
(2, 'admin@test.com', '[\"ROLE_USER\", \"ROLE_ADMIN\"]', '$2y$13$RSr1Rp1SL0J8n50YMAt4LuZMIIXAoguhU5vJMeU4lB/lcV0a86fVK', 'Nyme', 'Ano', '2022-12-09 15:48:20'),
(3, 'user@test.com', '[\"ROLE_USER\"]', '$2y$13$fOuPO0orqeBdCuX1cAbeueiWxaiEp9WTavgK8d/TfD4OhSkOSDhiK', 'TEST', 'test', '2022-12-11 01:28:15'),
(4, 'ccc@ddd.ee', '[\"ROLE_USER\"]', '$2y$13$eM9pdIXtLVtMT9x9v2TXvOfxu./9WrZD/1Wn1x3U9ZWwaHAk1PlDu', 'aaa', 'bbb', '2022-12-11 02:01:16'),
(5, 'hhh@iii.jj', '[\"ROLE_USER\"]', '$2y$13$It0JQpzI.D/P1A0z5hxUU.p4o881GOKWnUhwm67k3IEjVNVUGIfHW', 'fff', 'ggg', '2022-12-11 02:02:25');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contenu_panier`
--
ALTER TABLE `contenu_panier`
  ADD CONSTRAINT `FK_80507DC0F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `FK_80507DC0F77D927C` FOREIGN KEY (`panier_id`) REFERENCES `panier` (`id`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `FK_24CC0DF2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

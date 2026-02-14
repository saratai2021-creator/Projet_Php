-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : sam. 14 juin 2025 à 10:24
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `theatre_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE `evenements` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_event` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `description`, `date_event`) VALUES
(7, 'Memories', 'BienVenue dans nos evenement', '2026-02-12');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscrits`
--

CREATE TABLE `inscrits` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `evenement_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','visiteur') DEFAULT 'visiteur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`created_at`, `id`, `nom`, `prenom`, `email`, `password`, `role`) VALUES
('2025-06-13 14:52:00', 7, 'tai', 'sara', 'admin@gmail.com', '$2y$10$0thsJNltojyve.qe2r7kvee2UjQYtIrERH6NmrP5DI4EeOdet/v7e', 'admin'),
('2025-06-13 14:52:00', 21, 'tai', 'sara', 'admin@admin.com', '$2y$10$vy7VqfQfmol8KzCXs83H0ejCwf9mrtCQCtaHYoKy0dTt6RdoQzoh6', 'admin'),
('2025-06-13 14:52:00', 22, 'tai', 'sara', 'admin123@gmai.com', '$2y$10$9Dz9FkWhs2JL1vSsL2y96uYwGJ9.4V2ecQ0xN1y6xsrIIi/ksV7DK', 'visiteur'),
('2025-06-13 14:52:00', 23, 'tai', 'sara', 'admin1253@gmai.com', '$2y$10$00E5UBFP2PWnF5PzKqkwt.2vXJs/dKbpVi2KKBkGjtks0156PurWi', 'visiteur'),
('2025-06-13 14:52:00', 24, 'tai', 'sara', 'admin147@admin.com', '$2y$10$EmPb3ptPHD/ZiBMXNo1oYe5m7AZJTDkBHbpncyZ8eu6V6aWzw90t2', 'visiteur'),
('2025-06-13 14:52:00', 25, 'tai', 'sara', 'admin1972@admin.com', '$2y$10$4JJ0SGUSR.iTZjkJEIJhg.NgkmRAt2BqqmT/Cj9J5G6Z8Q6jbu3EW', 'visiteur'),
('2025-06-13 14:52:00', 26, 'tai', 'sara', 'admin1977@admin.com', '$2y$10$5Nq4mKuSqdYy7wHOF23yUuQc6BqQm6yIO37utEpzCmBFBx6GR7CZ2', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Index pour la table `inscrits`
--
ALTER TABLE `inscrits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evenement_id` (`evenement_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `inscrits`
--
ALTER TABLE `inscrits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `evenements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `inscrits`
--
ALTER TABLE `inscrits`
  ADD CONSTRAINT `inscrits_ibfk_1` FOREIGN KEY (`evenement_id`) REFERENCES `evenements` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 14 fév. 2025 à 22:39
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
-- Base de données : `librairy`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `CategoryId` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `ClientId` int(11) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Genre` enum('Masculin','Féminin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`ClientId`, `Nom`, `Address`, `DateNaissance`, `Genre`) VALUES
(3, 'ahmed', 'nktt', '2025-02-19', ''),
(4, 'admin', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `CommandeId` int(11) NOT NULL,
  `CommandeDate` datetime DEFAULT current_timestamp(),
  `Prix` decimal(10,2) NOT NULL,
  `Status` text DEFAULT 'En cours',
  `ClientId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `login`
--

CREATE TABLE `login` (
  `UserId` int(11) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Category` enum('Client','Admin') DEFAULT 'Client',
  `ClientId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `login`
--

INSERT INTO `login` (`UserId`, `Password`, `Category`, `ClientId`) VALUES
(3, '$2y$10$DUJ2TR1UA1gjjGM4NrmIZ.uOE1zse.smTjcbzroo3qr2sM.hwbWbS', 'Client', 3),
(4, '$2y$10$bsOh3bxkXgiVWmRcfntvruWRaL7VMONZ.Hnzw4tQhgj8erO3NAd.m', 'Admin', 4);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `Id` int(11) NOT NULL,
  `ClientId` int(11) DEFAULT NULL,
  `ProduitId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `ProduitId` int(11) NOT NULL,
  `NomLivre` varchar(200) NOT NULL,
  `Auteur` varchar(100) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Prix` decimal(10,2) NOT NULL,
  `CategoryId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits_commandes`
--

CREATE TABLE `produits_commandes` (
  `IdClé` int(11) NOT NULL,
  `ProduitId` int(11) DEFAULT NULL,
  `CommandeId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryId`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ClientId`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`CommandeId`),
  ADD KEY `ClientId` (`ClientId`);

--
-- Index pour la table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`UserId`),
  ADD KEY `ClientId` (`ClientId`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `ClientId` (`ClientId`),
  ADD KEY `ProduitId` (`ProduitId`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`ProduitId`),
  ADD KEY `CategoryId` (`CategoryId`);

--
-- Index pour la table `produits_commandes`
--
ALTER TABLE `produits_commandes`
  ADD PRIMARY KEY (`IdClé`),
  ADD KEY `ProduitId` (`ProduitId`),
  ADD KEY `CommandeId` (`CommandeId`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `ClientId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `CommandeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `login`
--
ALTER TABLE `login`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `ProduitId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `produits_commandes`
--
ALTER TABLE `produits_commandes`
  MODIFY `IdClé` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`ClientId`) REFERENCES `client` (`ClientId`);

--
-- Contraintes pour la table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`ClientId`) REFERENCES `client` (`ClientId`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`ClientId`) REFERENCES `client` (`ClientId`),
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`ProduitId`) REFERENCES `produits` (`ProduitId`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`CategoryId`) REFERENCES `category` (`CategoryId`);

--
-- Contraintes pour la table `produits_commandes`
--
ALTER TABLE `produits_commandes`
  ADD CONSTRAINT `produits_commandes_ibfk_1` FOREIGN KEY (`ProduitId`) REFERENCES `produits` (`ProduitId`),
  ADD CONSTRAINT `produits_commandes_ibfk_2` FOREIGN KEY (`CommandeId`) REFERENCES `commande` (`CommandeId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

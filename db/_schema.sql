-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 03 Janvier 2016 à 19:53
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `phpat`
--

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id (clef principale) utilisateur',
  `username` varchar(128) NOT NULL COMMENT 'Username',
  `password_hash` varchar(128) NOT NULL COMMENT 'Hash du mot de passe',
  `firstname` varchar(128) NOT NULL COMMENT 'Prénom',
  `lastname` varchar(256) NOT NULL COMMENT 'Nom',
  `email` varchar(128) NOT NULL COMMENT 'Adresse courriel utilisateur',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table des utilisateurs du site' AUTO_INCREMENT=191 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password_hash`, `firstname`, `lastname`, `email`) VALUES
(188, 'gp', '$2y$10$qgHMNfbLXi0C90dMVratou9TMQ/zf9Le/mIkVQF9856lYlZvVHKg2', 'Gilles', 'Pénissard', 'gilles.penissard@isi-mtl.com'),
(189, 'pinocchio', '$2y$10$HMedEASO9EmJAYK0MayBzud05y.WacMwvtilOCivigCMFlVkGSHS2', 'Pinocchio', 'La marionetta', 'pinocchio.marionetta@isi-mtl.com'),
(190, 'jiminy', '$2y$10$bmywgS/L.oHNJnZnI4Xc9e82yzLkQjQoFXthoBatwVBRhRQik7scW', 'Jiminy', 'Cricket', 'jiminy.cricket@isi-mtl.com');

-- --------------------------------------------------------

--
-- Structure de la table `user_cnx`
--

CREATE TABLE IF NOT EXISTS `user_cnx` (
  `cnx_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de connexion',
  `user_id` int(11) NOT NULL COMMENT 'Id de l''utilisateur',
  `session_id` varchar(126) NOT NULL COMMENT 'l''id de session de l''utilisateur',
  `date_in` datetime NOT NULL COMMENT 'Date de la dernière connexion',
  `date_last_access` datetime NOT NULL COMMENT 'Date dernier accès au site',
  `date_out` datetime DEFAULT NULL COMMENT 'Date de la dernière déconnexion',
  PRIMARY KEY (`cnx_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `user_cnx`
--

INSERT INTO `user_cnx` (`cnx_id`, `user_id`, `session_id`, `date_in`, `date_last_access`, `date_out`) VALUES
(1, 188, '_gp_', '2016-01-03 19:51:15', '2016-01-03 19:51:15', '2016-01-03 19:51:15'),
(2, 190, '_jiminy_', '2016-01-03 19:51:15', '2016-01-03 19:51:15', NULL),
(3, 188, '_gp_', '2016-01-03 19:51:15', '2016-01-03 19:51:15', NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `user_cnx`
--
ALTER TABLE `user_cnx`
  ADD CONSTRAINT `user_cnx_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

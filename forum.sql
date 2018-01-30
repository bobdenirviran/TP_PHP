-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 30 Janvier 2018 à 15:08
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `forum`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `Cid` int(11) NOT NULL,
  `Cname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`Cid`, `Cname`) VALUES
(1, 'LANGAGE'),
(2, 'BASE DE DONNEES'),
(3, 'SERVEUR'),
(4, 'FRAMEWORK'),
(5, 'SYNTAXE'),
(6, 'DIVERS');

-- --------------------------------------------------------

--
-- Structure de la table `grants`
--

CREATE TABLE `grants` (
  `id` int(11) NOT NULL,
  `grant_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `grants`
--

INSERT INTO `grants` (`id`, `grant_name`) VALUES
(1, 'CAN_CREATE_PRODUCT'),
(2, 'CAN_UPDATE_PRODUCT'),
(3, 'CAN_DELETE_PRODUCT');

-- --------------------------------------------------------

--
-- Structure de la table `link_role_grant`
--

CREATE TABLE `link_role_grant` (
  `id_role` int(11) NOT NULL,
  `id_grant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `link_role_grant`
--

INSERT INTO `link_role_grant` (`id_role`, `id_grant`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 2),
(2, 3),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `Pid` int(11) NOT NULL,
  `Pdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Ptitle` varchar(255) NOT NULL,
  `Ptext` longtext NOT NULL,
  `Pid_subject` int(11) NOT NULL,
  `Pid_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`Pid`, `Pdate`, `Ptitle`, `Ptext`, `Pid_subject`, `Pid_user`) VALUES
(1, '2018-01-26 19:41:19', 'titre 1', 'ceci est le texte titre 1 SUJET 1', 1, 1),
(3, '2018-01-26 19:40:44', 'titre 3', 'ceci est le texte du titre 3 Sujet 2', 2, 2),
(4, '2018-01-26 19:40:12', 'titre 4', 'ceci est un texte du titre 4 Sujet 3', 3, 1),
(11, '2018-01-26 13:37:48', 'titre 1 post 1 sujet 4', 'texte 1 post 1 sujet 4', 8, 2),
(12, '2018-01-26 13:38:40', 'titre 1 post 1 sujet 3', 'texte 1 post 1 sujet 3', 9, 2),
(33, '2018-01-28 22:09:10', 'titre message 1 html', 'Le langage HTML est fait de balise', 15, 1),
(34, '2018-01-28 22:36:50', 'titre message 1 Mongo', 'Mongo est une base de donnÃ©e OO', 16, 1),
(35, '2018-01-28 23:12:07', 'Titre 1 message 1 DB OBJET', 'c\'est quoi un DB OBJET', 17, 1),
(36, '2018-01-28 23:41:51', 'Titre 3 PHP syntaxe', 'Message 3 PHP Syntaxe', 1, 1),
(37, '2018-01-29 11:43:44', 'Titre Message 3 PHP SYNTAXE', 'TEXTE MESSAGE 3 PHP SYNTAXE', 1, 1),
(38, '2018-01-29 11:45:25', 'Titre message 4 php syntaxe', 'Texte 4 PHP SYNTAXE 5', 1, 1),
(41, '2018-01-30 12:01:35', 'python titre 1 ', 'python texte 1', 20, 1),
(45, '2018-01-30 13:49:04', 'titre 3', 'texte 3 ', 20, 3),
(46, '2018-01-30 13:11:22', 'Titre 4', 'Texte 4', 20, 3),
(47, '2018-01-30 13:54:32', 'Titre X', 'Texte titre X', 20, 1);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'ADMIN'),
(2, 'MODERATOR'),
(3, 'USER');

-- --------------------------------------------------------

--
-- Structure de la table `subjects`
--

CREATE TABLE `subjects` (
  `Sid` int(11) NOT NULL,
  `Sdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Slabel` varchar(255) NOT NULL,
  `Sclosed` int(1) NOT NULL DEFAULT '0',
  `Sid_categorie` int(11) NOT NULL,
  `Sid_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `subjects`
--

INSERT INTO `subjects` (`Sid`, `Sdate`, `Slabel`, `Sclosed`, `Sid_categorie`, `Sid_user`) VALUES
(1, '2018-01-28 12:44:25', 'PHP SYNTAXE', 0, 1, 1),
(2, '2018-01-28 12:44:30', 'PHP OBJET SUJET 2', 0, 1, 2),
(3, '2018-01-28 12:44:35', 'PHP PROCEDURAL SUJET 3', 0, 1, 2),
(8, '2018-01-28 12:44:39', 'MYSQLI SUJET 4', 0, 2, 3),
(9, '2018-01-28 12:44:43', 'APACHE CORDOVA SUJET 5', 0, 3, 3),
(15, '2018-01-28 22:09:10', 'HTML', 0, 1, 1),
(16, '2018-01-28 22:36:50', 'MONGO', 0, 2, 1),
(17, '2018-01-28 23:12:07', 'DB OBJET', 1, 2, 1),
(20, '2018-01-30 12:01:35', 'Python', 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `id_role`) VALUES
(1, 'Pierre', 'e4953807b90944c5eb46ddcf68470b3ee7e76502', 1),
(2, 'Paul', 'd7c42d00268ebbcec2226ab0d8a8b42c13b68af9', 2),
(3, 'Bernard', '13b52ff128a5912865fc022e619fa10a46666dc6', 3),
(4, 'David', 'd7cfa0f646fa6c540376cbe3fcd6ecbeb60da0e0', 3),
(5, 'Anthonny', '5efcf1851a49a86102d764a12b4a8f8547246e49', 3),
(8, 'Stephane', '709405aa05333b13ccee02b9820f1c12020f3582', 3);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`Cid`);

--
-- Index pour la table `grants`
--
ALTER TABLE `grants`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `link_role_grant`
--
ALTER TABLE `link_role_grant`
  ADD PRIMARY KEY (`id_role`,`id_grant`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_grant` (`id_grant`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`Pid`),
  ADD KEY `Pid_subject` (`Pid_subject`),
  ADD KEY `Pid_user` (`Pid_user`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`Sid`),
  ADD KEY `Sid_user` (`Sid_user`),
  ADD KEY `Sid_categ` (`Sid_categorie`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `Cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `grants`
--
ALTER TABLE `grants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `Pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `link_role_grant`
--
ALTER TABLE `link_role_grant`
  ADD CONSTRAINT `link_role_grant_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `link_role_grant_ibfk_2` FOREIGN KEY (`id_grant`) REFERENCES `grants` (`id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`Pid_subject`) REFERENCES `subjects` (`Sid`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`Pid_user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`Sid_categorie`) REFERENCES `categories` (`Cid`),
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`Sid_user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : ven. 20 juin 2025 à 10:24
-- Version du serveur : 9.3.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pawfect-match`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@pawfect-match.dev', '$2y$10$.4.kOAi82rFDoUBVaZEl3.BFQg9..t98VN/MJvyiW1cvXj2sAyc4i');

-- --------------------------------------------------------

--
-- Structure de la table `banned`
--

CREATE TABLE `banned` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `end_date` date NOT NULL,
  `begin_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dog`
--

CREATE TABLE `dog` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  `size_id` int NOT NULL,
  `temperament_id` int NOT NULL,
  `gender_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog`
--

INSERT INTO `dog` (`id`, `name`, `birthday`, `size_id`, `temperament_id`, `gender_id`) VALUES
(1, 'loki', '2023-01-29', 2, 1, 2),
(4, 'Tsuki', '2023-03-16', 2, 2, 3),
(5, 'Tsuki', '2022-03-15', 2, 2, 3),
(6, 'Max', '2023-03-15', 2, 3, 1),
(7, 'Bella', '2022-08-22', 1, 2, 2),
(8, 'Charlie', '2021-11-07', 3, 1, 1),
(9, 'Luna', '2024-01-30', 2, 4, 2),
(10, 'Cooper', '2020-05-12', 4, 2, 1),
(11, 'Lucy', '2023-09-18', 1, 3, 2),
(12, 'Rocky', '2022-02-28', 3, 1, 1),
(13, 'Daisy', '2024-06-03', 2, 2, 2),
(14, 'Buddy', '2021-07-14', 4, 4, 1),
(15, 'Molly', '2023-12-09', 1, 1, 2),
(16, 'Duke', '2020-10-25', 3, 3, 1),
(17, 'Sophie', '2022-04-16', 2, 2, 2),
(18, 'Bear', '2024-02-11', 4, 1, 1),
(19, 'Chloe', '2023-07-27', 1, 4, 2),
(20, 'Tucker', '2021-12-01', 3, 2, 1),
(21, 'Lola', '2022-09-13', 2, 3, 2),
(22, 'Zeus', '2020-03-08', 4, 1, 1),
(23, 'Ruby', '2024-05-20', 1, 2, 2),
(24, 'Oscar', '2023-10-06', 3, 4, 1),
(25, 'Zoe', '2022-01-17', 2, 1, 2),
(26, 'Rex', '2021-06-24', 4, 3, 1),
(27, 'Mia', '2024-04-12', 1, 2, 2),
(28, 'Jack', '2023-08-29', 3, 1, 1),
(29, 'Penny', '2022-11-05', 2, 4, 2),
(30, 'Diesel', '2020-07-18', 4, 2, 1),
(31, 'Rosie', '2023-01-14', 1, 3, 2),
(32, 'Toby', '2021-04-21', 3, 1, 1),
(33, 'Emma', '2024-03-07', 2, 2, 2),
(34, 'Buster', '2022-12-23', 4, 4, 1),
(35, 'Gracie', '2023-05-10', 1, 1, 2),
(36, 'Finn', '2020-08-31', 3, 3, 1),
(37, 'Lexi', '2022-06-08', 2, 2, 2),
(38, 'Atlas', '2024-01-25', 4, 1, 1),
(39, 'Nala', '2023-09-12', 1, 4, 2),
(40, 'Gus', '2021-03-04', 3, 2, 1),
(41, 'Stella', '2022-10-19', 2, 3, 2),
(42, 'Titan', '2020-12-02', 4, 1, 1),
(43, 'Lily', '2024-07-16', 1, 2, 2),
(44, 'Bruno', '2023-02-28', 3, 4, 1),
(45, 'Maya', '2022-05-15', 2, 1, 2),
(46, 'Ranger', '2021-11-22', 4, 3, 1),
(47, 'Sadie', '2024-04-09', 1, 2, 2),
(48, 'Hank', '2023-08-14', 3, 1, 1),
(49, 'Coco', '2022-01-27', 2, 4, 2),
(50, 'Thor', '2020-09-11', 4, 2, 1),
(51, 'Piper', '2023-06-18', 1, 3, 2),
(52, 'Bandit', '2021-02-03', 3, 1, 1),
(53, 'Roxy', '2024-05-26', 2, 2, 2),
(54, 'Apollo', '2022-12-07', 4, 4, 1),
(55, 'Honey', '2023-03-21', 1, 1, 2),
(56, 'Scout', '2020-06-14', 3, 3, 1),
(57, 'Ellie', '2022-09-28', 2, 2, 2),
(58, 'Maverick', '2024-02-12', 4, 1, 1),
(59, 'Olive', '2023-07-05', 1, 4, 2),
(60, 'Murphy', '2021-10-18', 3, 2, 1),
(61, 'Hazel', '2022-04-30', 2, 3, 2),
(62, 'King', '2020-11-13', 4, 1, 1),
(63, 'Willow', '2024-08-01', 1, 2, 2),
(64, 'Cash', '2023-01-24', 3, 4, 1),
(65, 'Maggie', '2022-07-17', 2, 1, 2),
(66, 'Chief', '2021-12-30', 4, 3, 1),
(67, 'Izzy', '2024-06-23', 1, 2, 2),
(68, 'Jasper', '2023-11-06', 3, 1, 1),
(69, 'Koda', '2022-03-19', 2, 4, 1),
(70, 'Storm', '2020-05-02', 4, 2, 1),
(71, 'Abby', '2023-09-25', 1, 3, 2),
(72, 'Rocco', '2021-01-08', 3, 1, 1),
(73, 'Layla', '2024-04-21', 2, 2, 2),
(74, 'Hunter', '2022-08-04', 4, 4, 1),
(75, 'Ginger', '2023-12-17', 1, 1, 2),
(76, 'Blue', '2020-07-29', 3, 3, 1),
(77, 'Pepper', '2022-02-11', 2, 2, 2),
(78, 'Ace', '2024-03-26', 4, 1, 1),
(79, 'Princess', '2023-06-09', 1, 4, 2),
(80, 'Oreo', '2021-09-22', 3, 2, 1),
(81, 'Angel', '2022-12-05', 2, 3, 2),
(82, 'Gunner', '2020-04-18', 4, 1, 1),
(83, 'Cassie', '2024-07-31', 1, 2, 2),
(84, 'Tank', '2023-10-14', 3, 4, 1),
(85, 'Bonnie', '2022-05-27', 2, 1, 2),
(86, 'Blaze', '2021-08-10', 4, 3, 1),
(87, 'Maddie', '2024-01-23', 1, 2, 2),
(88, 'Diesel', '2023-04-06', 3, 1, 1),
(89, 'Karma', '2022-11-19', 2, 4, 2),
(90, 'Maximus', '2020-03-02', 4, 2, 1),
(91, 'Belle', '2023-08-15', 1, 3, 2),
(92, 'Harley', '2021-05-28', 3, 1, 1),
(93, 'Millie', '2024-09-11', 2, 2, 2),
(94, 'Brutus', '2022-06-24', 4, 4, 1),
(95, 'Sasha', '2023-01-07', 1, 1, 2),
(96, 'Chester', '2020-10-20', 3, 3, 1),
(97, 'Callie', '2022-04-03', 2, 2, 2),
(98, 'Tyson', '2024-05-16', 4, 1, 1),
(99, 'Dixie', '2023-12-29', 1, 4, 2),
(100, 'Bentley', '2021-07-12', 3, 2, 1),
(101, 'Lulu', '2022-09-25', 2, 3, 2),
(102, 'Caesar', '2020-12-08', 4, 1, 1),
(103, 'Phoebe', '2024-02-21', 1, 2, 2),
(104, 'Beau', '2023-05-04', 3, 4, 1),
(105, 'Trixie', '2022-08-17', 2, 1, 2),
(106, 'Goliath', '2021-11-30', 4, 3, 1),
(107, 'Zoey', '2024-06-13', 1, 2, 2),
(108, 'Rusty', '2023-09-26', 3, 1, 1),
(109, 'Fiona', '2022-01-09', 2, 4, 2),
(110, 'Boomer', '2020-06-22', 4, 2, 1),
(111, 'Xena', '2023-11-05', 1, 3, 2),
(112, 'Copper', '2021-04-18', 3, 1, 1),
(113, 'Zelda', '2024-07-01', 2, 2, 2),
(114, 'Spike', '2022-10-14', 4, 4, 1),
(115, 'Mila', '2023-02-27', 1, 1, 2),
(116, 'Ziggy', '2020-09-10', 3, 3, 1),
(117, 'Ivy', '2022-12-23', 2, 2, 2),
(118, 'Samson', '2024-03-08', 4, 1, 1),
(119, 'Penny', '2023-06-21', 1, 4, 2),
(120, 'Bandit', '2021-01-04', 3, 2, 1),
(121, 'Chloe', '2022-07-17', 2, 3, 2),
(122, 'Rex', '2020-05-30', 4, 1, 1),
(123, 'Raven', '2024-08-13', 1, 2, 2),
(124, 'Ace', '2023-03-26', 3, 4, 1),
(125, 'Daisy', '2022-11-09', 2, 1, 2),
(126, 'Thor', '2021-08-22', 4, 3, 1),
(127, 'Lola', '2024-01-05', 1, 2, 2),
(128, 'Duke', '2023-07-18', 3, 1, 1),
(129, 'Nova', '2022-04-01', 2, 4, 2),
(130, 'Atlas', '2020-11-14', 4, 2, 1),
(131, 'Luna', '2023-12-27', 1, 3, 2),
(132, 'Rocco', '2021-06-10', 3, 1, 1),
(133, 'Sophie', '2024-09-23', 2, 2, 2),
(134, 'Titan', '2022-02-06', 4, 4, 1),
(135, 'Ruby', '2023-05-19', 1, 1, 2),
(136, 'Buster', '2020-08-02', 3, 3, 1),
(137, 'Bella', '2022-12-15', 2, 2, 2),
(138, 'Zeus', '2024-04-28', 4, 1, 1),
(139, 'Coco', '2023-09-11', 1, 4, 2),
(140, 'Oscar', '2021-02-24', 3, 2, 1),
(141, 'Mia', '2022-06-07', 2, 3, 2),
(142, 'Bear', '2020-12-20', 4, 1, 1),
(143, 'Rosie', '2024-03-03', 1, 2, 2),
(144, 'Jack', '2023-08-16', 3, 4, 1),
(145, 'Lexi', '2022-10-29', 2, 1, 2),
(146, 'Ranger', '2021-05-12', 4, 3, 1),
(147, 'Gracie', '2024-07-25', 1, 2, 2),
(148, 'Tucker', '2023-01-08', 3, 1, 1),
(149, 'Emma', '2022-09-21', 2, 4, 2),
(150, 'Diesel', '2020-07-04', 4, 2, 1),
(151, 'Nala', '2023-11-17', 1, 3, 2),
(152, 'Finn', '2021-03-30', 3, 1, 1),
(153, 'Stella', '2024-06-13', 2, 2, 2),
(154, 'Apollo', '2022-08-26', 4, 4, 1),
(155, 'Lily', '2023-04-09', 1, 1, 2),
(156, 'Bruno', '2020-10-22', 3, 3, 1),
(157, 'Maya', '2022-01-05', 2, 2, 2),
(158, 'Chief', '2024-05-18', 4, 1, 1),
(159, 'Sadie', '2023-10-01', 1, 4, 2),
(160, 'Hank', '2021-12-14', 3, 2, 1),
(161, 'Piper', '2022-07-27', 2, 3, 2),
(162, 'Storm', '2020-04-10', 4, 1, 1),
(163, 'Honey', '2024-08-23', 1, 2, 2),
(164, 'Scout', '2023-02-06', 3, 4, 1),
(165, 'Ellie', '2022-11-19', 2, 1, 2),
(166, 'Maverick', '2021-07-02', 4, 3, 1),
(167, 'Olive', '2024-01-15', 1, 2, 2),
(168, 'Murphy', '2023-06-28', 3, 1, 1),
(169, 'Hazel', '2022-03-11', 2, 4, 2),
(170, 'King', '2020-09-24', 4, 2, 1),
(171, 'Willow', '2023-12-07', 1, 3, 2),
(172, 'Cash', '2021-04-20', 3, 1, 1),
(173, 'Maggie', '2024-09-03', 2, 2, 2),
(174, 'Hunter', '2022-05-16', 4, 4, 1),
(175, 'Izzy', '2023-08-29', 1, 1, 2),
(176, 'Jasper', '2020-11-11', 3, 3, 1),
(177, 'Koda', '2022-02-24', 2, 2, 2),
(178, 'Blaze', '2024-07-07', 4, 1, 1),
(179, 'Abby', '2023-03-20', 1, 4, 2),
(180, 'Dixie', '2023-12-29', 1, 4, 2),
(181, 'Bentley', '2021-07-12', 3, 2, 1),
(182, 'Lulu', '2022-09-25', 2, 3, 2),
(183, 'Caesar', '2020-12-08', 4, 1, 1),
(184, 'Phoebe', '2024-02-21', 1, 2, 2),
(185, 'Beau', '2023-05-04', 3, 4, 1),
(186, 'Trixie', '2022-08-17', 2, 1, 2),
(187, 'Goliath', '2021-11-30', 4, 3, 1),
(188, 'Zoey', '2024-06-13', 1, 2, 2),
(189, 'Rusty', '2023-09-26', 3, 1, 1),
(190, 'Fiona', '2022-01-09', 2, 4, 2),
(191, 'Boomer', '2020-06-22', 4, 2, 1),
(192, 'Xena', '2023-11-05', 1, 3, 2),
(193, 'Copper', '2021-04-18', 3, 1, 1),
(194, 'Zelda', '2024-07-01', 2, 2, 2),
(195, 'Spike', '2022-10-14', 4, 4, 1),
(196, 'Mila', '2023-02-27', 1, 1, 2),
(197, 'Ziggy', '2020-09-10', 3, 3, 1),
(198, 'Ivy', '2022-12-23', 2, 2, 2),
(199, 'Samson', '2024-03-08', 4, 1, 1),
(200, 'Penny', '2023-06-21', 1, 4, 2),
(201, 'Bandit', '2021-01-04', 3, 2, 1),
(202, 'Chloe', '2022-07-17', 2, 3, 2),
(203, 'Rex', '2020-05-30', 4, 1, 1),
(204, 'Raven', '2024-08-13', 1, 2, 2),
(205, 'Ace', '2023-03-26', 3, 4, 1),
(206, 'Daisy', '2022-11-09', 2, 1, 2),
(207, 'Thor', '2021-08-22', 4, 3, 1),
(208, 'Lola', '2024-01-05', 1, 2, 2),
(209, 'Duke', '2023-07-18', 3, 1, 1),
(210, 'Nova', '2022-04-01', 2, 4, 2),
(211, 'Atlas', '2020-11-14', 4, 2, 1),
(212, 'Luna', '2023-12-27', 1, 3, 2),
(213, 'Rocco', '2021-06-10', 3, 1, 1),
(214, 'Sophie', '2024-09-23', 2, 2, 2),
(215, 'Titan', '2022-02-06', 4, 4, 1),
(216, 'Ruby', '2023-05-19', 1, 1, 2),
(217, 'Buster', '2020-08-02', 3, 3, 1),
(218, 'Bella', '2022-12-15', 2, 2, 2),
(219, 'Zeus', '2024-04-28', 4, 1, 1),
(220, 'Coco', '2023-09-11', 1, 4, 2),
(221, 'Oscar', '2021-02-24', 3, 2, 1),
(222, 'Mia', '2022-06-07', 2, 3, 2),
(223, 'Bear', '2020-12-20', 4, 1, 1),
(224, 'Rosie', '2024-03-03', 1, 2, 2),
(225, 'Jack', '2023-08-16', 3, 4, 1),
(226, 'Lexi', '2022-10-29', 2, 1, 2),
(227, 'Ranger', '2021-05-12', 4, 3, 1),
(228, 'Gracie', '2024-07-25', 1, 2, 2),
(229, 'Tucker', '2023-01-08', 3, 1, 1),
(230, 'Raven', '2024-08-13', 1, 2, 2),
(231, 'Ace', '2023-03-26', 3, 4, 1),
(232, 'Daisy', '2022-11-09', 2, 1, 2),
(233, 'Thor', '2021-08-22', 4, 3, 1),
(234, 'Lola', '2024-01-05', 1, 2, 2),
(235, 'Duke', '2023-07-18', 3, 1, 1),
(236, 'Nova', '2022-04-01', 2, 4, 2),
(237, 'Atlas', '2020-11-14', 4, 2, 1),
(238, 'Luna', '2023-12-27', 1, 3, 2),
(239, 'Rocco', '2021-06-10', 3, 1, 1),
(240, 'Sophie', '2024-09-23', 2, 2, 2),
(241, 'Titan', '2022-02-06', 4, 4, 1),
(242, 'Ruby', '2023-05-19', 1, 1, 2),
(243, 'Buster', '2020-08-02', 3, 3, 1),
(244, 'Bella', '2022-12-15', 2, 2, 2),
(245, 'Zeus', '2024-04-28', 4, 1, 1),
(246, 'Coco', '2023-09-11', 1, 4, 2),
(247, 'Oscar', '2021-02-24', 3, 2, 1),
(248, 'Mia', '2022-06-07', 2, 3, 2),
(249, 'Bear', '2020-12-20', 4, 1, 1),
(250, 'Rosie', '2024-03-03', 1, 2, 2),
(251, 'Jack', '2023-08-16', 3, 4, 1),
(252, 'Lexi', '2022-10-29', 2, 1, 2),
(253, 'Ranger', '2021-05-12', 4, 3, 1),
(254, 'Gracie', '2024-07-25', 1, 2, 2),
(255, 'Tucker', '2023-01-08', 3, 1, 1),
(256, 'Emma', '2022-09-21', 2, 4, 2),
(257, 'Diesel', '2020-07-04', 4, 2, 1),
(258, 'Nala', '2023-11-17', 1, 3, 2),
(259, 'Finn', '2021-03-30', 3, 1, 1),
(260, 'Stella', '2024-06-13', 2, 2, 2),
(261, 'Apollo', '2022-08-26', 4, 4, 1),
(262, 'Lily', '2023-04-09', 1, 1, 2),
(263, 'Bruno', '2020-10-22', 3, 3, 1),
(264, 'Maya', '2022-01-05', 2, 2, 4),
(265, 'Chief', '2024-05-18', 4, 1, 1),
(266, 'Sadie', '2023-10-01', 1, 4, 2),
(267, 'Hank', '2021-12-14', 3, 2, 1),
(268, 'Piper', '2022-07-27', 2, 3, 2),
(269, 'Storm', '2020-04-10', 4, 1, 1),
(270, 'Honey', '2024-08-23', 1, 2, 4),
(271, 'Princess', '2023-06-09', 1, 4, 2),
(272, 'Oreo', '2021-09-22', 3, 2, 1),
(273, 'Angel', '2022-12-05', 2, 3, 2),
(274, 'Gunner', '2020-04-18', 4, 1, 1),
(275, 'Cassie', '2024-07-31', 1, 2, 2),
(276, 'Tank', '2023-10-14', 3, 4, 1),
(277, 'Bonnie', '2022-05-27', 2, 1, 2),
(278, 'Blaze', '2021-08-10', 4, 3, 1),
(279, 'Maddie', '2024-01-23', 1, 2, 2),
(280, 'Diesel', '2023-04-06', 3, 1, 1),
(281, 'Karma', '2022-11-19', 2, 4, 2),
(282, 'Maximus', '2020-03-02', 4, 2, 1),
(283, 'Belle', '2023-08-15', 1, 3, 2),
(284, 'Harley', '2021-05-28', 3, 1, 4),
(285, 'Millie', '2024-09-11', 2, 2, 2),
(286, 'Brutus', '2022-06-24', 4, 4, 1),
(287, 'Sasha', '2023-01-07', 1, 1, 2),
(288, 'Spike', '2022-10-14', 4, 4, 1),
(289, 'Mila', '2023-02-27', 1, 1, 2),
(290, 'Ziggy', '2020-09-10', 3, 3, 1),
(291, 'Ivy', '2022-12-23', 2, 2, 4),
(292, 'Samson', '2024-03-08', 4, 1, 1),
(293, 'Penny', '2023-06-21', 1, 4, 2),
(294, 'Bandit', '2021-01-04', 3, 2, 1),
(295, 'Chloe', '2022-07-17', 2, 3, 2),
(296, 'Rex', '2020-05-30', 4, 1, 1),
(297, 'Raven', '2024-08-13', 1, 2, 2),
(298, 'Ace', '2023-03-26', 3, 4, 1),
(299, 'Daisy', '2022-11-09', 2, 1, 2),
(300, 'Thor', '2021-08-22', 4, 3, 1),
(301, 'Lola', '2024-01-05', 1, 2, 2),
(302, 'Mila', '2023-02-27', 1, 1, 2),
(303, 'Ziggy', '2020-09-10', 3, 3, 4),
(304, 'Ivy', '2022-12-23', 2, 2, 2),
(305, 'Samson', '2024-03-08', 4, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `dog_genders`
--

CREATE TABLE `dog_genders` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_genders`
--

INSERT INTO `dog_genders` (`id`, `name`) VALUES
(1, 'Mâle castré'),
(2, 'Mâle non castré'),
(3, 'Femelle stérilisée'),
(4, 'Femelle non stérilisé');

-- --------------------------------------------------------

--
-- Structure de la table `dog_sizes`
--

CREATE TABLE `dog_sizes` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_sizes`
--

INSERT INTO `dog_sizes` (`id`, `name`) VALUES
(1, 'Petit'),
(2, 'Moyen'),
(3, 'Grand'),
(4, 'Très grand');

-- --------------------------------------------------------

--
-- Structure de la table `dog_temperaments`
--

CREATE TABLE `dog_temperaments` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dog_temperaments`
--

INSERT INTO `dog_temperaments` (`id`, `name`) VALUES
(1, 'Très actif'),
(2, 'Calme mais joueur'),
(3, 'Calme et tranquille'),
(4, 'Nerveux / anxieux');

-- --------------------------------------------------------

--
-- Structure de la table `gender`
--

CREATE TABLE `gender` (
  `id` int NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `gender`
--

INSERT INTO `gender` (`id`, `name`) VALUES
(1, 'femme'),
(2, 'homme'),
(3, 'autres');

-- --------------------------------------------------------

--
-- Structure de la table `hobbies`
--

CREATE TABLE `hobbies` (
  `id` int NOT NULL,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `icon` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `hobbies`
--

INSERT INTO `hobbies` (`id`, `name`, `icon`) VALUES
(1, 'Lecture', 'menu_book'),
(2, 'Cuisine', 'restaurant'),
(3, 'Voyage', 'flight'),
(4, 'Musique', 'music_note'),
(5, 'Peinture', 'brush'),
(6, 'Photographie', 'photo_camera'),
(7, 'Randonnée', 'hiking'),
(8, 'Cinéma', 'movie'),
(9, 'Jeux vidéo', 'sports_esports'),
(10, 'Jardinage', 'yard'),
(11, 'Danse', 'music_video'),
(12, 'Écriture', 'edit'),
(13, 'Fitness', 'fitness_center'),
(14, 'Cyclisme', 'directions_bike'),
(15, 'Natation', 'pool'),
(17, 'Pêche', 'phishing'),
(18, 'Shopping', 'shopping_bag'),
(19, 'Yoga', 'self_improvement'),
(20, 'Chant', 'mic');

-- --------------------------------------------------------

--
-- Structure de la table `inflow`
--

CREATE TABLE `inflow` (
  `id` int NOT NULL,
  `cash` float NOT NULL,
  `date_buy` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matches`
--

CREATE TABLE `matches` (
  `id` int NOT NULL,
  `user_id_0` int NOT NULL,
  `user_id_1` int NOT NULL,
  `date` date NOT NULL,
  `is_skiped` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `hour` time NOT NULL,
  `is_view` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `price`
--

CREATE TABLE `price` (
  `id` int NOT NULL,
  `price` double NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `price`
--

INSERT INTO `price` (`id`, `price`, `quantity`) VALUES
(1, 8.99, 1),
(2, 24.99, 3),
(3, 44.99, 6),
(4, 79.99, 12);

-- --------------------------------------------------------

--
-- Structure de la table `report`
--

CREATE TABLE `report` (
  `id` int NOT NULL,
  `complainant_id` int NOT NULL,
  `accused_id` int NOT NULL,
  `reason_id` int NOT NULL,
  `date` date NOT NULL,
  `is_solved` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `report_reason`
--

CREATE TABLE `report_reason` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `report_reason`
--

INSERT INTO `report_reason` (`id`, `name`) VALUES
(9, 'Comportement inapproprié'),
(10, 'Harcèlement'),
(11, 'Faux profil'),
(12, 'Spam');

-- --------------------------------------------------------

--
-- Structure de la table `subscription`
--

CREATE TABLE `subscription` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lastname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gender_id` int DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `profil_photo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `message_push` tinyint NOT NULL DEFAULT '1',
  `message_email` tinyint NOT NULL DEFAULT '1',
  `match_push` tinyint NOT NULL DEFAULT '1',
  `match_email` tinyint NOT NULL DEFAULT '1',
  `location` point DEFAULT NULL,
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_dog`
--

CREATE TABLE `user_dog` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `dog_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_filter`
--

CREATE TABLE `user_filter` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `min_age` int DEFAULT '25',
  `max_age` int DEFAULT '35',
  `distance` int DEFAULT '80'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_filter_dog_genders`
--

CREATE TABLE `user_filter_dog_genders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `dog_gender_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_filter_dog_sizes`
--

CREATE TABLE `user_filter_dog_sizes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `size_dog_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_filter_dog_temperament`
--

CREATE TABLE `user_filter_dog_temperament` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `dog_temperament_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_filter_gender`
--

CREATE TABLE `user_filter_gender` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `gender_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_filter_hobbies`
--

CREATE TABLE `user_filter_hobbies` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `hobbies_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_gender_preferences`
--

CREATE TABLE `user_gender_preferences` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `gender_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_hobbies`
--

CREATE TABLE `user_hobbies` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `hobbies_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `banned`
--
ALTER TABLE `banned`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `dog`
--
ALTER TABLE `dog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `size_id` (`size_id`),
  ADD KEY `temperament_id` (`temperament_id`);

--
-- Index pour la table `dog_genders`
--
ALTER TABLE `dog_genders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dog_sizes`
--
ALTER TABLE `dog_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dog_temperaments`
--
ALTER TABLE `dog_temperaments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `hobbies`
--
ALTER TABLE `hobbies`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inflow`
--
ALTER TABLE `inflow`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_0` (`user_id_0`),
  ADD KEY `user_id_1` (`user_id_1`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Index pour la table `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reason_id` (`reason_id`),
  ADD KEY `accused_id` (`accused_id`),
  ADD KEY `complainant_id` (`complainant_id`);

--
-- Index pour la table `report_reason`
--
ALTER TABLE `report_reason`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gender_id` (`gender_id`);

--
-- Index pour la table `user_dog`
--
ALTER TABLE `user_dog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dog_id` (`dog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_filter`
--
ALTER TABLE `user_filter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_filter_dog_genders`
--
ALTER TABLE `user_filter_dog_genders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dog_gender_id` (`dog_gender_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_filter_dog_sizes`
--
ALTER TABLE `user_filter_dog_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `size_dog_id` (`size_dog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_filter_dog_temperament`
--
ALTER TABLE `user_filter_dog_temperament`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dog_temperament_id` (`dog_temperament_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_filter_gender`
--
ALTER TABLE `user_filter_gender`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_filter_hobbies`
--
ALTER TABLE `user_filter_hobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hobby_id` (`hobbies_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_gender_preferences`
--
ALTER TABLE `user_gender_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_hobbies`
--
ALTER TABLE `user_hobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hobbies_id` (`hobbies_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `banned`
--
ALTER TABLE `banned`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dog`
--
ALTER TABLE `dog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT pour la table `dog_genders`
--
ALTER TABLE `dog_genders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `dog_sizes`
--
ALTER TABLE `dog_sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `dog_temperaments`
--
ALTER TABLE `dog_temperaments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `hobbies`
--
ALTER TABLE `hobbies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `inflow`
--
ALTER TABLE `inflow`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `price`
--
ALTER TABLE `price`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `report`
--
ALTER TABLE `report`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `report_reason`
--
ALTER TABLE `report_reason`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_dog`
--
ALTER TABLE `user_dog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_filter`
--
ALTER TABLE `user_filter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_filter_dog_genders`
--
ALTER TABLE `user_filter_dog_genders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_filter_dog_sizes`
--
ALTER TABLE `user_filter_dog_sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_filter_dog_temperament`
--
ALTER TABLE `user_filter_dog_temperament`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_filter_gender`
--
ALTER TABLE `user_filter_gender`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_filter_hobbies`
--
ALTER TABLE `user_filter_hobbies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_gender_preferences`
--
ALTER TABLE `user_gender_preferences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_hobbies`
--
ALTER TABLE `user_hobbies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `banned`
--
ALTER TABLE `banned`
  ADD CONSTRAINT `banned_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `dog`
--
ALTER TABLE `dog`
  ADD CONSTRAINT `dog_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `dog_genders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `dog_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `dog_sizes` (`id`),
  ADD CONSTRAINT `dog_ibfk_3` FOREIGN KEY (`temperament_id`) REFERENCES `dog_temperaments` (`id`);

--
-- Contraintes pour la table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`user_id_0`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`user_id_1`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`reason_id`) REFERENCES `report_reason` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`accused_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `report_ibfk_3` FOREIGN KEY (`complainant_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `subscription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_dog`
--
ALTER TABLE `user_dog`
  ADD CONSTRAINT `user_dog_ibfk_1` FOREIGN KEY (`dog_id`) REFERENCES `dog` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_dog_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_filter`
--
ALTER TABLE `user_filter`
  ADD CONSTRAINT `user_filter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_filter_dog_genders`
--
ALTER TABLE `user_filter_dog_genders`
  ADD CONSTRAINT `user_filter_dog_genders_ibfk_1` FOREIGN KEY (`dog_gender_id`) REFERENCES `dog_genders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_filter_dog_genders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_filter_dog_sizes`
--
ALTER TABLE `user_filter_dog_sizes`
  ADD CONSTRAINT `user_filter_dog_sizes_ibfk_1` FOREIGN KEY (`size_dog_id`) REFERENCES `dog_sizes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_filter_dog_sizes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_filter_dog_temperament`
--
ALTER TABLE `user_filter_dog_temperament`
  ADD CONSTRAINT `user_filter_dog_temperament_ibfk_1` FOREIGN KEY (`dog_temperament_id`) REFERENCES `dog_temperaments` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_filter_dog_temperament_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_filter_gender`
--
ALTER TABLE `user_filter_gender`
  ADD CONSTRAINT `user_filter_gender_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_filter_gender_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_filter_hobbies`
--
ALTER TABLE `user_filter_hobbies`
  ADD CONSTRAINT `user_filter_hobbies_ibfk_1` FOREIGN KEY (`hobbies_id`) REFERENCES `hobbies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_filter_hobbies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_gender_preferences`
--
ALTER TABLE `user_gender_preferences`
  ADD CONSTRAINT `user_gender_preferences_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_gender_preferences_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `user_hobbies`
--
ALTER TABLE `user_hobbies`
  ADD CONSTRAINT `user_hobbies_ibfk_1` FOREIGN KEY (`hobbies_id`) REFERENCES `hobbies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_hobbies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

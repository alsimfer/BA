-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Erstellungszeit: 17. Aug 2016 um 18:29
-- Server-Version: 5.5.42
-- PHP-Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `ba_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arrangement`
--

CREATE TABLE `arrangement` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(1023) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date_time` datetime NOT NULL,
  `max_participants` int(11) NOT NULL,
  `sys_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `arrangement`
--

INSERT INTO `arrangement` (`id`, `name`, `description`, `date_time`, `max_participants`, `sys_user_id`) VALUES
(2, 'Aerobics', 'Intensives Kurs, besonders für ... gedacht. Beschränkungen:...', '2011-01-01 00:00:00', 30, NULL),
(17, 'Fitness', 'Sehr empfehlenswert', '2016-06-12 08:00:00', 12, NULL),
(18, 'Ausdauertraining', 'Ein guter Kurs für fortgeschrittene', '2016-08-14 00:00:00', 25, 15),
(19, 'Marathon', 'Los!', '2016-08-19 00:00:00', 1000, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `caretaker`
--

CREATE TABLE `caretaker` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `caretaker`
--

INSERT INTO `caretaker` (`id`, `first_name`, `last_name`, `email`, `phone_number`) VALUES
(1, 'Maik', 'Borchard', 'mike.borchard@ukr.net', '0176 656-32-123'),
(2, 'Julia', 'Envers', 'julia@ukr.net', '0172 312-51-312');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `coaching`
--

CREATE TABLE `coaching` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `sys_user_id` int(11) DEFAULT NULL,
  `week_goal` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_and_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `coaching`
--

INSERT INTO `coaching` (`id`, `patient_id`, `sys_user_id`, `week_goal`, `date_and_time`) VALUES
(2, 1, 13, 'blablabla1', '2016-08-24 00:00:00'),
(3, 9, 12, 'Bis 25.08.2016: -1 Kilo\r\nBis 18.09.2016: -es wäre möglich täglich 1 km zu joggen', '2016-08-18 00:00:00'),
(4, 9, 13, 'ein Ziel', '2016-08-25 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cron_task`
--

CREATE TABLE `cron_task` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commands` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `interval` int(11) NOT NULL,
  `lastrun` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `cron_task`
--

INSERT INTO `cron_task` (`id`, `name`, `commands`, `interval`, `lastrun`) VALUES
(1, 'Example asset symlinking task', 'a:1:{i:0;s:28:"assets:install --symlink web";}', 3600, '2016-08-17 18:15:00'),
(2, 'Example asset symlinking task', 'a:1:{i:0;s:28:"assets:install --symlink web";}', 3600, '2016-08-17 18:15:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hospital`
--

CREATE TABLE `hospital` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `hospital`
--

INSERT INTO `hospital` (`id`, `name`, `description`) VALUES
(1, 'Erstes Krankenhaus', 'Bescheibung des ersten Krankenhauses'),
(2, 'Zweites Krankenhaus', 'Bescheibung des zweiten Krankenhauses'),
(3, 'Drittes Krankenhauses', 'Bescheibung des dritten Krankenhauses');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `user_last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `log` varchar(5000) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `log`
--

INSERT INTO `log` (`id`, `user_last_name`, `user_first_name`, `field`, `action`, `object_id`, `date_time`, `log`) VALUES
(3, 'Smith', 'Alex', 'Patienten', 'erstellen', 12, '2016-08-16 12:34:41', 'id => 79\r\nfirstName => Lets\r\nlastName => see\r\naddress => we\r\nphoneNumber => what\r\nkrankenkassennummer => have\r\n'),
(4, 'Smith', 'Alex', 'Patienten', 'erstellen', 80, '2016-08-16 12:48:33', 'id => 80\nfirstName => asd\nlastName => fqwe\n'),
(5, 'Smith', 'Alex', 'Patienten', 'erstellen', 81, '2016-08-16 15:20:11', 'id => 81\n'),
(6, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 14, '2016-08-16 15:30:49', 'phoneNumber => asda\nsysUser => Maik Borchard (id = 12)\nhospital => Erstes Krankenhaus (id = 1)\n'),
(7, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 14, '2016-08-16 16:05:04', ''),
(8, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 10, '2016-08-16 16:06:04', 'birthDate => 14.07.1978 00:00:00\nsysUser => Maik Borchard (id = 12)\nhospital => Erstes Krankenhaus (id = 1)\n'),
(9, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 12, '2016-08-16 16:11:32', 'birthDate:  => 19.08.2016 00:00:00\naddress:  => wiener strasse\nsysUser:  => Maik Borchard (id = 12)\nkostentraegerabrechnungsbereich:  => Kostenträgerabrechnungsbereich\nkvBereich:  => KV-Bereich\n'),
(10, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 10, '2016-08-16 16:12:12', 'birthDate: 14.07.1978 00:00:00 => 18.07.1978 00:00:00\nsex: männlich => weiblich\nsysUser: Maik Borchard (id = 12) => Julia Envers (id = 13)\n'),
(11, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 9, '2016-08-16 16:20:22', ''),
(12, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 9, '2016-08-16 16:20:51', ''),
(13, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 9, '2016-08-16 16:25:37', ''),
(14, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 9, '2016-08-16 16:29:20', 'birthDate: 17.08.1901 00:00:00 => 17.08.1902 00:00:00\nkrankenkassennummer:  => Krankenkassennummer\nkrankenkasse:  => Krankenkasse\n'),
(15, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 9, '2016-08-16 16:32:56', 'birthDate: 17.08.1902 00:00:00 17.08.1903 00:00:00 '),
(16, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 9, '2016-08-16 16:34:15', 'kassennameZurBedruckung: Leer Kassenname zur Bedruckung '),
(17, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 3, '2016-08-16 16:35:56', 'address: Leer => sdfsdqwe'),
(18, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 11, '2016-08-16 16:43:37', 'birthDate: Leer => 11.08.2016 00:00:00address: Leer => asdqwephoneNumber: Leer => asdhospital: Leer => Erstes Krankenhaus (id = 1)'),
(19, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 11, '2016-08-16 16:44:24', 'phoneNumber: asd => asd\nsysUser: Leer => Maik Borchard (id = 12)\nsex: männlich => männlich\naddress: asdqwe => asdqwe\n'),
(20, 'Smith', 'Alex', 'Patienten', 'bearbeiten', 9, '2016-08-16 16:45:48', 'birthDate: 17.08.1903 00:00:00 => 17.08.1904 00:00:00\naddress: asdqwe => asdqweqwe\nemail: herzer@mail.ru => qwe@mail.ru\nhospital: Leer => Erstes Krankenhaus (id = 1)\nphoneNumber: 1234151 => Leer\n'),
(21, 'Smith', 'Alex', 'Patienten', 'löschen', 47, '2016-08-16 16:49:34', 'id: 47 => Leer\n'),
(22, 'Smith', 'Alex', 'Patienten', 'erstellen', 90, '2016-08-16 17:08:20', 'id: Leer => 90\nfirstName: Leer => Michael\nlastName: Leer => Fritz\nbirthDate: Leer => 18.08.1985 00:00:00\nsysUser: Leer => Julia Envers (id = 13)\nhospital: Leer => Zweites Krankenhaus (id = 2)\nkrankenversicherungsart: Leer => Private Krankenversicherung (GKV)\nvalidTill: Leer => 19.08.2017 00:00:00\n'),
(23, 'Smith', 'Alex', 'Patienten', 'erstellen', 91, '2016-08-16 17:27:09', 'id: Leer => 91\n'),
(24, 'Smith', 'Alex', 'Coachings', 'erstellen', 4, '2016-08-16 17:28:22', 'id: Leer => 4\npatient: Leer => Max Herzer (id = 9)\nsysUser: Leer => Julia Envers (id = 13)\nweekGoal: Leer => ein Ziel\ndateAndTime: Leer => 25.08.2016 00:00:00\n'),
(25, 'Smith', 'Alex', 'Untersuchungen', 'erstellen', 7, '2016-08-16 17:29:43', 'id: Leer => 7\npatient: Leer => Julian Bruns (id = 10)\nsysUser: Leer => Cersei Lannister (id = 11)\ntype: Leer => Basischeck\ndateAndTime: Leer => 18.08.2016 00:00:00\nsource: Leer => Empfehlung vom Hausarzt\narterielleHypertonie: Leer => 1\narterielleHypertonieText: Leer => irgendwas\nandereKardialeKomorbiditaeten: Leer => 1\ninsulinpflichtigerDiabetes: Leer => 1\ninsulinpflichtigerDiabetesText: Leer => noch irgendwas\npulmonaleKomorbiditaeten: Leer => 1\n'),
(26, 'Smith', 'Alex', 'Kurse', 'erstellen', 19, '2016-08-16 17:33:38', 'id: Leer => 19\nsysUser: Leer => TestKursleiiter TestKursleiiter2 (id = 15)\nname: Leer => Marathon\ndescription: Leer => Los!\ndateTime: Leer => 19.08.2016 00:00:00\nmaxParticipants: Leer => 1000\n'),
(27, 'Smith', 'Alex', 'Kurse', 'bearbeiten', 19, '2016-08-16 17:34:15', 'sysUser: TestKursleiiter TestKursleiiter2 (id = 15) => Leer\n'),
(28, 'Smith', 'Alex', 'Kursverläufe', 'löschen', 20, '2016-08-16 17:41:02', 'id: 20 => Leer\n'),
(29, 'Smith', 'Alex', 'Kursverläufe', 'erstellen', 21, '2016-08-16 17:41:20', 'id: Leer => 21\npatient: Leer => Max Herzer (id = 9)\narrangement: Leer => Marathon (id = 19)\n'),
(30, 'Smith', 'Alex', 'Kursverläufe', 'bearbeiten', 21, '2016-08-16 17:41:52', 'attended: Leer => 2\ncomments: Leer => Nicht schlecht\n'),
(31, 'Smith', 'Alex', 'Patienten', 'erstellen', 92, '2016-08-16 17:56:36', 'id: Leer => 92\n'),
(32, 'Smith', 'Alex', 'Patienten', 'löschen', 91, '2016-08-16 17:57:35', 'id: 91 => Leer\n'),
(33, 'Smith', 'Alex', 'Patienten', 'löschen', 92, '2016-08-16 17:57:44', 'id: 92 => Leer\n'),
(34, 'Smith', 'Alex', 'Patienten', 'löschen', 90, '2016-08-16 17:58:47', 'id: 90 => Leer\n'),
(35, 'Smith', 'Alex', 'Patienten', 'löschen', 89, '2016-08-16 17:58:50', 'id: 89 => Leer\n'),
(36, 'Smith', 'Alex', 'Patienten', 'löschen', 86, '2016-08-16 17:58:54', 'id: 86 => Leer\n'),
(37, 'Smith', 'Alex', 'Patienten', 'löschen', 85, '2016-08-16 17:58:57', 'id: 85 => Leer\n'),
(38, 'Smith', 'Alex', 'Patienten', 'löschen', 87, '2016-08-16 17:59:00', 'id: 87 => Leer\n'),
(39, 'Smith', 'Alex', 'Patienten', 'löschen', 84, '2016-08-16 17:59:03', 'id: 84 => Leer\n'),
(40, 'Smith', 'Alex', 'Patienten', 'löschen', 88, '2016-08-16 17:59:06', 'id: 88 => Leer\n'),
(41, 'Smith', 'Alex', 'Patienten', 'löschen', 82, '2016-08-16 17:59:09', 'id: 82 => Leer\n'),
(42, 'Smith', 'Alex', 'Benutzer', 'erstellen', 17, '2016-08-16 18:00:59', 'id: Leer => 17\nuserGroup: Leer => Admin (id = 2)\nemail: Leer => 2@ukr.net\npassword: Leer => da39a3ee5e6b4b0d3255bfef95601890afd80709\n'),
(43, 'Smith', 'Alex', 'Benutzer', 'einstellen', 1, '2016-08-16 18:04:40', 'sex: Leer => männlich\nphoneNumber: 1231241 => (0176) 647-49-047\n'),
(44, 'TestKursleiiter2', 'TestKursleiiter', 'Kursverläufe', 'erstellen', 22, '2016-08-16 20:51:07', 'id: Leer => 22\npatient: Leer => Frank Schmidt (id = 3)\narrangement: Leer => Marathon (id = 19)\nattended: Leer => 2\ncomments: Leer => Das war ihm zu schwierig\n'),
(45, 'TestKursleiiter2', 'TestKursleiiter', 'Kursverläufe', 'bearbeiten', 1, '2016-08-16 20:51:19', 'attended: 1 => 2\n'),
(46, 'Smith', 'Alex', 'Untersuchungen', 'bearbeiten', 7, '2016-08-17 10:15:48', 'arterielleHypertonieText: irgendwas => hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung\n'),
(47, 'Smith', 'Alex', 'Benutzer', 'bearbeiten', 10, '2016-08-17 15:22:24', 'email: blabla123@ukr.net => 2@ukr.net\n');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `med_checkup`
--

CREATE TABLE `med_checkup` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_and_time` datetime NOT NULL,
  `sys_user_id` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `waist` int(11) DEFAULT NULL,
  `hips` int(11) DEFAULT NULL,
  `source` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `arterielle_hypertonie` tinyint(1) NOT NULL,
  `arterielle_hypertonie_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `andere_kardiale_komorbiditaeten` tinyint(1) NOT NULL,
  `andere_kardiale_komorbiditaeten_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `insulinpflichtiger_diabetes` tinyint(1) NOT NULL,
  `insulinpflichtiger_diabetes_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nicht_insulinpflichtiger_diabetes` tinyint(1) NOT NULL,
  `nicht_insulinpflichtiger_diabetes_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pulmonale_komorbiditaeten` tinyint(1) NOT NULL,
  `pulmonale_komorbiditaeten_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fettstoffwechselstoerungen` tinyint(1) NOT NULL,
  `fettstoffwechselstoerungen_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `endokrine_komorbiditaeten` tinyint(1) NOT NULL,
  `endokrine_komorbiditaeten_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `gastroenterologische_komorbiditaeten` tinyint(1) NOT NULL,
  `gastroenterologische_komorbiditaeten_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `varikosis` tinyint(1) NOT NULL,
  `varikosis_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `orthopaedische_komorbiditaeten` tinyint(1) NOT NULL,
  `orthopaedische_komorbiditaeten_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `neurologische_komorbiditaeten` tinyint(1) NOT NULL,
  `neurologische_komorbiditaeten_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `renale_komorbiditaeten` tinyint(1) NOT NULL,
  `renale_komorbiditaeten_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `oedeme` tinyint(1) NOT NULL,
  `oedeme_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `organtransplantation` tinyint(1) NOT NULL,
  `organtransplantation_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `prader_willi_syndrom` tinyint(1) NOT NULL,
  `prader_willi_syndrom_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nikotinabusus` tinyint(1) NOT NULL,
  `nikotinabusus_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `alkoholabusus` tinyint(1) NOT NULL,
  `alkoholabusus_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `weiteres` tinyint(1) NOT NULL,
  `weiteres_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `med_checkup`
--

INSERT INTO `med_checkup` (`id`, `patient_id`, `type`, `date_and_time`, `sys_user_id`, `height`, `weight`, `waist`, `hips`, `source`, `arterielle_hypertonie`, `arterielle_hypertonie_text`, `andere_kardiale_komorbiditaeten`, `andere_kardiale_komorbiditaeten_text`, `insulinpflichtiger_diabetes`, `insulinpflichtiger_diabetes_text`, `nicht_insulinpflichtiger_diabetes`, `nicht_insulinpflichtiger_diabetes_text`, `pulmonale_komorbiditaeten`, `pulmonale_komorbiditaeten_text`, `fettstoffwechselstoerungen`, `fettstoffwechselstoerungen_text`, `endokrine_komorbiditaeten`, `endokrine_komorbiditaeten_text`, `gastroenterologische_komorbiditaeten`, `gastroenterologische_komorbiditaeten_text`, `varikosis`, `varikosis_text`, `orthopaedische_komorbiditaeten`, `orthopaedische_komorbiditaeten_text`, `neurologische_komorbiditaeten`, `neurologische_komorbiditaeten_text`, `renale_komorbiditaeten`, `renale_komorbiditaeten_text`, `oedeme`, `oedeme_text`, `organtransplantation`, `organtransplantation_text`, `prader_willi_syndrom`, `prader_willi_syndrom_text`, `nikotinabusus`, `nikotinabusus_text`, `alkoholabusus`, `alkoholabusus_text`, `weiteres`, `weiteres_text`) VALUES
(1, 1, 'Basischeck', '2016-07-26 10:49:36', 10, 0, 0, 0, 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, ''),
(2, 3, 'Basischeck', '2016-07-26 10:49:56', 10, 0, 0, 0, 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, ''),
(3, 1, 'Basischeck', '2016-07-21 16:35:00', 10, 0, 0, 0, 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, ''),
(4, 1, 'Basischeck', '2016-08-26 00:00:00', 10, 0, 0, 0, 0, '', 1, 'Nein', 1, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, ''),
(6, 9, 'Basischeck', '2016-08-02 08:15:00', 10, 180, 180, 150, 180, 'Information über digitale Medien (Internet allgemein, Google-Suche etc.)', 1, 'alles schrecklich', 0, '', 0, '', 1, '', 0, '', 0, '', 1, '', 0, '', 0, '', 0, '', 1, '', 0, '', 0, '', 0, '', 0, '', 1, 'Raucht wie eine Dampflokomotive', 0, '', 0, ''),
(7, 10, 'Basischeck', '2016-08-18 00:00:00', 11, NULL, NULL, NULL, NULL, 'Empfehlung vom Hausarzt', 1, 'hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung hier kommt eine lange beschreibung', 1, '', 1, 'noch irgendwas', 0, '', 1, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigation_rules`
--

CREATE TABLE `navigation_rules` (
  `id` int(11) NOT NULL,
  `user_group_id` int(11) DEFAULT NULL,
  `nav_li_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urls_permitted` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `navigation_rules`
--

INSERT INTO `navigation_rules` (`id`, `user_group_id`, `nav_li_id`, `path`, `button_name`, `urls_permitted`) VALUES
(1, 1, 'patients_li', 'patientsPage', 'Patienten', '/patients,/patients/create,/patients/edit,/patients/info,/patients/delete'),
(2, 1, 'coachings_li', 'coachingsPage', 'Coachings', '/coachings,/coachings/create,/coachings/edit,/coachings/info,/coachings/delete'),
(3, 1, 'med_checkups_li', 'medCheckupsPage', 'Untersuchungen', '/med-checkups,/med-checkups/create,/med-checkups/edit,/med-checkups/info,/med-checkups/delete'),
(4, 1, 'arrangements_li', 'arrangementsPage', 'Kurse', '/arrangements,/arrangements/create,/arrangements/edit,/arrangements/info,/arrangements/delete'),
(5, 1, 'patient_arrangements_li', 'patientArrangementPage', 'Kursverlauf', '/patient-arrangements,/patient-arrangements/create,/patient-arrangements/edit,/patient-arrangements/info,/patient-arrangements/delete'),
(6, 1, 'logs_li', 'logsPage', 'Änderungsprotokoll', '/logs,/logs/info'),
(7, 1, 'users_li', 'usersPage', 'Benutzer', '/users,/users/create,/users/edit,/users/info,/users/delete'),
(8, 2, 'patients_li', 'patientsPage', 'Patienten', '/patients,/patients/create,/patients/edit,/patients/info,/patients/delete'),
(9, 2, 'coachings_li', 'coachingsPage', 'Coachings', '/coachings,/coachings/create,/coachings/edit,/coachings/info,/coachings/delete'),
(10, 2, 'med_checkups_li', 'medCheckupsPage', 'Untersuchungen', '/med-checkups,/med-checkups/create,/med-checkups/edit,/med-checkups/info,/med-checkups/delete'),
(11, 2, 'arrangements_li', 'arrangementsPage', 'Kurse', '/arrangements,/arrangements/create,/arrangements/edit,/arrangements/info,/arrangements/delete'),
(12, 2, 'patient_arrangements_li', 'patientArrangementPage', 'Kursverlauf', '/patient-arrangements,/patient-arrangements/create,/patient-arrangements/edit,/patient-arrangements/info,/patient-arrangements/delete'),
(13, 2, 'logs_li', 'logsPage', 'Änderungsprotokoll', '/logs,/logs/info'),
(14, 2, 'users_li', 'usersPage', 'Benutzer', '/users,/users/create,/users/edit,/users/info,/users/delete'),
(15, 3, 'patients_li', 'patientsPage', 'Patienten', '/patients,/patients/create,/patients/edit,/patients/info,/patients/delete'),
(16, 3, 'coachings_li', 'coachingsPage', 'Coachings', '/coachings,/coachings/create,/coachings/edit,/coachings/info,/coachings/delete'),
(17, 3, 'med_checkups_li', 'medCheckupsPage', 'Untersuchungen', '/med-checkups,/med-checkups/create,/med-checkups/edit,/med-checkups/info,/med-checkups/delete'),
(18, 3, 'arrangements_li', 'arrangementsPage', 'Kurse', '/arrangements,/arrangements/create,/arrangements/edit,/arrangements/info,/arrangements/delete'),
(19, 3, 'patient_arrangements_li', 'patientArrangementPage', 'Kursverlauf', '/patient-arrangements,/patient-arrangements/create,/patient-arrangements/edit,/patient-arrangements/info,/patient-arrangements/delete'),
(20, 4, 'patients_li', 'patientsPage', 'Patienten', '/patients,/patients/create,/patients/edit,/patients/info,/patients/delete'),
(21, 4, 'coachings_li', 'coachingsPage', 'Coachings', '/coachings,/coachings/create,/coachings/edit,/coachings/info,/coachings/delete'),
(22, 4, 'med_checkups_li', 'medCheckupsPage', 'Untersuchungen', '/med-checkups,/med-checkups/create,/med-checkups/edit,/med-checkups/info,/med-checkups/delete'),
(23, 4, 'arrangements_li', 'arrangementsPage', 'Kurse', '/arrangements,/arrangements/create,/arrangements/edit,/arrangements/info,/arrangements/delete'),
(24, 4, 'patient_arrangements_li', 'patientArrangementPage', 'Kursverlauf', '/patient-arrangements,/patient-arrangements/create,/patient-arrangements/edit,/patient-arrangements/info,/patient-arrangements/delete'),
(25, 5, 'patients_li', 'patientsPage', 'Patienten', '/patients,/patients/create,/patients/edit,/patients/info,/patients/delete'),
(26, 5, 'coachings_li', 'coachingsPage', 'Coachings', '/coachings,/coachings/create,/coachings/edit,/coachings/info,/coachings/delete'),
(27, 5, 'med_checkups_li', 'medCheckupsPage', 'Untersuchungen', '/med-checkups,/med-checkups/create,/med-checkups/edit,/med-checkups/info,/med-checkups/delete'),
(28, 5, 'arrangements_li', 'arrangementsPage', 'Kurse', '/arrangements,/arrangements/create,/arrangements/edit,/arrangements/info,/arrangements/delete'),
(29, 5, 'patient_arrangements_li', 'patientArrangementPage', 'Kursverlauf', '/patient-arrangements,/patient-arrangements/create,/patient-arrangements/edit,/patient-arrangements/info,/patient-arrangements/delete'),
(30, 6, 'patient_arrangements_li', 'patientArrangementPage', 'Kursverlauf', '/patient-arrangements,/patient-arrangements/create,/patient-arrangements/edit,/patient-arrangements/info,/patient-arrangements/delete');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `phone_number` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hospital_id` int(11) DEFAULT NULL,
  `sys_user_id` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `sex` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `krankenversicherungsart` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `krankenkassennummer` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `krankenkasse` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `kassenname_zur_bedruckung` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `versichertennummer` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `egk_versicherten_nr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `kostentraegerabrechnungsbereich` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `kv_bereich` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `abrechnungsvknr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sonstige` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `versichertenartmfr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `versichertenstatuskvk` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `statusergaenzung` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `valid_till` date DEFAULT NULL,
  `abrechnungsform` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nachsorge` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `patient`
--

INSERT INTO `patient` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `hospital_id`, `sys_user_id`, `birth_date`, `sex`, `address`, `krankenversicherungsart`, `krankenkassennummer`, `krankenkasse`, `kassenname_zur_bedruckung`, `versichertennummer`, `egk_versicherten_nr`, `kostentraegerabrechnungsbereich`, `kv_bereich`, `abrechnungsvknr`, `sonstige`, `versichertenartmfr`, `versichertenstatuskvk`, `statusergaenzung`, `valid_till`, `abrechnungsform`, `nachsorge`) VALUES
(1, 'Hans', 'Friedrich', 'qwe@gmail.com', '12312312', 2, 12, '1982-11-12', 'männlich', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(3, 'Frank', 'Schmidt', 'frank@schmidt.com', '', NULL, NULL, '1980-12-01', 'männlich', 'sdfsdqwe', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(8, 'Max', 'Mustermann', '123@web.de', '1231aasdq', 1, 12, '1980-11-08', 'männlich', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(9, 'Max', 'Herzer', 'qwe@mail.ru', '', 1, NULL, '1904-08-17', '', 'asdqweqwe', '', 'Krankenkassennummer', 'Krankenkasse', 'Kassenname zur Bedruckung', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(10, 'Julian', 'Bruns', 'qweqwrqwrqrqasd@email.com', '', 1, 13, '1978-07-18', 'weiblich', 'sdf', '', '', '', '', '', 'eGK-Versicherten-Nr', '', '', '', '', '', '', '', NULL, '', ''),
(11, 'Markus', 'Feld', 'markus.feld@ukr.net', 'asdasd', 1, 12, '2016-08-11', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(12, 'Jan', 'Steiner', 'jan.steiner@hs-bremen.de', '+49 (172) 123-41-121', NULL, 12, '2016-08-19', 'männlich', 'wiener strasse', '', '', '', '', '', '', 'Kostenträgerabrechnungsbereich', 'KV-Bereich', '', '', '', '', '', NULL, '', ''),
(13, 'Philipp', 'Frühling', 'p.fruehling@ukr.net', '1231415123aasd', NULL, NULL, NULL, 'männlich', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(14, 'Patrik', 'Staffhorst', 'p.staffhorst@ukr.net', 'asdaasda', 2, 12, '1978-07-14', 'männlich', 'blablablaadresseqwe', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(15, 'Emil', 'Schwarzer', 'e.schwarzer@aok.com', '-', NULL, NULL, '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(16, 'Lena', 'Musterfrau', 'l.musterfrau@ukr.net', '', NULL, NULL, '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(17, 'Jessica', 'Dahlhaus', 'j.dahlhaus@ukr.net', '', NULL, NULL, '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(18, 'Sabine', 'Merkel', 's.merkel@mail.ru', '', NULL, NULL, '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(19, 'Thomas', 'Lange', 'lange@ukr.net', '', NULL, NULL, '2012-03-01', 'männlich', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', ''),
(83, 'Michael', 'Fritz', '', '', 2, 13, '1985-08-18', '', '', 'Private Krankenversicherung (GKV)', '', '', '', '', '', '', '', '', '', '', '', '', '2017-08-19', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `patient_arrangement_reference`
--

CREATE TABLE `patient_arrangement_reference` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `arrangement_id` int(11) DEFAULT NULL,
  `attended` int(11) DEFAULT '0',
  `comments` varchar(1023) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `patient_arrangement_reference`
--

INSERT INTO `patient_arrangement_reference` (`id`, `patient_id`, `arrangement_id`, `attended`, `comments`) VALUES
(1, 1, 2, 2, ''),
(2, 3, 2, 1, 'Der hat richtig gut gemacht :-)'),
(7, 9, 17, 2, ''),
(8, 10, 2, 2, ''),
(21, 9, 19, 2, 'Nicht schlecht'),
(22, 3, 19, 2, 'Das war ihm zu schwierig');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sys_user`
--

CREATE TABLE `sys_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_group_id` int(11) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `sex` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hospital_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `sys_user`
--

INSERT INTO `sys_user` (`id`, `first_name`, `last_name`, `email`, `password`, `phone_number`, `user_group_id`, `birth_date`, `sex`, `address`, `hospital_id`) VALUES
(1, 'Alex', 'Smith', 'alsimfer@gmail.com', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '(0176) 647-49-047', 1, NULL, 'männlich', '', NULL),
(4, 'Thomas', 'Müller', 'qwerth@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '', 3, NULL, '', '', NULL),
(9, 'Alexander', 'Johnson', 'write_me_letters@mail.ru', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '11234101a', 2, NULL, '', '', NULL),
(10, 'Uwe', 'Boll', '2@ukr.net', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '1231', 4, NULL, '', '', 2),
(11, 'Cersei', 'Lannister', 'cercei.lannister@web.de', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '1234567890', 4, NULL, '', '', NULL),
(12, 'Maik', 'Borchard', 'maik.borchard@ukr.net', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '12341231', 5, NULL, '', '', NULL),
(13, 'Julia', 'Envers', 'julia.envers@ukr.net', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '241590820384', 5, NULL, '', '', NULL),
(15, 'TestKursleiiter', 'TestKursleiiter2', '1@ukr.net', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', '', 6, NULL, 'männlich', '', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `user_group`
--

INSERT INTO `user_group` (`id`, `name`, `description`) VALUES
(1, 'ROOT', 'Darf alles, für Testzwecke reserviert'),
(2, 'Admin', 'Hat Zugriff auf Patienten, Coachings, Untersuchungen, Kursen, Kursverläufe, Systemprotokolle und Benutzer'),
(3, 'Public', 'Hat Zugriff auf Patienten, Coachings, Untersuchungen, Kursen, Kursverläufe'),
(4, 'Arzt', 'Hat alle Public rechte und kann Untersuchungen durchführen'),
(5, 'Coach', 'Hat alle public Rechte und kann Verhaltenstrainings durchführen'),
(6, 'Kursleiter', 'Hat Zugriff nur auf eigene Kurse und Kursverläufe');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `arrangement`
--
ALTER TABLE `arrangement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7E99C3B9793139DD` (`sys_user_id`);

--
-- Indizes für die Tabelle `caretaker`
--
ALTER TABLE `caretaker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D1A32B5BE7927C74` (`email`);

--
-- Indizes für die Tabelle `coaching`
--
ALTER TABLE `coaching`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CABE08CE6B899279` (`patient_id`),
  ADD KEY `IDX_CABE08CE793139DD` (`sys_user_id`);

--
-- Indizes für die Tabelle `cron_task`
--
ALTER TABLE `cron_task`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `med_checkup`
--
ALTER TABLE `med_checkup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2AF737F46B899279` (`patient_id`),
  ADD KEY `IDX_2AF737F4793139DD` (`sys_user_id`);

--
-- Indizes für die Tabelle `navigation_rules`
--
ALTER TABLE `navigation_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_37A609F91ED93D47` (`user_group_id`);

--
-- Indizes für die Tabelle `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1ADAD7EB63DBB69` (`hospital_id`),
  ADD KEY `IDX_1ADAD7EB793139DD` (`sys_user_id`);

--
-- Indizes für die Tabelle `patient_arrangement_reference`
--
ALTER TABLE `patient_arrangement_reference`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_24DB82436B899279` (`patient_id`),
  ADD KEY `IDX_24DB8243C5CAAFBC` (`arrangement_id`);

--
-- Indizes für die Tabelle `sys_user`
--
ALTER TABLE `sys_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A52FF2C2E7927C74` (`email`),
  ADD KEY `IDX_A52FF2C21ED93D47` (`user_group_id`),
  ADD KEY `IDX_A52FF2C263DBB69` (`hospital_id`);

--
-- Indizes für die Tabelle `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `arrangement`
--
ALTER TABLE `arrangement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT für Tabelle `caretaker`
--
ALTER TABLE `caretaker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `coaching`
--
ALTER TABLE `coaching`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `cron_task`
--
ALTER TABLE `cron_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `hospital`
--
ALTER TABLE `hospital`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT für Tabelle `med_checkup`
--
ALTER TABLE `med_checkup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `navigation_rules`
--
ALTER TABLE `navigation_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT für Tabelle `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT für Tabelle `patient_arrangement_reference`
--
ALTER TABLE `patient_arrangement_reference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT für Tabelle `sys_user`
--
ALTER TABLE `sys_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT für Tabelle `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `arrangement`
--
ALTER TABLE `arrangement`
  ADD CONSTRAINT `FK_7E99C3B9793139DD` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_user` (`id`);

--
-- Constraints der Tabelle `coaching`
--
ALTER TABLE `coaching`
  ADD CONSTRAINT `FK_CABE08CE6B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `FK_CABE08CE793139DD` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_user` (`id`);

--
-- Constraints der Tabelle `med_checkup`
--
ALTER TABLE `med_checkup`
  ADD CONSTRAINT `FK_2AF737F46B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `FK_2AF737F4793139DD` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_user` (`id`);

--
-- Constraints der Tabelle `navigation_rules`
--
ALTER TABLE `navigation_rules`
  ADD CONSTRAINT `FK_37A609F91ED93D47` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`);

--
-- Constraints der Tabelle `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `FK_1ADAD7EB63DBB69` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`),
  ADD CONSTRAINT `FK_1ADAD7EB793139DD` FOREIGN KEY (`sys_user_id`) REFERENCES `sys_user` (`id`);

--
-- Constraints der Tabelle `patient_arrangement_reference`
--
ALTER TABLE `patient_arrangement_reference`
  ADD CONSTRAINT `FK_24DB82436B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `FK_24DB8243C5CAAFBC` FOREIGN KEY (`arrangement_id`) REFERENCES `arrangement` (`id`);

--
-- Constraints der Tabelle `sys_user`
--
ALTER TABLE `sys_user`
  ADD CONSTRAINT `FK_A52FF2C21ED93D47` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`),
  ADD CONSTRAINT `FK_A52FF2C263DBB69` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`id`);

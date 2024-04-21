-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.33 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour neitsabcms
CREATE DATABASE IF NOT EXISTS `neitsabcms` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `neitsabcms`;

-- Listage de la structure de la table neitsabcms. components
CREATE TABLE IF NOT EXISTS `components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.components : ~2 rows (environ)
/*!40000 ALTER TABLE `components` DISABLE KEYS */;
INSERT INTO `components` (`id`, `name`, `description`, `path`) VALUES
	(4, 'Nom du composant', 'Description du composant', 'C:\\Users\\basti\\code\\laragon\\www\\framework/app/themes\\default/components/hero');
/*!40000 ALTER TABLE `components` ENABLE KEYS */;

-- Listage de la structure de la table neitsabcms. components_fields
CREATE TABLE IF NOT EXISTS `components_fields` (
  `component_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.components_fields : ~0 rows (environ)
/*!40000 ALTER TABLE `components_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `components_fields` ENABLE KEYS */;

-- Listage de la structure de la table neitsabcms. components_pages
CREATE TABLE IF NOT EXISTS `components_pages` (
  `component_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `order` int(11) DEFAULT '0',
  KEY `FK__components` (`component_id`),
  KEY `FK__pages` (`page_id`),
  CONSTRAINT `FK__components` FOREIGN KEY (`component_id`) REFERENCES `components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__pages` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.components_pages : ~0 rows (environ)
/*!40000 ALTER TABLE `components_pages` DISABLE KEYS */;
INSERT INTO `components_pages` (`component_id`, `page_id`, `order`) VALUES
	(4, 1, 0);
/*!40000 ALTER TABLE `components_pages` ENABLE KEYS */;

-- Listage de la structure de la table neitsabcms. component_options
CREATE TABLE IF NOT EXISTS `component_options` (
  `component_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `option_key` varchar(50) NOT NULL,
  `option_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.component_options : ~0 rows (environ)
/*!40000 ALTER TABLE `component_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `component_options` ENABLE KEYS */;

-- Listage de la structure de la table neitsabcms. fields
CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.fields : ~0 rows (environ)
/*!40000 ALTER TABLE `fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `fields` ENABLE KEYS */;

-- Listage de la structure de la table neitsabcms. migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.migrations : ~0 rows (environ)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`) VALUES
	(1, 'm20240415_create_posts_table.php');
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Listage de la structure de la table neitsabcms. pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `layout` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.pages : ~0 rows (environ)
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` (`id`, `slug`, `title`, `layout`) VALUES
	(1, '/login', 'Login', 'default');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;

-- Listage de la structure de la table neitsabcms. posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Listage des données de la table neitsabcms.posts : ~0 rows (environ)
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

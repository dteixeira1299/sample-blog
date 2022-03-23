-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sample_blog
DROP DATABASE IF EXISTS `sample_blog`;
CREATE DATABASE IF NOT EXISTS `sample_blog` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sample_blog`;

-- Dumping structure for table sample_blog.posts
DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id_post` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned DEFAULT '0',
  `title` varchar(50) DEFAULT NULL,
  `content` text,
  `post_code` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table sample_blog.posts: ~0 rows (approximately)
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;

-- Dumping structure for table sample_blog.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varbinary(200) DEFAULT NULL,
  `email` varbinary(200) DEFAULT NULL,
  `psw` varchar(100) DEFAULT NULL,
  `profile` tinyint(3) unsigned DEFAULT '1',
  `user_code` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `email_verified` datetime DEFAULT NULL,
  `receive_newsletter` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table sample_blog.users: ~0 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id_user`, `username`, `email`, `psw`, `profile`, `user_code`, `last_login`, `email_verified`, `receive_newsletter`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, _binary 0xf5d1f32f3ab923e0689df245e2e6132b, _binary 0xb83a16a6863522552e11e30bb3f1a10b3041bef404417d637f8859b5deefa18c, '$2y$10$fyXetiKMMB1qyTFsSdgrlu2/3joaAtFD5mkW0MdEzZtbrTGNnmYb.', 1, '22437f37ddcab661eb4dac35437f8eae', NULL, '2022-03-23 19:06:52', '2022-03-23 19:03:39', '2022-03-23 19:03:39', '2022-03-23 19:03:39', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

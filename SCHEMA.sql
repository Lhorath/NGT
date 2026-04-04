-- Nerdy Gamer Tools — schema derived from PHP (mysqli queries + bind_param types)
-- Run in HeidiSQL against your MySQL/MariaDB server (matches engine/config.php DB_NAME).

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `ngt_webdb`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `ngt_webdb`;

DROP TABLE IF EXISTS `user_roles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `downloads`;
DROP TABLE IF EXISTS `news`;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------------
-- roles: register.php assigns new users role_id = 3 (must stay "Member").
-- admin.php: roles named 'Admin' and 'Member' cannot be deleted from the UI.
-- ---------------------------------------------------------------------------
CREATE TABLE `roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`) VALUES
  (1, 'Admin'),
  (2, 'Moderator'),
  (3, 'Member');

ALTER TABLE `roles` AUTO_INCREMENT = 4;

-- ---------------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `display_name` VARCHAR(64) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL COMMENT 'password_hash() output',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  UNIQUE KEY `uq_users_display_name` (`display_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- user_roles (junction)
-- ---------------------------------------------------------------------------
CREATE TABLE `user_roles` (
  `user_id` INT UNSIGNED NOT NULL,
  `role_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  KEY `idx_user_roles_role` (`role_id`),
  CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- news (admin inserts omit publish_date — DB default applies)
-- ---------------------------------------------------------------------------
CREATE TABLE `news` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(191) NOT NULL,
  `body` MEDIUMTEXT NOT NULL,
  `publish_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_url` VARCHAR(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_news_publish_date` (`publish_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- downloads (admin insert sets project_name, version, description, file_path, file_size)
-- ---------------------------------------------------------------------------
CREATE TABLE `downloads` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` VARCHAR(191) NOT NULL,
  `version` VARCHAR(64) NOT NULL,
  `description` TEXT NULL,
  `file_path` VARCHAR(255) NOT NULL COMMENT 'filename under DOCUMENT_ROOT/releases/',
  `file_size` INT UNSIGNED NOT NULL DEFAULT 0,
  `download_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `upload_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_downloads_project` (`project_name`),
  KEY `idx_downloads_upload_date` (`upload_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Optional: grant Admin after registering (replace YOUR_USER_ID):
--   INSERT INTO user_roles (user_id, role_id) VALUES (YOUR_USER_ID, 1);
-- Or swap Member for Admin:
--   UPDATE user_roles SET role_id = 1 WHERE user_id = YOUR_USER_ID AND role_id = 3;
-- ---------------------------------------------------------------------------

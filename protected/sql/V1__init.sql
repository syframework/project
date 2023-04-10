SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_page
-- ----------------------------
CREATE TABLE `t_page` (
  `id` varchar(24) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'none',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'none',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of t_page
-- ----------------------------
INSERT INTO `t_page` (id, title) VALUES ('about-us', 'About us');
INSERT INTO `t_page` (id, title) VALUES ('home', 'Home');
INSERT INTO `t_page` (id, title) VALUES ('privacy', 'Privacy policy');
INSERT INTO `t_page` (id, title) VALUES ('use', 'Conditions of use');
INSERT INTO `t_page` (id, title) VALUES ('user-account', 'My account');
INSERT INTO `t_page` (id, title) VALUES ('user-connection', 'Sign In');
INSERT INTO `t_page` (id, title) VALUES ('user-password', 'Choose a new password');

-- ----------------------------
-- Table structure for t_page_history
-- ----------------------------
CREATE TABLE `t_page_history` (
  `page_id` varchar(24) NOT NULL,
  `page_crc32` bigint NOT NULL,
  `page_content` text NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_id`,`page_crc32`),
  KEY `t_page_history_ibfk_1` (`user_id`) USING BTREE,
  CONSTRAINT `t_page_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `t_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `t_page_history_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `t_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
CREATE TABLE `t_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(32) DEFAULT 'user',
  `firstname` varchar(64) NOT NULL DEFAULT '',
  `lastname` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(512) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL,
  `phone` varchar(24) NOT NULL DEFAULT '',
  `language` varchar(2) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_connection_at` datetime DEFAULT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `ip` int unsigned DEFAULT NULL,
  `token` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`) USING BTREE,
  KEY `role` (`role`) USING BTREE,
  CONSTRAINT `t_user_ibfk_1` FOREIGN KEY (`role`) REFERENCES `t_user_role` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Table structure for t_user_has_permission
-- ----------------------------
CREATE TABLE `t_user_has_permission` (
  `id` int unsigned NOT NULL,
  `permission` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`permission`),
  KEY `permission` (`permission`) USING BTREE,
  CONSTRAINT `t_user_has_permission_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_user_has_permission_ibfk_2` FOREIGN KEY (`permission`) REFERENCES `t_user_permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Table structure for t_user_permission
-- ----------------------------
CREATE TABLE `t_user_permission` (
  `id` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of t_user_permission
-- ----------------------------
INSERT INTO `t_user_permission` VALUES ('page-create', 'Create a new page');
INSERT INTO `t_user_permission` VALUES ('page-delete', 'Delete a page');
INSERT INTO `t_user_permission` VALUES ('page-code', 'Update page source code');
INSERT INTO `t_user_permission` VALUES ('page-update', 'Update a page settings');
INSERT INTO `t_user_permission` VALUES ('page-update-inline', 'Update a page with inline edition (need page-update)');

-- ----------------------------
-- Table structure for t_user_role
-- ----------------------------
CREATE TABLE `t_user_role` (
  `id` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of t_user_role
-- ----------------------------
INSERT INTO `t_user_role` VALUES ('admin', 'Administrator');
INSERT INTO `t_user_role` VALUES ('blacklisted', 'Blacklisted user');
INSERT INTO `t_user_role` VALUES ('super-admin', 'Super administrator');
INSERT INTO `t_user_role` VALUES ('user', 'Simple user');

-- ----------------------------
-- Table structure for t_user_role_has_permission
-- ----------------------------
CREATE TABLE `t_user_role_has_permission` (
  `id_role` varchar(32) NOT NULL,
  `id_permission` varchar(32) NOT NULL,
  PRIMARY KEY (`id_role`,`id_permission`),
  KEY `fk_t_user_role_has_t_user_permission_t_user_role1` (`id_role`) USING BTREE,
  KEY `fk_t_user_role_has_t_user_permission_t_user_permission1` (`id_permission`) USING BTREE,
  CONSTRAINT `t_user_role_has_permission_ibfk_1` FOREIGN KEY (`id_permission`) REFERENCES `t_user_permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_user_role_has_permission_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `t_user_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of t_user_role_has_permission
-- ----------------------------
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-create');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-code');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-delete');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-update');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-update-inline');

-- ----------------------------
-- Table structure for t_user_setting
-- ----------------------------
CREATE TABLE `t_user_setting` (
  `user_id` int unsigned NOT NULL,
  `key` varchar(32) NOT NULL,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`,`key`),
  CONSTRAINT `t_user_setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `t_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

SET FOREIGN_KEY_CHECKS=1;
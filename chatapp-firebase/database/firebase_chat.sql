/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3306
 Source Schema         : firebase_chat

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 06/03/2023 19:51:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for conversation_stamp
-- ----------------------------
DROP TABLE IF EXISTS `conversation_stamp`;
CREATE TABLE `conversation_stamp`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `stamp_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stamp_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of conversation_stamp
-- ----------------------------
INSERT INTO `conversation_stamp` VALUES (1, 'Good Morning', 'https://media.giphy.com/media/1vYThCrZAsQU36nqkv/giphy.gif', '2023-03-06 18:06:01', '2023-03-06 18:06:04');
INSERT INTO `conversation_stamp` VALUES (2, 'Valentines Day Love', 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExMzYxNDdkYjI4ODYwMWY0ZWRiNTc5MWYzZTU2ODgyYWE1ZjJkODZiOCZjdD1n/h7Sowhj6EQSdxrvhrH/giphy.gif', '2023-03-06 18:07:50', '2023-03-06 18:07:53');
INSERT INTO `conversation_stamp` VALUES (3, 'Day Love', 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExYjY0YTNlZjFlNGFhZThhYzExM2Y3ZDc1ODAwMGZkNTJiODYzM2JhNCZjdD1n/0T559s8zghIiob0dhe/giphy.gif', '2023-03-06 18:08:37', '2023-03-06 18:08:39');
INSERT INTO `conversation_stamp` VALUES (4, 'Wooo', 'https://media.giphy.com/media/Bm31tGMWYF7feTIb8j/giphy.gif', '2023-03-06 18:09:37', '2023-03-06 18:09:39');
INSERT INTO `conversation_stamp` VALUES (5, 'Goodnight', 'https://media.giphy.com/media/1X4AaVSmnhT9umLneW/giphy.gif', '2023-03-06 18:09:56', '2023-03-06 18:09:58');
INSERT INTO `conversation_stamp` VALUES (6, 'Couple Love', 'https://media.giphy.com/media/1X4AaVSmnhT9umLneW/giphy.gif', '2023-03-06 18:10:29', '2023-03-06 18:10:31');
INSERT INTO `conversation_stamp` VALUES (7, 'Snow White Kiss', 'https://media.giphy.com/media/AIDv87fiokBva/giphy.gif', '2023-03-06 18:10:58', '2023-03-06 18:11:00');
INSERT INTO `conversation_stamp` VALUES (8, 'Cat', 'https://media.giphy.com/media/W3QKEujo8vztC/giphy.gif', '2023-03-06 18:11:31', '2023-03-06 18:11:33');
INSERT INTO `conversation_stamp` VALUES (9, 'Cat Massaging', 'https://media.giphy.com/media/W3QKEujo8vztC/giphy.gif', '2023-03-06 18:12:29', '2023-03-06 18:12:32');
INSERT INTO `conversation_stamp` VALUES (10, 'Thank U', 'https://media.giphy.com/media/4Rd3RQPeksO6RvBPXu/giphy.gif', '2023-03-06 18:13:01', '2023-03-06 18:13:04');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2023_03_04_103128_create_stamp_table', 2);
INSERT INTO `migrations` VALUES (6, '2023_03_04_170620_create_conversation_stamp_table', 3);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp(0) NULL DEFAULT NULL,
  `expires_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('988998d4-4298-4183-978d-264c454ed5ef', 'Thanh Trường', 'truong@example.com', '2023-02-23 15:50:19', '$2y$10$T3aN1GCbZG1PgPx13Lo1teJO7HLHb9JZvpUrhm/XggXcMuqAF.D0C', 2, 'cpZqYdgGX7J19gQqNair03UtPgbVf1Z9cWuNdHmytyQBCiLoS5xNl4uQBYOj', '2023-02-23 15:50:19', '2023-02-23 15:50:19');
INSERT INTO `users` VALUES ('988998d4-6207-4a6b-ac1e-d6e304840685', 'Provider Two', 'providertwo@example.com', '2023-02-23 15:50:19', '$2y$10$ztVJnjMiCvfE3TNOGOs1R.hY3/JY4OI/bBnAaHrKKO1VORUNW4K.2', 2, 'EMXlUhfPXR', '2023-02-23 15:50:19', '2023-02-23 15:50:19');
INSERT INTO `users` VALUES ('988998d4-7ecb-4a5f-9be4-d812c2364a4f', 'Provider Three', 'providerthree@example.com', '2023-02-23 15:50:19', '$2y$10$XI.dkpHrMLLV6t5Q6ozZhOV0CfaBt9HJ9qUwEqD8jufEU.z2Ab74q', 2, 'cwajSRpuXX', '2023-02-23 15:50:20', '2023-02-23 15:50:20');
INSERT INTO `users` VALUES ('988998d5-3ae0-4feb-9e97-ba991b474f45', 'Kamryn Morar', 'okiehn@example.com', '2023-03-05 01:57:08', '$2y$10$FiaalPnAzuFZiKYyNJBgB.PEbgtta9AbaNsHMlFAmDj3Qm.bKGwu6', 3, '17GIxqVRqhqUN4VmtOi653GzjHhv8pbAMPUqjOpY86Wq2M0k9lGqCYuclDav', '2023-02-23 15:50:20', '2023-02-23 15:50:20');
INSERT INTO `users` VALUES ('988998d5-3bb7-44f5-887e-82870132ef30', 'Solon Farrell', 'orin.friesen@example.com', '2023-02-23 15:50:20', '$2y$10$SUCNEdSHoqvlGkXqGEgtieD83M6bD8CSNDb8AEYMhLor8f.42DMKi', 3, 'IxBP9jyeNN3zkPPPYrwurtZx2hTHEduw2fIa3MYrohUkzaCq7CWrK260sxV7', '2023-02-23 15:50:20', '2023-02-23 15:50:20');
INSERT INTO `users` VALUES ('988998d5-3cde-403d-9123-d330f56e6287', 'Dovie Larkin Sr.', 'isai.skiles@example.net', '2023-02-23 15:50:20', '$2y$10$L/Qv4XqRGu6Ul.7UgVmThuQNI7NtMet7oAeiMPfuEZzsRjv9LX.S2', 3, 'mIC14AA526dGQFJgKL8lpBQwsGI150KYqSlfuWZj2fJjMbin50D2rcNN3X8h', '2023-02-23 15:50:20', '2023-02-23 15:50:20');
INSERT INTO `users` VALUES ('988998d5-3e0d-4a94-9867-fc9dd923ac45', 'Jamir Johnston', 'haylie04@example.org', '2023-02-23 15:50:20', '$2y$10$nPgf0qJz3BpAnpyMa5GtfuMrH2uvJrzHVSvEneFlH49cSdskfsx6i', 3, 'OwZDRzFUyh', '2023-02-23 15:50:20', '2023-02-23 15:50:20');
INSERT INTO `users` VALUES ('988998d5-3f1e-4123-8ae6-c4e38586bc8d', 'Dorris Armstrong', 'abergnaum@example.org', '2023-02-23 15:50:20', '$2y$10$io8leLooGnpy3uCKBwT/oOlUzDSvJxX6sO8kxW9t3C.1InFCdFMsu', 3, 'RLgmHpkMo5', '2023-02-23 15:50:20', '2023-02-23 15:50:20');
INSERT INTO `users` VALUES ('988998d5-4049-47ec-97c3-83b43fdb9fe2', 'Garland Brown', 'brooklyn03@example.org', '2023-02-23 15:50:20', '$2y$10$FyTNryhMMMqIFin9sKOkUOV4RQMDD2FvSfFi4ZT2cMaFHkP825moe', 3, 'N0wlasbl79', '2023-02-23 15:50:20', '2023-02-23 15:50:20');
INSERT INTO `users` VALUES ('988998d5-4102-4695-b071-2a89eab48c40', 'Ms. Eugenia Bauch', 'ekuvalis@example.com', '2023-02-23 15:50:20', '$2y$10$2T6td0GbvOHdJPlIsoxJRuSIzo.oYMjGLB/DYRvlNjq/nbceCq8nW', 3, 'iVFZasmbir', '2023-02-23 15:50:20', '2023-02-23 15:50:20');

SET FOREIGN_KEY_CHECKS = 1;

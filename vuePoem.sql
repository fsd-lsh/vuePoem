/*
 Navicat Premium Data Transfer

 Source Server         : Brew_Mysql
 Source Server Type    : MySQL
 Source Server Version : 50737
 Source Host           : localhost:3306
 Source Schema         : vuePoem

 Target Server Type    : MySQL
 Target Server Version : 50737
 File Encoding         : 65001

 Date: 09/05/2022 18:22:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for poem_sys_admin
-- ----------------------------
DROP TABLE IF EXISTS `poem_sys_admin`;
CREATE TABLE `poem_sys_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `roles` text COLLATE utf8mb4_unicode_ci COMMENT '角色',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `ctime` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '创建时间',
  `utime` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统后台用户表';

-- ----------------------------
-- Records of poem_sys_admin
-- ----------------------------
BEGIN;
INSERT INTO `poem_sys_admin` (`id`, `name`, `password`, `roles`, `status`, `ctime`, `utime`) VALUES (1, 'admin', '$2y$10$BcFL0FCFNLmxctqFcdGvIuaa0mEsTb.XbTxEZtX3JRqZNJ8C4.v7i', '1', 1, '1617521386', '1643453252');
INSERT INTO `poem_sys_admin` (`id`, `name`, `password`, `roles`, `status`, `ctime`, `utime`) VALUES (2, 'admin2', '$2y$10$LJWr08iuENPl6WQ.2cNG4OJEqRKgkm66aQKkY6veCl0yb0rvUBm9m', '2', 1, '1630941179', '1632562583');
INSERT INTO `poem_sys_admin` (`id`, `name`, `password`, `roles`, `status`, `ctime`, `utime`) VALUES (3, 'admin3', '$2y$10$SHyUVBSMDMlAOOEzn.wH9.njvBRk2Gj8rA8c.gYZQqbkJSgW8aCZK', '1', 2, '1630941303', '1643455418');
INSERT INTO `poem_sys_admin` (`id`, `name`, `password`, `roles`, `status`, `ctime`, `utime`) VALUES (4, 'admin4', '$2y$10$gRmCPwT/8fcvVUYw0ASF4uXv50WEDW1p0grwvXERqLvguOe76xy8O', '1,2', 1, '1630941403', '1630941403');
INSERT INTO `poem_sys_admin` (`id`, `name`, `password`, `roles`, `status`, `ctime`, `utime`) VALUES (5, 'admin5', '$2y$10$RDoUi730GNqYkQsRsZZrS.tqyc5884TdzXZWdHMrhw7huGTeVsFCS', '3', 1, '1631110973', '1632136167');
COMMIT;

-- ----------------------------
-- Table structure for poem_sys_log
-- ----------------------------
DROP TABLE IF EXISTS `poem_sys_log`;
CREATE TABLE `poem_sys_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '日志操作人 0 - 未登录用户操作 n-已登录用户操作',
  `log` varchar(255) NOT NULL COMMENT '日志正文',
  `path` varchar(255) NOT NULL COMMENT '日志记录PATH',
  `ctime` varchar(11) NOT NULL COMMENT '日志创建时间',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '日志等级 0-普通 1-警告 2-高危',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统日志表';

-- ----------------------------
-- Records of poem_sys_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for poem_sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `poem_sys_menu`;
CREATE TABLE `poem_sys_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `title` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单图标',
  `href` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '链接',
  `target` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0-禁用,1-启用)',
  `lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '锁(0-解锁,1-上锁)',
  `show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '显性(1-显示,0-隐藏)',
  `remark` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '备注信息',
  `ctime` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '创建时间',
  `utime` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '更新时间',
  `dtime` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `title` (`title`) USING BTREE,
  KEY `href` (`href`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统菜单表';

-- ----------------------------
-- Records of poem_sys_menu
-- ----------------------------
BEGIN;
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (1, 0, '业务管理', '', '', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (2, 1, '面板统计', 'fa fa-dashboard', '/dash', '_self', 0, 1, 1, 1, NULL, '1617536467', '1638347608', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (3, 0, '系统管理', '', '', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (4, 3, '菜单设置', 'fa fa-list', '/menu', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (5, 3, '角色设置', 'fa fa-user-secret', '/roles', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (6, 3, '用户管理', 'fa fa-users', '/user', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (7, 3, '系统日志', 'fa fa-bitbucket', '', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (8, 7, '框架日志', '', '/log/fwLog', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (9, 7, '运行日志', '', '/log/sysLog', '_self', 0, 1, 1, 1, NULL, '1617536467', '1617536467', NULL);
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (11, 3, '系统监控', 'fa fa-area-chart', '/system', '_self', 0, 1, 1, 1, NULL, '1632745477', '1638412220', NULL);
COMMIT;

-- ----------------------------
-- Table structure for poem_sys_roles
-- ----------------------------
DROP TABLE IF EXISTS `poem_sys_roles`;
CREATE TABLE `poem_sys_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `menu_ids` text COLLATE utf8mb4_unicode_ci COMMENT '角色权限',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `ctime` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '创建时间',
  `utime` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统角色表';

-- ----------------------------
-- Records of poem_sys_roles
-- ----------------------------
BEGIN;
INSERT INTO `poem_sys_roles` (`id`, `name`, `menu_ids`, `status`, `ctime`, `utime`) VALUES (1, '系统管理员', '1,2,3,4,5,6,7,8,9,10,11', 1, '1620033242', '1620033242');
INSERT INTO `poem_sys_roles` (`id`, `name`, `menu_ids`, `status`, `ctime`, `utime`) VALUES (2, '业务管理员', '1,2,3,5,10', 1, '1627980503', '1627980503');
INSERT INTO `poem_sys_roles` (`id`, `name`, `menu_ids`, `status`, `ctime`, `utime`) VALUES (3, '客户A', '1,2,10', 1, '1631113295', '1631113295');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

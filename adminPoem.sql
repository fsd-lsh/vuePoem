/*
 Navicat Premium Data Transfer

 Source Server         : Docker_Mysql
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : localhost:3305
 Source Schema         : adminPoem

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 03/05/2022 21:51:36
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
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COMMENT='系统日志表';

-- ----------------------------
-- Records of poem_sys_log
-- ----------------------------
BEGIN;
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (1, 1, '用户admin, 登录成功', '/admin', '1631341722', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (2, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1631341824', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (3, 2, '用户admin2, 登录成功', '/admin?api=sign_in', '1631428363', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (4, 2, '用户admin2, 登录成功', '/admin?api=sign_in', '1631429522', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (5, 2, '用户admin2, 登录成功', '/admin?api=sign_in', '1631430303', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (6, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1631430321', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (7, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1631495750', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (8, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1631704953', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (9, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1631793681', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (10, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1631931448', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (11, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632127627', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (12, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632130483', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (13, 1, '用户admin77, 登录成功', '/admin?api=sign_in', '1632130562', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (14, 1, 'admin77, 用户信息修改完成', '/admin/dash/user_info?api=change_userinfo', '1632130676', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (15, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632130711', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (16, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632131119', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (17, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632131279', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (18, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632136120', 2);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (19, 5, '用户admin5, 登录成功', '/admin?api=sign_in', '1632136180', 1);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (20, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632136215', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (21, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632445616', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (22, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632562652', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (23, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632743613', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (24, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632744375', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (25, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1632992859', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (26, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1633241714', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (27, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1633682465', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (28, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1633682568', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (29, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1633683459', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (30, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1637042149', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (31, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1637043629', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (32, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1638346746', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (33, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1638408825', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (34, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1638438124', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (35, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1638493670', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (36, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1641965943', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (37, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642233940', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (38, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642234010', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (39, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642234142', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (40, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642234155', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (41, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642234592', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (42, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642235447', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (43, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642235949', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (44, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642236017', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (45, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642236497', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (46, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642237514', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (47, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642237723', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (48, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642237988', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (49, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642238304', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (50, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642238603', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (51, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642239074', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (52, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642244295', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (53, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642244807', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (54, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642421744', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (55, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642422430', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (56, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642424854', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (57, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642424898', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (58, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642425865', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (59, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642425975', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (60, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642426135', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (61, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642426144', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (62, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642426354', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (63, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642927376', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (64, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642928756', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (65, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642928851', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (66, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642928879', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (67, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642931373', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (68, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642931510', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (69, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642931700', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (70, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642932628', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (71, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642932693', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (72, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642932843', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (73, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642932969', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (74, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642933002', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (75, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642933055', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (76, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642933236', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (77, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642998970', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (78, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1642999131', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (79, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643341584', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (80, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643341600', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (81, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643341635', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (82, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643350505', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (83, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643351144', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (84, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643351440', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (85, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643351504', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (86, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643351692', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (87, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643351782', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (88, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643356983', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (89, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643357123', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (90, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643357164', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (91, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643437903', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (92, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643447346', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (93, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643449554', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (94, 1, 'admin, 用户信息修改完成', '/admin/dash/user_info?api=change_userinfo', '1643453241', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (95, 1, 'admin, 用户信息修改完成', '/admin/dash/user_info?api=change_userinfo', '1643453252', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (96, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643454164', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (97, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643454447', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (98, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643454509', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (99, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643529018', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (100, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643529502', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (101, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643707580', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (102, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643715280', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (103, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1643717562', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (104, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646133956', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (105, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646134090', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (106, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646471580', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (107, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646472032', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (108, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646472180', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (109, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646472445', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (110, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646472514', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (111, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646478532', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (112, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646479417', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (113, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646564487', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (114, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646738208', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (115, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646738263', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (116, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646738293', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (117, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1646745452', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (118, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1647432142', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (119, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1647436083', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (120, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1647759113', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (121, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1647769362', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (122, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1648178043', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (123, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1651495949', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (124, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1651581233', 0);
INSERT INTO `poem_sys_log` (`id`, `admin_id`, `log`, `path`, `ctime`, `level`) VALUES (125, 1, '用户admin, 登录成功', '/admin?api=sign_in', '1651584628', 0);
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
INSERT INTO `poem_sys_menu` (`id`, `pid`, `title`, `icon`, `href`, `target`, `sort`, `status`, `lock`, `show`, `remark`, `ctime`, `utime`, `dtime`) VALUES (10, 1, 'LITE CMS', 'fa fa-chrome', '/cms', '_self', 0, 1, 0, 1, NULL, '1617536467', '1642934301', NULL);
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

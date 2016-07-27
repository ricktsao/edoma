-- --------------------------------------------------------
-- 主機:                           27.147.4.239
-- 服務器版本:                        5.1.73-log - Source distribution
-- 服務器操作系統:                      none-linux-gnueabi
-- HeidiSQL 版本:                  9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 導出 comm 的資料庫結構
DROP DATABASE IF EXISTS `edoma`;
CREATE DATABASE IF NOT EXISTS `edoma` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `edoma`;


-- 導出  表 edoma.album 結構
DROP TABLE IF EXISTS `album`;
CREATE TABLE IF NOT EXISTS `album` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_category_sn` int(11) unsigned NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `img_filename` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_filename2` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `launch` tinyint(4) NOT NULL DEFAULT '1',
  `sort` smallint(6) NOT NULL DEFAULT '500',
  `update_date` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  `title_page_sn` int(11) NOT NULL DEFAULT '0' COMMENT '封面 album_item_sn',
  `start_date` date DEFAULT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  `is_sync` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`),
  KEY `category_sn` (`album_category_sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在導出表  edoma.album 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
/*!40000 ALTER TABLE `album` ENABLE KEYS */;


-- 導出  表 edoma.album_item 結構
DROP TABLE IF EXISTS `album_item`;
CREATE TABLE IF NOT EXISTS `album_item` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `album_sn` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `img_filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '代表圖1',
  `img_filename2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '代表圖2',
  `update_date` datetime NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `launch` tinyint(3) NOT NULL DEFAULT '1',
  `sort` smallint(6) NOT NULL DEFAULT '500',
  `is_del` int(11) NOT NULL DEFAULT '0',
  `is_sync` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`),
  KEY `album_sn` (`album_sn`),
  CONSTRAINT `album_item_ibfk_1` FOREIGN KEY (`album_sn`) REFERENCES `album` (`sn`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在導出表  edoma.album_item 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `album_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `album_item` ENABLE KEYS */;


-- 導出  表 edoma.gas 結構
DROP TABLE IF EXISTS `gas`;
CREATE TABLE IF NOT EXISTS `gas` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `comm_id` varchar(8) NOT NULL DEFAULT '0',
  `server_sn` int(11) DEFAULT NULL,
  `building_id` varchar(60) DEFAULT NULL,
  `building_text` varchar(500) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `month` tinyint(2) DEFAULT NULL,
  `degress` int(11) DEFAULT NULL COMMENT '度數',
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`),
  UNIQUE KEY `building_id_year_month` (`building_id`,`year`,`month`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='瓦斯抄表';

-- 正在導出表  edoma.gas 的資料：0 rows
/*!40000 ALTER TABLE `gas` DISABLE KEYS */;
/*!40000 ALTER TABLE `gas` ENABLE KEYS */;


-- 導出  表 edoma.house_to_rent 結構
DROP TABLE IF EXISTS `house_to_rent`;
CREATE TABLE IF NOT EXISTS `house_to_rent` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `comm_id` varchar(12) NOT NULL COMMENT '社區ID',
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(60) DEFAULT NULL COMMENT '標題',
  `name` varchar(60) DEFAULT NULL COMMENT '聯絡人',
  `phone` varchar(60) DEFAULT NULL COMMENT '聯絡方式',
  `furniture` varchar(60) DEFAULT NULL,
  `electric` varchar(60) DEFAULT NULL,
  `rent_price` int(11) DEFAULT NULL COMMENT '租金',
  `deposit` varchar(50) DEFAULT NULL COMMENT '押金',
  `area_ping` int(11) DEFAULT NULL COMMENT '面積(坪)',
  `room` tinyint(4) DEFAULT NULL COMMENT '幾房',
  `livingroom` tinyint(4) DEFAULT NULL COMMENT '幾廳',
  `bathroom` tinyint(4) DEFAULT NULL COMMENT '幾衛',
  `balcony` tinyint(4) DEFAULT NULL COMMENT '幾陽台',
  `locate_level` tinyint(4) DEFAULT NULL COMMENT '位於幾樓',
  `total_level` tinyint(4) DEFAULT NULL COMMENT '總樓層',
  `usage` varchar(50) DEFAULT NULL COMMENT '法定用途',
  `current` varchar(50) DEFAULT NULL COMMENT '型態/現況',
  `flag_parking` tinyint(1) DEFAULT NULL COMMENT '車位  1.有  0.無',
  `addr` varchar(50) DEFAULT NULL COMMENT '地址',
  `rent_term` varchar(50) DEFAULT NULL COMMENT '最短租期',
  `flag_cooking` tinyint(1) DEFAULT NULL COMMENT '是否可開伙  1.可  0.不可',
  `flag_pet` tinyint(1) DEFAULT NULL COMMENT '是否可養寵物  1.可  0.不可',
  `rent_type` char(1) DEFAULT NULL,
  `house_type` char(1) DEFAULT NULL,
  `tenant_term` varchar(30) DEFAULT NULL COMMENT '身分要求',
  `gender_term` varchar(30) DEFAULT NULL COMMENT '性別要求',
  `start_date` date NOT NULL COMMENT '刊登起始日期',
  `end_date` date NOT NULL COMMENT '刊登截止日期',
  `forever` tinyint(1) NOT NULL,
  `meterial` varchar(30) DEFAULT NULL COMMENT '隔間材料',
  `move_in` varchar(30) DEFAULT NULL COMMENT '可遷入日',
  `living` text COMMENT '生活機能',
  `traffic` varchar(30) DEFAULT NULL COMMENT '附近交通',
  `desc` text,
  `launch` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id` (`sn`,`comm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='租屋資料表';

-- 正在導出表  edoma.house_to_rent 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `house_to_rent` DISABLE KEYS */;
/*!40000 ALTER TABLE `house_to_rent` ENABLE KEYS */;


-- 導出  表 edoma.house_to_rent_photo 結構
DROP TABLE IF EXISTS `house_to_rent_photo`;
CREATE TABLE IF NOT EXISTS `house_to_rent_photo` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序號',
  `comm_id` varchar(12) NOT NULL COMMENT '社區ID',
  `house_to_rent_sn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '租屋序號',
  `filename` varchar(60) NOT NULL COMMENT '檔名',
  `title` varchar(60) NOT NULL COMMENT '說明',
  `updated` datetime NOT NULL,
  `updated_by` varchar(10) NOT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id_house_to_rent_sn_filename` (`sn`,`comm_id`,`house_to_rent_sn`,`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='租屋照片';

-- 正在導出表  edoma.house_to_rent_photo 的資料：0 rows
/*!40000 ALTER TABLE `house_to_rent_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `house_to_rent_photo` ENABLE KEYS */;


-- 導出  表 edoma.house_to_sale 結構
DROP TABLE IF EXISTS `house_to_sale`;
CREATE TABLE IF NOT EXISTS `house_to_sale` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `comm_id` varchar(12) NOT NULL,
  `is_sync` TINYINT(1) NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `sale_type` char(1) DEFAULT NULL,
  `house_type` char(1) DEFAULT NULL,
  `direction` char(1) DEFAULT NULL COMMENT '座向',
  `title` varchar(60) DEFAULT NULL COMMENT '標題',
  `name` varchar(60) DEFAULT NULL COMMENT '聯絡人',
  `phone` varchar(60) DEFAULT NULL COMMENT '聯絡方式',
  `area_desc` varchar(100) DEFAULT NULL COMMENT '坪數說明',
  `total_price` decimal(8,2) DEFAULT NULL COMMENT '總價',
  `unit_price` decimal(5,2) DEFAULT NULL COMMENT '單價',
  `manage_fee` int(11) DEFAULT NULL COMMENT '管理費',
  `area_ping` int(11) DEFAULT NULL COMMENT '面積(坪)',
  `house_age` int(11) DEFAULT NULL,
  `pub_ratio` decimal(4,2) DEFAULT NULL COMMENT '公設比',
  `room` tinyint(4) DEFAULT NULL COMMENT '幾房',
  `livingroom` tinyint(4) DEFAULT NULL COMMENT '幾廳',
  `bathroom` tinyint(4) DEFAULT NULL COMMENT '幾衛',
  `balcony` tinyint(4) DEFAULT NULL COMMENT '幾陽台',
  `locate_level` tinyint(4) DEFAULT NULL COMMENT '位於幾樓',
  `total_level` tinyint(4) DEFAULT NULL COMMENT '總樓層',
  `usage` varchar(50) DEFAULT NULL COMMENT '法定用途',
  `current` varchar(50) DEFAULT NULL COMMENT '型態/現況',
  `flag_rent` tinyint(1) DEFAULT NULL COMMENT '是否帶租約  1.有  0.無',
  `flag_parking` tinyint(1) DEFAULT NULL COMMENT '車位',
  `addr` varchar(50) DEFAULT NULL COMMENT '地址',
  `start_date` date NOT NULL COMMENT '刊登起始日期',
  `end_date` date NOT NULL COMMENT '刊登截止日期',
  `forever` tinyint(1) NOT NULL,
  `decoration` varchar(30) DEFAULT NULL COMMENT '裝潢程度',
  `living` text COMMENT '生活機能',
  `traffic` varchar(30) DEFAULT NULL COMMENT '附近交通',
  `desc` text,
  `launch` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id` (`sn`,`comm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='售屋屋資料表';

-- 正在導出表  edoma.house_to_sale 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `house_to_sale` DISABLE KEYS */;
/*!40000 ALTER TABLE `house_to_sale` ENABLE KEYS */;


-- 導出  表 edoma.house_to_sale_photo 結構
DROP TABLE IF EXISTS `house_to_sale_photo`;
CREATE TABLE IF NOT EXISTS `house_to_sale_photo` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '車位序號',
  `comm_id` varchar(12) NOT NULL COMMENT '社區ID',
  `house_to_sale_sn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '住戶序號',
  `filename` varchar(60) NOT NULL COMMENT '檔名',
  `title` varchar(60) NOT NULL COMMENT '說明',
  `updated` datetime NOT NULL,
  `updated_by` varchar(10) NOT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id_house_to_sale_sn_filename` (`sn`,`comm_id`,`house_to_sale_sn`,`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='售屋照片';

-- 正在導出表  edoma.house_to_sale_photo 的資料：0 rows
/*!40000 ALTER TABLE `house_to_sale_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `house_to_sale_photo` ENABLE KEYS */;


-- 導出  表 edoma.it_sessions 結構
DROP TABLE IF EXISTS `it_sessions`;
CREATE TABLE IF NOT EXISTS `it_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- 導出  表 edoma.mailbox 結構
DROP TABLE IF EXISTS `mailbox`;
CREATE TABLE IF NOT EXISTS `mailbox` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comm_id` varchar(8) DEFAULT NULL,
  `type` char(1) NOT NULL COMMENT '郵件類型  A:掛號信  B:包裹   C: 代收包裹  D:送洗衣物',
  `type_str` varchar(20) NOT NULL,
  `no` varchar(11) NOT NULL COMMENT '代收編號',
  `desc` char(50) NOT NULL COMMENT '郵件敘述說明',
  `booked` datetime NOT NULL COMMENT '登錄時間',
  `booker` int(10) NOT NULL COMMENT '代收警衛sn',
  `booker_id` varchar(10) NOT NULL,
  `user_sn` int(10) unsigned NOT NULL COMMENT '收件人_sn',
  `user_app_id` varchar(10) DEFAULT NULL COMMENT '收件人_app_id',
  `user_building_id` varchar(60) NOT NULL COMMENT '收件人_building_id',
  `user_name` varchar(50) NOT NULL COMMENT '收件人',
  `received` datetime DEFAULT NULL COMMENT '領取時間',
  `receive_user_name` varchar(50) DEFAULT NULL COMMENT '領收人',
  `receive_user_sn` int(11) DEFAULT NULL,
  `receive_agent_sn` int(11) DEFAULT NULL COMMENT '領收警衛sn',
  `is_receive` tinyint(1) DEFAULT '0' COMMENT '是否領取 1:yes,2:no',
  `updated` datetime DEFAULT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='社區郵件';

-- 正在導出表  edoma.mailbox 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `mailbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailbox` ENABLE KEYS */;


-- 導出  表 edoma.parking 結構
DROP TABLE IF EXISTS `parking`;
CREATE TABLE IF NOT EXISTS `parking` (
  `sn` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '車位序號',
  `comm_id` char(8) NOT NULL,
  `parking_id` varchar(10) DEFAULT NULL COMMENT '車位ID (車位所在位置)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '狀態1表正常',
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`),
  UNIQUE KEY `comm_id_parking_id` (`comm_id`,`parking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='社區車位';

-- 正在導出表  edoma.parking 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `parking` DISABLE KEYS */;
/*!40000 ALTER TABLE `parking` ENABLE KEYS */;


-- 導出  表 edoma.repair 結構
DROP TABLE IF EXISTS `repair`;
CREATE TABLE IF NOT EXISTS `repair` (
  `sn` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comm_id` varchar(8) DEFAULT NULL,
  `server_sn` int(11) unsigned DEFAULT NULL,
  `user_sn` int(11) unsigned DEFAULT NULL,
  `user_name` varchar(20) DEFAULT NULL,
  `app_id` varchar(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '1:公共區域,2:住家內部',
  `content` text,
  `reply` text COMMENT '回覆',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0:已報修,1:已讀 /2:勘驗/3:估價/4:完工',
  `is_sync` tinyint(1) unsigned DEFAULT '0',
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='社區環境報修';

-- 正在導出表  edoma.repair 的資料：0 rows
/*!40000 ALTER TABLE `repair` DISABLE KEYS */;
/*!40000 ALTER TABLE `repair` ENABLE KEYS */;


-- 導出  表 edoma.repair_reply 結構
DROP TABLE IF EXISTS `repair_reply`;
CREATE TABLE IF NOT EXISTS `repair_reply` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `repair_sn` int(11) DEFAULT NULL,
  `repair_status` tinyint(1) NOT NULL DEFAULT '1',
  `reply` text,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.repair_reply 的資料：0 rows
/*!40000 ALTER TABLE `repair_reply` DISABLE KEYS */;
/*!40000 ALTER TABLE `repair_reply` ENABLE KEYS */;


-- 導出  表 edoma.suggestion 結構
DROP TABLE IF EXISTS `suggestion`;
CREATE TABLE IF NOT EXISTS `suggestion` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `server_sn` int(11) DEFAULT NULL,
  `comm_id` varchar(10) DEFAULT NULL,
  `app_id` int(10) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text COMMENT '意見內容',
  `reply` text COMMENT '回覆',
  `user_sn` int(11) DEFAULT NULL,
  `to_role` varchar(1) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `is_sync` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='社區意見箱';

-- 正在導出表  edoma.suggestion 的資料：0 rows
/*!40000 ALTER TABLE `suggestion` DISABLE KEYS */;
/*!40000 ALTER TABLE `suggestion` ENABLE KEYS */;


-- 導出  表 edoma.sys_backend_log 結構
DROP TABLE IF EXISTS `sys_backend_log`;
CREATE TABLE IF NOT EXISTS `sys_backend_log` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(6) DEFAULT NULL,
  `ip` varchar(50) NOT NULL,
  `module_id` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `action` tinyint(1) DEFAULT '0' COMMENT '0:使用狀況,1:動作',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `active_date` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_backend_log 的資料：0 rows
/*!40000 ALTER TABLE `sys_backend_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_backend_log` ENABLE KEYS */;


-- 導出  表 edoma.sys_config 結構
DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE IF NOT EXISTS `sys_config` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(50) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `param1` varchar(100) DEFAULT NULL COMMENT '參數1',
  `param2` varchar(100) DEFAULT NULL COMMENT '參數1',
  `desc` varchar(100) DEFAULT NULL,
  `launch` tinyint(1) DEFAULT '1',
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系統配置設定';

-- 正在導出表  edoma.sys_config 的資料：~1 rows (大約)
/*!40000 ALTER TABLE `sys_config` DISABLE KEYS */;
INSERT INTO `sys_config` (`sn`, `id`, `value`, `param1`, `param2`, `desc`, `launch`, `updated`, `created`) VALUES
	(1, 'comm_id', '%s', NULL, NULL, NULL, 1, NULL, NULL);
/*!40000 ALTER TABLE `sys_config` ENABLE KEYS */;


-- 導出  表 edoma.sys_frontend_log_2016 結構
DROP TABLE IF EXISTS `sys_frontend_log_2016`;
CREATE TABLE IF NOT EXISTS `sys_frontend_log_2016` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(40) DEFAULT NULL,
  `user_id` varchar(6) DEFAULT NULL,
  `ip` varchar(50) NOT NULL,
  `module_id` varchar(50) NOT NULL,
  `desc` varchar(500) NOT NULL,
  `action` tinyint(1) DEFAULT '0' COMMENT '0:模組停留狀況,1:動作',
  `stay_time` smallint(6) DEFAULT '0',
  `active_time` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_frontend_log_2016 的資料：0 rows
/*!40000 ALTER TABLE `sys_frontend_log_2016` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_frontend_log_2016` ENABLE KEYS */;


-- 導出  表 edoma.sys_function 結構
DROP TABLE IF EXISTS `sys_function`;
CREATE TABLE IF NOT EXISTS `sys_function` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `is_frontend` tinyint(1) NOT NULL DEFAULT '1' COMMENT '前端:1,後端:0',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_function 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `sys_function` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_function` ENABLE KEYS */;


-- 導出  表 edoma.sys_message_assign 結構
DROP TABLE IF EXISTS `sys_message_assign`;
CREATE TABLE IF NOT EXISTS `sys_message_assign` (
  `sn` bigint(20) NOT NULL AUTO_INCREMENT,
  `from_unit_sn` int(11) NOT NULL COMMENT '單位sn',
  `from_unit_name` varchar(50) NOT NULL COMMENT '單位名稱',
  `from_user_sn` int(11) NOT NULL,
  `to_user_sn` varchar(200) NOT NULL,
  `to_user_id` varchar(1000) NOT NULL,
  `fail_user_id` varchar(1000) DEFAULT NULL,
  `category_id` varchar(10) DEFAULT NULL,
  `sub_category_id` varchar(10) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `sub_title` varchar(50) DEFAULT NULL,
  `brief` varchar(500) DEFAULT NULL,
  `msg_content` mediumtext NOT NULL COMMENT '訊息',
  `meeting_date` datetime DEFAULT NULL COMMENT '會議時間',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='訊息';

-- 正在導出表  edoma.sys_message_assign 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `sys_message_assign` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_message_assign` ENABLE KEYS */;


-- 導出  表 edoma.sys_module 結構
DROP TABLE IF EXISTS `sys_module`;
CREATE TABLE IF NOT EXISTS `sys_module` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_sn` int(10) unsigned DEFAULT NULL,
  `id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:單元模組,2:特殊模組',
  `dir` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'type=1 時才判斷,是否為目錄,0:否(單元模組) 1:是',
  `level` tinyint(1) unsigned DEFAULT '1' COMMENT '單元層級(type=1 時才判斷)',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `icon_text` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '500',
  `launch` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在導出表  community.sys_module 的資料：~51 rows (大約)
/*!40000 ALTER TABLE `sys_module` DISABLE KEYS */;
INSERT INTO `sys_module` (`sn`, `parent_sn`, `id`, `type`, `dir`, `level`, `title`, `icon_text`, `sort`, `launch`) VALUES
	(22, NULL, 'auth-dir', 1, 1, 1, '權限設定', 'fa fa-group ', 1, 1),
	(26, NULL, 'media', 1, 0, 1, '媒體庫', 'fa fa-cloud ', 3, 0),
	(30, 51, 'setting', 1, 0, 2, '關於社區', 'fa fa-wrench', 2, 1),
	(31, NULL, 'homesetting', 1, 1, 1, '首頁設定', 'fa fa-home', 5, 0),
	(32, 22, 'auth', 1, 0, 2, '人員管理', 'fa fa-comment', 5, 1),
	(33, 22, 'authgroup', 1, 0, 2, '群組管理', 'fa fa-briefcase', 6, 1),
	(36, NULL, 'log', 1, 0, 1, '系統記錄', 'fa fa-briefcase', 7, 0),
	(37, NULL, 'bulletin', 1, 0, 1, '管委公告', 'fa fa-comment-o', 5, 1),
	(39, NULL, 'repair', 1, 0, 1, '環境修繕', 'fa fa-gavel ', 10, 1),
	(40, NULL, 'suggestion', 1, 0, 1, '住戶意見箱', 'fa fa-file-text-o', 11, 1),
	(41, NULL, 'data-dir', 1, 1, 1, '社區資料管理', 'icon-coffee', 12, 0),
	(46, NULL, 'news', 1, 0, 1, '社區公告', 'fa fa-newspaper-o', 4, 1),
	(48, NULL, 'voting', 1, 0, 1, '社區議題', 'fa fa-bar-chart ', 9, 1),
	(49, NULL, 'mailbox-dir', 1, 1, 1, '郵件物品管理', 'fa fa-cubes', 8, 1),
	(51, NULL, 'setting-dir', 1, 1, 1, '網站設定', 'fa fa-book', 2, 1),
	(52, 51, 'realtycat', 1, 0, 2, '分類', 'icon-food', 1, 0),
	(53, 51, 'realty', 1, 0, 2, '列表', 'icon-food', 2, 0),
	(54, NULL, 'photo-dir', 1, 1, 1, '社區活動相片', 'fa fa-camera ', 11, 1),
	(55, 54, 'album', 1, 0, 2, '相簿', 'icon-food', 10, 1),
	(57, NULL, 'course', 1, 0, 1, '課程專區', 'fa fa-university ', 7, 1),
	(58, NULL, 'ad', 1, 0, 1, '廣告託撥', 'fa fa-newspaper-o', 12, 1),
	(59, NULL, 'ch_keycode', 1, 0, 1, '磁卡變更', 'fa fa-retweet ', 11, 0),
	(60, NULL, 'gas_dir', 1, 1, 1, '瓦斯報表', 'fa fa-building-o', 13, 1),
	(66, NULL, 'msgcenter-dir', 1, 1, 1, '住戶訊息', 'fa fa-comments-o ', 88, 1),
	(67, 42, 'tv_file', 1, 0, 1, '電視輪播', 'icon-food', 10, 0),
	(68, NULL, 'daily_good', 1, 0, 1, '日行一善', 'fa fa-thumbs-o-up ', 6, 1),
	(70, NULL, 'app_marquee', 1, 0, 2, 'app端首頁橫條資訊', 'icon-food', 999, 1),
	(71, NULL, 'keywords', 1, 1, 1, '片語管理x', 'fa fa-newspaper-o', 10, 0),
	(72, 66, 'can_msg', 1, 0, 2, '罐頭訊息設定', NULL, 500, 1),
	(73, 66, 'msgcenter', 1, 0, 2, '住戶訊息發布', NULL, 500, 1),
	(74, 49, 'mailreg', 1, 0, 2, '郵件登錄', 'fa fa-cubes', 1, 1),
	(75, 49, 'mail_history', 1, 0, 2, '郵件物品記錄', 'fa fa-cubes', 3, 1),
	(76, 84, 'userimport', 1, 0, 2, '批次匯入', 'fa fa-wrench', 2, 1),
	(77, 84, 'parking', 1, 0, 2, '車位查詢', 'icon-coffee', 3, 1),
	(78, NULL, 'house-dir', 1, 1, 1, '房產租售', 'fa fa-newspaper-o', 99, 1),
	(79, 78, 'rent_house', 1, 0, 2, '租屋登記', 'fa fa-comment', 5, 1),
	(80, 78, 'sale_house', 1, 0, 2, '售屋登記', 'fa fa-home', 5, 1),
	(81, 60, 'gas_report', 1, 0, 2, '報表查詢', 'icon-coffee', 1, 1),
	(82, 60, 'gas_company', 1, 0, 2, '瓦斯公司', 'icon-coffee', 2, 1),
	(83, 84, 'user', 1, 0, 2, '住戶管理', 'fa fa-cloud ', 1, 1),
	(84, NULL, 'user-dir', 1, 1, 1, '社區管理', 'fa fa-group', 3, 1),
	(85, 51, 'gen_parking', 1, 0, 2, '車位設定', 'icon-coffee', 3, 1),
	(86, 49, 'mailbox', 1, 0, 2, '郵件領取', 'fa fa-cubes', 2, 1),
	(87, 51, 'watermark', 1, 0, 2, 'pdf浮水印設定', 'icon-coffee', 4, 1),
	(88, 51, 'about', 1, 0, 2, '關於社區', 'icon-coffee', 1, 0),
	(89, 84, 'parkuser', 1, 0, 2, '獨立車位承租人', 'icon-food', 4, 1),
	(90, 84, 'app', 1, 0, 2, 'APP統計', 'icon-food', 5, 0),
	(91, 84, 'collect', 1, 0, 2, '住戶磁扣蒐集', 'icon-food', 6, 1),
	(92, NULL, 'cycle_img', 1, 0, 1, '公告輪播底圖', 'fa fa-newspaper-o', 5, 1),
	(93, NULL, 'marquee', 1, 0, 1, '跑馬燈', 'fa fa-book', 5, 1),
	(94, 51, 'landing', 1, 0, 2, '網站靜止頁面背景', 'icon-coffee', 5, 1);
/*!40000 ALTER TABLE `sys_module` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;


-- 導出  表 edoma.sys_setting 結構
DROP TABLE IF EXISTS `sys_setting`;
CREATE TABLE IF NOT EXISTS `sys_setting` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `meta_keyword` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  `website_title` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在導出表  edoma.sys_setting 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `sys_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_setting` ENABLE KEYS */;


-- 導出  表 edoma.sys_user 結構
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE IF NOT EXISTS `sys_user` (
  `comm_id` char(8) DEFAULT NULL COMMENT '社區序號',
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用戶序號',
  `is_sync` int(10) unsigned NOT NULL DEFAULT '0',
  `building_id` varchar(60) DEFAULT NULL COMMENT '棟別 或 門牌號(A-Z) 或 (9999)  樓層(99)    [住戶識別號_１_１]',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `role` char(1) DEFAULT NULL COMMENT 'I:住戶    M:物業人員   F:富網通',
  `addr_part_01` tinyint(1) unsigned DEFAULT NULL COMMENT '地址門牌',
  `addr_part_02` tinyint(1) unsigned DEFAULT NULL COMMENT '地址門牌樓層',
  `addr` varchar(50) DEFAULT NULL COMMENT '門牌號碼',
  `title` varchar(20) DEFAULT NULL COMMENT '住戶 or 物業人員(ex秘書, 總幹事, 警衛) or 富網通',
  `id` varchar(10) DEFAULT NULL COMMENT '磁扣卡10碼    [住戶識別號_２]',
  `app_id` varchar(50) DEFAULT NULL COMMENT '手機識別碼    [住戶識別號_３]',
  `act_code` char(12) DEFAULT NULL COMMENT '手機開通碼',
  `gender` tinyint(1) unsigned DEFAULT NULL COMMENT '１:男　２:女',
  `account` varchar(10) DEFAULT NULL COMMENT '帳號（物業人員登入）',
  `is_contact` tinyint(1) unsigned DEFAULT '0' COMMENT '緊急聯絡人　0:否,1:是',
  `is_owner` tinyint(1) unsigned DEFAULT '0' COMMENT '所有權人　0:否,1:是',
  `owner_addr` varchar(200) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL COMMENT '密碼（物業人員登入）',
  `is_manager` tinyint(1) unsigned DEFAULT '0' COMMENT '管委',
  `manager_title` varchar(20) DEFAULT NULL,
  `voting_right` tinyint(1) unsigned DEFAULT NULL COMMENT '投票權限　0:否,1:是',
  `gas_right` tinyint(1) DEFAULT NULL COMMENT '瓦斯登記權限　0:否,1:是',
  `email` varchar(100) DEFAULT NULL COMMENT '電子郵件',
  `tel` varchar(15) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `is_chang_pwd` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `forever` tinyint(1) NOT NULL DEFAULT '1',
  `launch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0:未啟用,1:啟用,2:離職',
  `is_default` tinyint(1) unsigned DEFAULT '0' COMMENT '所有權人　0:否,1:是',
  `updated` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `last_login_ip` varchar(30) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `last_login_agent` varchar(100) DEFAULT NULL,
  `use_cnt` int(11) DEFAULT '0' COMMENT 'keycode 使用次數',
  `app_last_login_ip` varchar(30) DEFAULT NULL,
  `app_login_time` datetime DEFAULT NULL,
  `app_last_login_time` datetime DEFAULT NULL,
  `app_use_cnt` int(11) DEFAULT NULL,
  PRIMARY KEY (`sn`),
  UNIQUE KEY `comm_id_building_id` (`comm_id`,`building_id`),
  UNIQUE KEY `comm_id_id` (`comm_id`,`id`),
  KEY `name` (`name`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用戶資料表';

-- 正在導出表  edoma.sys_user 的資料：~1 rows (大約)
/*!40000 ALTER TABLE `sys_user` DISABLE KEYS */;
INSERT INTO `sys_user` (`comm_id`, `sn`, `is_sync`, `building_id`, `name`, `role`, `title`, `id`, `app_id`, `act_code`, `gender`, `account`, `is_contact`, `is_owner`, `owner_addr`, `password`, `is_manager`, `manager_title`, `voting_right`, `gas_right`, `email`, `tel`, `phone`, `addr`, `is_chang_pwd`, `start_date`, `end_date`, `forever`, `launch`, `is_default`, `updated`, `created`, `created_by`, `last_login_ip`, `last_login_time`, `login_time`, `last_login_agent`, `use_cnt`, `app_last_login_ip`, `app_login_time`, `app_last_login_time`, `app_use_cnt`) VALUES
	('%s', 1, 0, NULL, '管理者', 'M', '富網通', NULL, NULL, NULL, 1, 'admin', 0, 0, '', 'c4983d36fb195428c9e8c79dfa9bcb0eb20f74e0', 0, '', 0, 0, NULL, NULL, NULL, NULL, 0, NOW(), NULL, 1, 1, 1, NOW(), NOW(), '', '192.168.1.68', NOW(), NULL, '', 1, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `sys_user` ENABLE KEYS */;


-- 導出  表 edoma.sys_user_group 結構
DROP TABLE IF EXISTS `sys_user_group`;
CREATE TABLE IF NOT EXISTS `sys_user_group` (
  `sn` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `launch` tinyint(1) DEFAULT '1',
  `id` varchar(10) DEFAULT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '500',
  `update_date` datetime DEFAULT NULL,
  `creare_date` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_user_group 的資料：~5 rows (大約)
/*!40000 ALTER TABLE `sys_user_group` DISABLE KEYS */;
INSERT INTO `sys_user_group` (`sn`, `title`, `launch`, `id`, `sort`, `update_date`, `creare_date`) VALUES
	(1, '住戶', 1, 'user', 500, '2015-03-18 16:17:58', NULL),
	(2, '管委會', 1, 'advuser', 400, '2015-03-17 17:08:04', NULL),
	(3, '總幹事', 1, 'secretary', 100, '2015-03-18 10:35:16', NULL),
	(4, '警衛', 1, 'guard', 300, '2015-08-31 09:49:02', NULL),
	(5, '富網通', 1, 'fu', 501, '2015-08-05 11:42:02', NULL);
/*!40000 ALTER TABLE `sys_user_group` ENABLE KEYS */;





-- 導出  表 edoma.web_menu 結構
DROP TABLE IF EXISTS `web_menu`;
CREATE TABLE IF NOT EXISTS `web_menu` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_sn` int(11) unsigned DEFAULT NULL COMMENT '父層序號',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '層級',
  `title` varchar(100) NOT NULL COMMENT '名稱',
  `id` varchar(20) DEFAULT NULL COMMENT 'id',
  `img_filename` varchar(100) DEFAULT NULL COMMENT '圖片',
  `dir` tinyint(1) NOT NULL DEFAULT '0' COMMENT '目錄 (0:否,1:是)',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:網頁單元,1:連結',
  `url` varchar(255) DEFAULT NULL COMMENT 'URL(type=1時使用)',
  `target` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'target',
  `sort` smallint(6) NOT NULL DEFAULT '500' COMMENT '排序',
  `allow_internet` tinyint(1) NOT NULL DEFAULT '0',
  `launch` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:啟用,0:停用',
  `update_date` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`sn`),
  KEY `parent_sn` (`parent_sn`),
  CONSTRAINT `web_menu_ibfk_1` FOREIGN KEY (`parent_sn`) REFERENCES `web_menu` (`sn`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前端單元';

-- 正在導出表  edoma.web_menu 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `web_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_menu` ENABLE KEYS */;


-- 導出  表 edoma.web_menu_banner 結構
DROP TABLE IF EXISTS `web_menu_banner`;
CREATE TABLE IF NOT EXISTS `web_menu_banner` (
  `sn` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `banner_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '檔案名稱',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `forever` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `launch` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort` int(10) unsigned NOT NULL DEFAULT '500',
  `url` text COLLATE utf8_unicode_ci,
  `target` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci,
  `update_date` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在導出表  edoma.web_menu_banner 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `web_menu_banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_menu_banner` ENABLE KEYS */;


-- 導出  表 edoma.web_menu_content 結構
DROP TABLE IF EXISTS `web_menu_content`;
CREATE TABLE IF NOT EXISTS `web_menu_content` (
  `sn` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server_sn` int(11) unsigned DEFAULT NULL,
  `comm_id` char(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '社區序號',
  `id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_sn` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `brief` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `brief2` text COLLATE utf8_unicode_ci,
  `content_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `img_filename` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '圖片名稱',
  `img_filename2` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '圖片名稱2',
  `filename` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '檔案名稱',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `dir` tinyint(1) NOT NULL DEFAULT '0',
  `forever` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `launch` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已刪除  1:yes,0:no',
  `sort` int(10) unsigned NOT NULL DEFAULT '500',
  `url` text COLLATE utf8_unicode_ci,
  `target` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci,
  `hot` tinyint(1) NOT NULL DEFAULT '0',
  `is_sync` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否同步 1:yes,0:no',
  `is_edoma` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否為富網通資料 1:yes,0:no',
  `update_date` datetime NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 正在導出表  edoma.web_menu_content 的資料：0 rows
/*!40000 ALTER TABLE `web_menu_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_menu_content` ENABLE KEYS */;


-- 導出  表 edoma.web_menu_photo 結構
DROP TABLE IF EXISTS `web_menu_photo`;
CREATE TABLE IF NOT EXISTS `web_menu_photo` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序號',
  `content_sn` int(10) NOT NULL,
  `img_filename` varchar(60) NOT NULL COMMENT '檔名',
  `title` varchar(60) NOT NULL COMMENT '說明',
  `updated` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='照片';

-- 正在導出表  edoma.web_menu_photo 的資料：0 rows
/*!40000 ALTER TABLE `web_menu_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `web_menu_photo` ENABLE KEYS */;


-- 導出  表 edoma.sys_user_belong_group 結構
DROP TABLE IF EXISTS `sys_user_belong_group`;
CREATE TABLE IF NOT EXISTS `sys_user_belong_group` (
  `sys_user_sn` int(10) unsigned NOT NULL,
  `sys_user_group_sn` int(10) unsigned NOT NULL DEFAULT '0',
  `launch` tinyint(1) DEFAULT '0',
  `update_date` datetime DEFAULT NULL,
  KEY `FK_sys_admin_belong_group_1` (`sys_user_sn`),
  KEY `FK_sys_admin_belong_group_2` (`sys_user_group_sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_user_belong_group 的資料：~13 rows (大約)
/*!40000 ALTER TABLE `sys_user_belong_group` DISABLE KEYS */;
INSERT INTO `sys_user_belong_group` (`sys_user_sn`, `sys_user_group_sn`, `launch`, `update_date`) VALUES
	(3, 5, 0, '2016-05-31 13:05:51'),
	(6, 2, 1, '2016-05-29 14:02:15'),
	(6, 1, 1, '2016-05-29 14:02:15'),
	(7, 2, 1, '2016-05-29 14:02:36'),
	(7, 1, 1, '2016-05-29 14:02:36'),
	(5, 1, 1, '2016-05-29 14:03:00'),
	(9, 1, 1, '2016-05-29 14:03:43'),
	(10, 1, 1, '2016-05-29 14:04:06'),
	(8, 1, 1, '2016-05-29 14:04:26'),
	(1, 3, 0, '2016-06-06 23:14:08'),
	(2, 3, 1, '2016-05-30 23:39:21'),
	(3, 3, 1, '2016-05-31 13:05:51'),
	(1, 5, 1, '2016-06-06 23:14:27');
/*!40000 ALTER TABLE `sys_user_belong_group` ENABLE KEYS */;


-- 導出  表 edoma.sys_user_file_auth 結構
DROP TABLE IF EXISTS `sys_user_file_auth`;
CREATE TABLE IF NOT EXISTS `sys_user_file_auth` (
  `sn` bigint(20) NOT NULL AUTO_INCREMENT,
  `sys_user_group_sn` int(11) DEFAULT NULL,
  `file_sn` int(11) DEFAULT NULL,
  `launch` tinyint(1) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_user_file_auth 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `sys_user_file_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_user_file_auth` ENABLE KEYS */;


-- 導出  表 edoma.sys_user_func_auth 結構
DROP TABLE IF EXISTS `sys_user_func_auth`;
CREATE TABLE IF NOT EXISTS `sys_user_func_auth` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `sys_user_group_sn` int(11) DEFAULT NULL,
  `sys_function_sn` int(11) DEFAULT NULL,
  `is_frontend` tinyint(1) DEFAULT '1',
  `launch` tinyint(1) DEFAULT '1',
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前端特殊權限';

-- 正在導出表  edoma.sys_user_func_auth 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `sys_user_func_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_user_func_auth` ENABLE KEYS */;



-- 導出  表 edoma.sys_user_group_b_auth 結構
DROP TABLE IF EXISTS `sys_user_group_b_auth`;
CREATE TABLE IF NOT EXISTS `sys_user_group_b_auth` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_group_sn` int(10) unsigned NOT NULL DEFAULT '0',
  `module_sn` int(10) unsigned NOT NULL DEFAULT '0',
  `launch` tinyint(1) DEFAULT '0',
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`),
  KEY `FK_sys_admin_group_authority_1` (`sys_user_group_sn`),
  KEY `FK_sys_admin_group_authority_2` (`module_sn`),
  CONSTRAINT `FK_sys_admin_group_authority_sys_admin_group` FOREIGN KEY (`sys_user_group_sn`) REFERENCES `sys_user_group` (`sn`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_admin_group_authority_sys_module` FOREIGN KEY (`module_sn`) REFERENCES `sys_module` (`sn`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_user_group_b_auth 的資料：~168 rows (大約)
/*!40000 ALTER TABLE `sys_user_group_b_auth` DISABLE KEYS */;
INSERT INTO `sys_user_group_b_auth` (`sn`, `sys_user_group_sn`, `module_sn`, `launch`, `update_date`) VALUES
	(1, 3, 22, 1, '2016-06-03 16:58:51'),
	(4, 3, 32, 1, '2016-06-03 16:58:46'),
	(5, 3, 33, 1, '2016-06-03 16:58:46'),
	(6, 3, 48, 1, '2016-06-03 16:58:49'),
	(7, 3, 67, 0, '2016-04-28 18:50:13'),
	(8, 3, 49, 1, '2016-06-03 16:58:51'),
	(9, 3, 68, 1, '2016-06-03 16:58:49'),
	(10, 3, 26, 0, '2016-04-28 18:50:13'),
	(14, 3, 46, 1, '2016-06-03 16:58:48'),
	(16, 3, 52, 0, '2016-04-25 22:23:40'),
	(17, 3, 53, 0, '2016-04-25 22:23:40'),
	(18, 3, 71, 0, '2016-04-28 18:50:14'),
	(19, 3, 40, 1, '2016-06-03 16:58:49'),
	(20, 3, 55, 1, '2016-06-03 16:58:50'),
	(22, 3, 70, 1, '2016-04-25 22:23:41'),
	(23, 3, 59, 0, '2016-05-03 14:46:11'),
	(24, 3, 58, 1, '2016-06-03 16:58:50'),
	(25, 3, 60, 1, '2016-06-03 16:58:51'),
	(26, 3, 66, 1, '2016-06-03 16:58:51'),
	(27, 3, 37, 1, '2016-06-03 16:58:48'),
	(29, 3, 51, 1, '2016-06-03 16:58:51'),
	(30, 3, 54, 1, '2016-06-03 16:58:51'),
	(31, 2, 32, 1, '2016-05-03 14:46:21'),
	(32, 2, 33, 0, '2016-05-03 14:46:21'),
	(33, 2, 46, 1, '2016-05-03 14:46:22'),
	(34, 2, 48, 1, '2016-05-03 14:46:22'),
	(35, 2, 67, 0, '2016-04-25 22:19:26'),
	(36, 2, 49, 1, '2016-05-03 14:46:24'),
	(37, 2, 68, 1, '2016-05-03 14:46:22'),
	(38, 2, 26, 0, '2016-04-25 22:19:26'),
	(42, 2, 52, 1, '2016-04-25 22:19:28'),
	(43, 2, 53, 1, '2016-04-25 22:19:28'),
	(44, 2, 71, 0, '2016-04-25 22:19:28'),
	(45, 2, 40, 1, '2016-05-03 14:46:23'),
	(46, 2, 55, 1, '2016-05-03 14:46:23'),
	(48, 2, 70, 1, '2016-04-25 22:19:29'),
	(49, 2, 59, 0, '2016-05-03 14:46:23'),
	(50, 2, 58, 0, '2016-05-03 14:46:23'),
	(51, 2, 60, 0, '2016-05-03 14:46:24'),
	(52, 2, 66, 1, '2016-05-03 14:46:24'),
	(53, 2, 22, 1, '2016-05-03 14:46:24'),
	(54, 2, 37, 1, '2016-05-03 14:46:22'),
	(55, 2, 51, 0, '2016-05-03 14:46:24'),
	(56, 2, 54, 1, '2016-05-03 14:46:24'),
	(57, 3, 57, 1, '2016-06-03 16:58:49'),
	(58, 3, 72, 1, '2016-06-03 16:58:50'),
	(59, 3, 73, 1, '2016-06-03 16:58:50'),
	(60, 2, 57, 1, '2016-05-03 14:46:22'),
	(61, 2, 72, 1, '2016-05-03 14:46:24'),
	(62, 2, 73, 1, '2016-05-03 14:46:24'),
	(63, 3, 74, 1, '2016-06-03 16:58:49'),
	(64, 3, 75, 1, '2016-06-03 16:58:49'),
	(65, 2, 76, 1, '2016-05-03 14:46:21'),
	(66, 2, 74, 1, '2016-05-03 14:46:22'),
	(67, 2, 75, 1, '2016-05-03 14:46:22'),
	(68, 3, 76, 1, '2016-06-03 16:58:47'),
	(69, 3, 77, 1, '2016-06-03 16:58:47'),
	(70, 2, 77, 1, '2016-05-03 14:46:21'),
	(71, 2, 79, 1, '2016-05-03 14:46:24'),
	(72, 2, 80, 0, '2016-05-03 14:46:24'),
	(73, 2, 78, 1, '2016-05-03 14:46:24'),
	(74, 3, 79, 1, '2016-06-03 16:58:50'),
	(75, 3, 80, 1, '2016-06-03 16:58:51'),
	(76, 3, 78, 1, '2016-06-03 16:58:51'),
	(77, 3, 81, 1, '2016-06-03 16:58:50'),
	(78, 3, 82, 1, '2016-06-03 16:58:50'),
	(79, 3, 39, 1, '2016-06-03 16:58:49'),
	(80, 2, 39, 1, '2016-05-03 14:46:23'),
	(81, 2, 81, 0, '2016-05-03 14:46:23'),
	(82, 2, 82, 0, '2016-05-03 14:46:24'),
	(83, 3, 83, 1, '2016-06-03 16:58:47'),
	(84, 5, 32, 1, '2016-06-06 23:13:48'),
	(85, 5, 76, 0, '2016-06-06 23:13:49'),
	(86, 5, 77, 0, '2016-06-06 23:13:49'),
	(87, 5, 33, 1, '2016-06-06 23:13:48'),
	(88, 5, 83, 0, '2016-06-06 23:13:49'),
	(89, 5, 48, 0, '2016-06-06 23:13:51'),
	(90, 5, 67, 1, '2016-05-02 23:31:26'),
	(91, 5, 26, 0, '2016-05-01 16:59:31'),
	(92, 5, 46, 0, '2016-06-06 23:13:50'),
	(93, 5, 37, 0, '2016-06-06 23:13:50'),
	(94, 5, 68, 0, '2016-06-06 23:13:51'),
	(95, 5, 57, 0, '2016-06-06 23:13:51'),
	(96, 5, 74, 0, '2016-06-06 23:13:51'),
	(97, 5, 75, 0, '2016-06-06 23:13:51'),
	(98, 5, 39, 0, '2016-06-06 23:13:52'),
	(100, 5, 71, 1, '2016-05-01 02:37:58'),
	(101, 5, 40, 0, '2016-06-06 23:13:52'),
	(102, 5, 55, 0, '2016-06-06 23:13:52'),
	(103, 5, 59, 1, '2016-05-05 19:50:33'),
	(104, 5, 58, 0, '2016-06-06 23:13:52'),
	(105, 5, 81, 0, '2016-06-06 23:13:52'),
	(106, 5, 82, 0, '2016-06-06 23:13:52'),
	(107, 5, 72, 0, '2016-06-06 23:13:53'),
	(108, 5, 73, 0, '2016-06-06 23:13:53'),
	(109, 5, 79, 0, '2016-06-06 23:13:53'),
	(110, 5, 80, 0, '2016-06-06 23:13:53'),
	(111, 5, 22, 1, '2016-06-06 23:13:53'),
	(112, 5, 49, 0, '2016-06-06 23:13:53'),
	(113, 5, 54, 0, '2016-06-06 23:13:53'),
	(114, 5, 60, 0, '2016-06-06 23:13:53'),
	(115, 5, 66, 0, '2016-06-06 23:13:53'),
	(116, 5, 78, 0, '2016-06-06 23:13:53'),
	(117, 5, 30, 1, '2016-06-06 23:13:49'),
	(118, 5, 84, 0, '2016-06-06 23:13:53'),
	(119, 5, 85, 1, '2016-06-06 23:13:49'),
	(120, 5, 51, 1, '2016-06-06 23:13:53'),
	(121, 3, 30, 1, '2016-06-03 16:58:47'),
	(122, 3, 85, 1, '2016-06-03 16:58:47'),
	(123, 3, 86, 1, '2016-06-03 16:58:49'),
	(124, 3, 84, 1, '2016-06-03 16:58:51'),
	(125, 2, 30, 0, '2016-05-03 14:46:21'),
	(126, 2, 85, 0, '2016-05-03 14:46:21'),
	(127, 2, 83, 0, '2016-05-03 14:46:21'),
	(128, 2, 86, 1, '2016-05-03 14:46:22'),
	(129, 2, 84, 1, '2016-05-03 14:46:24'),
	(130, 5, 87, 1, '2016-06-06 23:13:49'),
	(131, 5, 86, 0, '2016-06-06 23:13:51'),
	(132, 5, 88, 1, '2016-06-06 23:13:48'),
	(133, 3, 88, 1, '2016-06-03 16:58:46'),
	(134, 3, 87, 1, '2016-06-03 16:58:47'),
	(135, 3, 89, 1, '2016-06-03 16:58:47'),
	(136, 5, 89, 0, '2016-06-06 23:13:50'),
	(137, 3, 90, 1, '2016-06-03 16:58:48'),
	(138, 4, 32, 0, '2016-05-20 15:57:10'),
	(139, 4, 33, 0, '2016-05-20 15:57:10'),
	(140, 4, 88, 0, '2016-05-20 15:57:10'),
	(141, 4, 30, 0, '2016-05-20 15:57:10'),
	(142, 4, 85, 0, '2016-05-20 15:57:10'),
	(143, 4, 87, 0, '2016-05-20 15:57:11'),
	(144, 4, 83, 0, '2016-05-20 15:57:11'),
	(145, 4, 76, 0, '2016-05-20 15:57:11'),
	(146, 4, 77, 0, '2016-05-20 15:57:11'),
	(147, 4, 89, 0, '2016-05-20 15:57:11'),
	(148, 4, 90, 0, '2016-05-20 15:57:11'),
	(149, 4, 46, 0, '2016-05-20 15:57:11'),
	(150, 4, 37, 0, '2016-05-20 15:57:11'),
	(151, 4, 68, 0, '2016-05-20 15:57:12'),
	(152, 4, 57, 0, '2016-05-20 15:57:12'),
	(153, 4, 74, 1, '2016-05-20 15:57:12'),
	(154, 4, 86, 1, '2016-05-20 15:57:12'),
	(155, 4, 75, 1, '2016-05-20 15:57:12'),
	(156, 4, 48, 0, '2016-05-20 15:57:12'),
	(157, 4, 39, 0, '2016-05-20 15:57:12'),
	(159, 4, 40, 0, '2016-05-20 15:57:13'),
	(160, 4, 55, 0, '2016-05-20 15:57:13'),
	(161, 4, 58, 0, '2016-05-20 15:57:13'),
	(162, 4, 81, 0, '2016-05-20 15:57:13'),
	(163, 4, 82, 0, '2016-05-20 15:57:13'),
	(164, 4, 72, 0, '2016-05-20 15:57:13'),
	(165, 4, 73, 0, '2016-05-20 15:57:13'),
	(166, 4, 79, 0, '2016-05-20 15:57:13'),
	(167, 4, 80, 0, '2016-05-20 15:57:14'),
	(168, 4, 22, 0, '2016-05-20 15:57:14'),
	(169, 4, 51, 0, '2016-05-20 15:57:14'),
	(170, 4, 84, 0, '2016-05-20 15:57:14'),
	(171, 4, 49, 1, '2016-05-20 15:57:14'),
	(172, 4, 54, 0, '2016-05-20 15:57:14'),
	(173, 4, 60, 0, '2016-05-20 15:57:14'),
	(174, 4, 66, 0, '2016-05-20 15:57:14'),
	(175, 4, 78, 0, '2016-05-20 15:57:14'),
	(176, 3, 91, 1, '2016-06-03 16:58:48'),
	(177, 3, 92, 1, '2016-06-03 16:58:48'),
	(178, 3, 93, 1, '2016-06-03 16:58:47'),
	(179, 5, 93, 0, '2016-06-06 23:13:49'),
	(180, 5, 90, 0, '2016-06-06 23:13:50'),
	(181, 5, 91, 0, '2016-06-06 23:13:50'),
	(182, 5, 92, 0, '2016-06-06 23:13:51');
/*!40000 ALTER TABLE `sys_user_group_b_auth` ENABLE KEYS */;








-- 導出  表 edoma.sys_user_group_f_auth 結構
DROP TABLE IF EXISTS `sys_user_group_f_auth`;
CREATE TABLE IF NOT EXISTS `sys_user_group_f_auth` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sys_user_group_sn` int(10) unsigned NOT NULL DEFAULT '0',
  `web_menu_sn` int(10) unsigned NOT NULL DEFAULT '0',
  `launch` tinyint(1) DEFAULT '0',
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`),
  KEY `FK_sys_user_group_f_auth_sys_user_group` (`sys_user_group_sn`),
  KEY `FK_sys_user_group_f_auth_web_menu` (`web_menu_sn`),
  CONSTRAINT `FK_sys_user_group_f_auth_sys_user_group` FOREIGN KEY (`sys_user_group_sn`) REFERENCES `sys_user_group` (`sn`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_user_group_f_auth_web_menu` FOREIGN KEY (`web_menu_sn`) REFERENCES `web_menu` (`sn`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='群組對應前端權限關係表';

-- 正在導出表  edoma.sys_user_group_f_auth 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `sys_user_group_f_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_user_group_f_auth` ENABLE KEYS */;


-- 導出  表 edoma.user_message 結構
DROP TABLE IF EXISTS `user_message`;
CREATE TABLE `user_message` (
	`sn` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`edit_user_sn` BIGINT(20) NULL DEFAULT NULL COMMENT '訊息編輯者',
	`to_user_sn` INT(11) NOT NULL,
	`to_user_app_id` VARCHAR(10) NULL DEFAULT NULL,
	`to_user_name` VARCHAR(10) NULL DEFAULT NULL,
	`title` VARCHAR(50) NULL DEFAULT NULL,
	`msg_content` TEXT NOT NULL COMMENT '訊息',
	`is_sync` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否已同步至server',
	`post_date` DATETIME NOT NULL,
	`updated` DATETIME NOT NULL,
	`created` DATETIME NOT NULL,
	PRIMARY KEY (`sn`)
)
COMMENT='訊息'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;


-- 正在導出表  edoma.user_message 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `user_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_message` ENABLE KEYS */;


-- 導出  表 edoma.user_message_assign 結構
DROP TABLE IF EXISTS `user_message_assign`;
CREATE TABLE `user_message_assign` (
	`sn` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`edit_user_sn` BIGINT(20) NULL DEFAULT NULL COMMENT '訊息編輯者',
	`to_user_sn` VARCHAR(500) NOT NULL,
	`to_user_name` VARCHAR(500) NULL DEFAULT NULL,
	`to_user_count` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	`title` VARCHAR(50) NULL DEFAULT NULL,
	`msg_content` TEXT NOT NULL COMMENT '訊息',
	`is_sync` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否已同步至server',
	`post_date` DATETIME NOT NULL,
	`updated` DATETIME NOT NULL,
	`created` DATETIME NOT NULL,
	PRIMARY KEY (`sn`)
)
COMMENT='訊息'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;


-- 正在導出表  edoma.user_message_assign 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `user_message_assign` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_message_assign` ENABLE KEYS */;


-- 導出  表 edoma.user_parking 結構
DROP TABLE IF EXISTS `user_parking`;
CREATE TABLE IF NOT EXISTS `user_parking` (
  `comm_id` char(8) NOT NULL,
  `parking_sn` int(11) NOT NULL COMMENT '車位序號',
  `user_sn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '住戶序號',
  `person_sn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '若不為0, 表示該車位是獨立購買的，不屬於社區內住戶所有',
  `user_id` varchar(20) NOT NULL COMMENT '住戶ID',
  `car_number` varchar(60) NOT NULL COMMENT '車號',
  `updated` datetime NOT NULL,
  `updated_by` varchar(10) NOT NULL,
  PRIMARY KEY (`comm_id`,`parking_sn`),
  UNIQUE KEY `comm_id_parking_id` (`comm_id`,`parking_sn`,`user_sn`,`person_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='住戶、車位關聯表\r\n＊一個人可以有多個車位\r\n＊一個車位可以登記多輛車子車牌';

-- 正在導出表  edoma.user_parking 的資料：0 rows
/*!40000 ALTER TABLE `user_parking` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_parking` ENABLE KEYS */;


-- 導出  表 edoma.voting 結構
DROP TABLE IF EXISTS `voting`;
CREATE TABLE IF NOT EXISTS `voting` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `allow_anony` tinyint(1) unsigned NOT NULL COMMENT '0 表記名投票   1 表匿名投票 ',
  `is_multiple` tinyint(1) NOT NULL COMMENT '0 表單選   1 表複選',
  `is_del` int(1) NOT NULL DEFAULT '0' COMMENT '是否刪除 1Y/0N',
  `is_sync` int(1) NOT NULL DEFAULT '0' COMMENT '是否同步 1Y/0N',
  `user_sn` int(11) NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投票主表';

-- 正在導出表  edoma.voting 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `voting` DISABLE KEYS */;
/*!40000 ALTER TABLE `voting` ENABLE KEYS */;


-- 導出  表 edoma.voting_option 結構
DROP TABLE IF EXISTS `voting_option`;
CREATE TABLE IF NOT EXISTS `voting_option` (
  `sn` int(10) NOT NULL AUTO_INCREMENT,
  `voting_sn` int(10) NOT NULL,
  `text` varchar(50) NOT NULL,
  `is_del` int(1) NOT NULL DEFAULT '0' COMMENT '是否刪除 1Y/0N',
  `is_sync` int(1) NOT NULL DEFAULT '0' COMMENT '是否同步 1Y/0N',
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投票選項';

-- 正在導出表  edoma.voting_option 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `voting_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `voting_option` ENABLE KEYS */;


-- 導出  表 edoma.voting_record 結構
DROP TABLE IF EXISTS `voting_record`;
CREATE TABLE IF NOT EXISTS `voting_record` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `voting_sn` int(10) NOT NULL,
  `option_sn` int(10) NOT NULL,
  `user_sn` int(10) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `created` datetime NOT NULL,
  `is_sync` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投票記錄';

-- 正在導出表  edoma.voting_record 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `voting_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `voting_record` ENABLE KEYS */;


-- 導出  表 edoma.web_setting 結構
DROP TABLE IF EXISTS `web_setting`;
CREATE TABLE IF NOT EXISTS `web_setting` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(1000) NOT NULL,
  `memo` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `sort` smallint(3) NOT NULL DEFAULT '500',
  `launch` tinyint(1) NOT NULL DEFAULT '1',
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='前端單元';

-- 正在導出表  community.web_setting 的資料：22 rows
/*!40000 ALTER TABLE `web_setting` DISABLE KEYS */;
INSERT INTO `web_setting` (`sn`, `title`, `key`, `value`, `memo`, `type`, `sort`, `launch`, `update_date`) VALUES
	(1, '網站名稱', 'website_title', 'E-DOMA e化你家', '', 'text', 10, 1, '2016-07-18 00:32:04'),
	(19, '公告輪播停留秒數', 'bulletin_cycle_sec', '1', '', 'text', 39, 1, '2016-07-18 00:32:04'),
	(3, '社區名稱', 'comm_name', '金雅苑', '', 'text', 30, 1, '2016-07-18 00:32:04'),
	(12, '社區簡介', 'comm_desc', '信義特區佔地3000坪', '', 'textarea', 36, 1, '2016-07-18 00:32:04'),
	(6, '管委職稱', 'manager_title', '主委,財委,委員,財務委員,水電委員,監察委員', '請以逗點(,)隔開，例如：主委,財委,委員', 'text', 40, 1, '2016-07-18 00:32:04'),
	(7, '戶別識別1_名稱', 'building_part_01', '棟別', '請輸入『棟別』或『門牌號碼』，若戶別為英數字，可設定棟別可輸入英數字 ，門牌號可輸入數字', 'text', 53, 1, '2016-06-11 03:03:56'),
	(8, '戶別識別1_內容', 'building_part_01_value', 'A,B,C', '請以逗點(,)隔開，例如：A,B,C', 'text', 60, 1, '2016-06-11 03:03:56'),
	(9, '戶別識別2_名稱', 'building_part_02', '樓層', '', 'text', 70, 1, '2016-06-11 03:03:56'),
	(10, '戶別識別2_內容', 'building_part_02_value', '1,2,3,4,5,6,7,8,9,10,11,12', '請以逗點(,)隔開，例如：1,2,3', 'text', 80, 1, '2016-06-11 03:03:56'),
	(11, '戶別識別3_名稱', 'building_part_03', '住戶人數編號', '', 'text', 90, 1, '2016-06-11 03:03:56'),
	(4, 'Google Search ID', 'google_search_id', '017154571463157724076:p-zsbzzctk4', '', 'text', 9999, 0, '2014-10-30 14:25:38'),
	(5, 'Google analytics', 'google_analytics', '  (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){', '', 'text', 9999, 0, '2014-10-30 14:25:38'),
	(13, '車位識別1_名稱', 'parking_part_01', '停車位棟別', '', 'text', 100, 1, '2016-06-11 03:03:56'),
	(14, '車位識別1_內容', 'parking_part_01_value', 'A,B,C', '請以逗點(,)隔開，例如：A,B,C', 'text', 110, 1, '2016-06-11 03:03:56'),
	(15, '車位識別2_名稱', 'parking_part_02', '停車位樓層', '', 'text', 120, 1, '2016-06-11 03:03:56'),
	(16, '車位識別2_內容', 'parking_part_02_value', '1F,B1,B2,B3', '請以逗點(,)隔開，例如：B1,B2,B3', 'text', 130, 1, '2016-06-11 03:03:56'),
	(17, '車位識別3_名稱', 'parking_part_03', '車位編號', '', 'text', 140, 1, '2016-06-11 03:03:56'),
	(18, '郵件類型', 'mail_box_type', '掛號信,包裹,代收包裹,送洗衣物', '請以逗點(,)隔開，例如：掛號信,包裹,代收包裹 ，若欲增加新的類型，請勿更改原先順序', 'text', 38, 1, '2016-07-18 00:32:04'),
	(20, '社區電話', 'comm_tel', '02-88511988', '', 'text', 32, 1, '2016-07-18 00:32:04'),
	(21, '社區地址', 'comm_addr', '台北市信義區基隆路二段100號', '', 'text', 35, 1, '2016-07-18 00:32:04'),
	(22, '地址識別_門牌號碼', 'addr_part_01', '環中路100號,環中路102號,環中路104巷1號,環中路104巷2號,中武路1號,中武路3號', '請以逗點(,)隔開', 'text', 50, 1, '2016-07-18 00:32:04'),
	(23, '地址識別_樓層', 'addr_part_02', '一,二,三,四,五,六,七,八,九,十,十一,十二', '請以逗點(,)隔開，例如：1,2,3', 'text', 52, 1, '2016-07-18 00:32:04');
/*!40000 ALTER TABLE `web_setting` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 導出  表 community.web_setting_photo 結構
DROP TABLE IF EXISTS `web_setting_photo`;
CREATE TABLE IF NOT EXISTS `web_setting_photo` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序號',
  `img_filename` varchar(60) NOT NULL COMMENT '檔名',
  `title` varchar(60) NOT NULL COMMENT '說明',
  `updated` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='照片';

-- 正在導出表  community.web_setting_photo 的資料：1 rows
/*!40000 ALTER TABLE `web_setting_photo` DISABLE KEYS */;
INSERT INTO `web_setting_photo` (`sn`, `img_filename`, `title`, `updated`, `updated_by`, `created`) VALUES
	(8, '20160726113125_651303.jpg', '', '2016-07-26 11:31:25', '曹小賢', '2016-07-26 11:31:25');
/*!40000 ALTER TABLE `web_setting_photo` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

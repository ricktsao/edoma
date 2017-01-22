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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 資料導出被取消選擇。
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='瓦斯抄表';

-- 資料導出被取消選擇。
-- 導出  表 edoma.house_to_rent 結構
DROP TABLE IF EXISTS `house_to_rent`;
CREATE TABLE IF NOT EXISTS `house_to_rent` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `comm_id` varchar(12) NOT NULL COMMENT '社區ID',
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `server_sn` int(8) DEFAULT NULL,
  `is_edoma` tinyint(1) NOT NULL DEFAULT '0',
  `edoma_sn` int(10) DEFAULT NULL,
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
  `end_date` date DEFAULT NULL COMMENT '刊登截止日期',
  `forever` tinyint(1) NOT NULL,
  `meterial` varchar(30) DEFAULT NULL COMMENT '隔間材料',
  `move_in` varchar(30) DEFAULT NULL COMMENT '可遷入日',
  `living` text COMMENT '生活機能',
  `traffic` varchar(30) DEFAULT NULL COMMENT '附近交通',
  `desc` text,
  `is_post` tinyint(1) DEFAULT '0' COMMENT '1: 發布至大平台聯合刊登',
  `launch` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id` (`sn`,`comm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='租屋資料表';

-- 資料導出被取消選擇。
-- 導出  表 edoma.house_to_rent_photo 結構
DROP TABLE IF EXISTS `house_to_rent_photo`;
CREATE TABLE IF NOT EXISTS `house_to_rent_photo` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序號',
  `comm_id` varchar(12) NOT NULL COMMENT '社區ID',
  `house_to_rent_sn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '房屋序號',
  `filename` varchar(60) NOT NULL COMMENT '檔名',
  `edoma_house_to_rent_sn` int(11) DEFAULT NULL COMMENT 'Edoma 房屋序號',
  `title` varchar(60) NOT NULL COMMENT '說明',
  `updated` datetime NOT NULL,
  `updated_by` varchar(20) NOT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id_house_to_rent_sn_filename` (`sn`,`comm_id`,`house_to_rent_sn`,`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='租屋照片';

-- 資料導出被取消選擇。
-- 導出  表 edoma.house_to_sale 結構
DROP TABLE IF EXISTS `house_to_sale`;
CREATE TABLE IF NOT EXISTS `house_to_sale` (
  `sn` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `comm_id` varchar(12) NOT NULL,
  `is_sync` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `server_sn` int(8) unsigned DEFAULT NULL,
  `is_edoma` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `edoma_sn` int(10) unsigned DEFAULT NULL,
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
  `end_date` date DEFAULT NULL COMMENT '刊登截止日期',
  `forever` tinyint(1) NOT NULL,
  `decoration` varchar(30) DEFAULT NULL COMMENT '裝潢程度',
  `living` text COMMENT '生活機能',
  `traffic` varchar(30) DEFAULT NULL COMMENT '附近交通',
  `desc` text,
  `is_post` tinyint(1) DEFAULT '0' COMMENT '1: 發布至大平台聯合刊登',
  `launch` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id` (`sn`,`comm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='售屋屋資料表';

-- 資料導出被取消選擇。
-- 導出  表 edoma.house_to_sale_photo 結構
DROP TABLE IF EXISTS `house_to_sale_photo`;
CREATE TABLE IF NOT EXISTS `house_to_sale_photo` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '車位序號',
  `comm_id` varchar(12) NOT NULL COMMENT '社區ID',
  `house_to_sale_sn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '房屋序號',
  `filename` varchar(60) NOT NULL COMMENT '檔名',
  `edoma_house_to_sale_sn` int(11) NOT NULL COMMENT 'Edoma 房屋序號',
  `title` varchar(60) NOT NULL COMMENT '說明',
  `updated` datetime NOT NULL,
  `updated_by` varchar(20) NOT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`),
  KEY `sn_comm_id_house_to_sale_sn_filename` (`sn`,`comm_id`,`house_to_sale_sn`,`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='售屋照片';


-- 資料導出被取消選擇。
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


-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='社區郵件';

-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='社區車位';

-- 資料導出被取消選擇。
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='社區環境報修';

-- 資料導出被取消選擇。
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- 資料導出被取消選擇。
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='社區意見箱';


-- 資料導出被取消選擇。
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

-- 資料導出被取消選擇。
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系統配置設定';

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- 導出  表 edoma.sys_frontend_log_2016 結構
DROP TABLE IF EXISTS `sys_frontend_log_2017`;
CREATE TABLE IF NOT EXISTS `sys_frontend_log_2017` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- 資料導出被取消選擇。
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


-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='訊息';

-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 正在導出表  edoma.sys_module 的資料：~52 rows (大約)
/*!40000 ALTER TABLE `sys_module` DISABLE KEYS */;
INSERT INTO `sys_module` (`sn`, `parent_sn`, `id`, `type`, `dir`, `level`, `title`, `icon_text`, `sort`, `launch`) VALUES
    (22, NULL, 'auth-dir', 1, 1, 1, '網站設定', 'fa fa-group ', 100, 1),
    (26, NULL, 'media', 1, 0, 1, '媒體庫', 'fa fa-cloud ', 3, 0),
    (30, 22, 'setting', 1, 0, 2, '關於社區', 'fa fa-wrench', 4, 1),
    (31, NULL, 'homesetting', 1, 1, 1, '首頁設定', 'fa fa-home', 5, 0),
    (32, 22, 'auth', 1, 0, 2, '人員管理', 'fa fa-comment', 1, 1),
    (33, 22, 'authgroup', 1, 0, 2, '群組管理', 'fa fa-briefcase', 2, 1),
    (36, NULL, 'log', 1, 0, 1, '系統記錄', 'fa fa-briefcase', 7, 0),
    (37, NULL, 'bulletin', 1, 0, 1, '管委公告', 'fa fa-comment-o', 5, 1),
    (39, NULL, 'repair', 1, 0, 1, '環境修繕', 'fa fa-gavel ', 10, 1),
    (40, NULL, 'suggestion', 1, 0, 1, '住戶意見箱', 'fa fa-file-text-o', 11, 1),
    (41, NULL, 'data-dir', 1, 1, 1, '社區資料管理', 'icon-coffee', 12, 0),
    (46, NULL, 'news', 1, 0, 1, '社區公告', 'fa fa-newspaper-o', 4, 1),
    (48, NULL, 'voting', 1, 0, 1, '社區議題', 'fa fa-bar-chart ', 9, 1),
    (49, NULL, 'mailbox-dir', 1, 1, 0, '郵件物品管理', 'fa fa-cubes', 8, 1),
    (51, NULL, 'setting-dir', 1, 1, 1, '網站設定_old', 'fa fa-book', 2, 0),
    (52, 51, 'realtycat', 1, 0, 2, '分類', 'icon-food', 1, 0),
    (53, 51, 'realty', 1, 0, 2, '列表', 'icon-food', 2, 0),
    (54, NULL, 'photo-dir', 1, 1, 1, '社區活動相片', 'fa fa-camera ', 11, 1),
    (55, 54, 'album', 1, 0, 2, '相簿', 'icon-food', 10, 1),
    (57, NULL, 'course', 1, 0, 1, '課程專區', 'fa fa-university ', 7, 1),
    (58, NULL, 'ad', 1, 0, 1, '社區優惠', 'fa fa-newspaper-o', 12, 1),
    (59, NULL, 'ch_keycode', 1, 0, 1, '磁卡變更', 'fa fa-retweet ', 11, 0),
    (60, NULL, 'gas_dir', 1, 1, 1, '瓦斯報表', 'fa fa-building-o', 13, 1),
    (66, NULL, 'msgcenter-dir', 1, 1, 1, '住戶訊息', 'fa fa-comments-o ', 88, 1),
    (67, 42, 'tv_file', 1, 0, 1, '電視輪播', 'icon-food', 10, 0),
    (68, NULL, 'daily_good', 1, 0, 1, '日行一善', 'fa fa-thumbs-o-up ', 6, 1),
    (70, NULL, 'app_marquee', 1, 0, 2, 'app端首頁橫條資訊', 'icon-food', 999, 1),
    (71, NULL, 'keywords', 1, 1, 1, '片語管理x', 'fa fa-newspaper-o', 10, 0),
    (72, 66, 'can_msg', 1, 0, 2, '罐頭訊息設定', NULL, 500, 0),
    (73, 66, 'msgcenter', 1, 0, 2, '住戶訊息發佈', NULL, 500, 1),
    (74, 49, 'mailreg', 1, 0, 2, '郵件登錄', 'fa fa-cubes', 1, 1),
    (75, 49, 'mail_history', 1, 0, 2, '郵件物品記錄', 'fa fa-cubes', 3, 1),
    (76, 22, 'userimport', 1, 0, 2, '住戶資料批次匯入', 'fa fa-wrench', 8, 1),
    (77, 84, 'parking', 1, 0, 2, '車位查詢', 'icon-coffee', 3, 1),
    (78, NULL, 'house-dir', 1, 1, 1, '房產租售', 'fa fa-newspaper-o', 99, 1),
    (79, 78, 'rent_house', 1, 0, 2, '租屋登記', 'fa fa-comment', 5, 1),
    (80, 78, 'sale_house', 1, 0, 2, '售屋登記', 'fa fa-home', 5, 1),
    (81, 60, 'gas_report', 1, 0, 2, '報表查詢', 'icon-coffee', 1, 1),
    (82, 60, 'gas_company', 1, 0, 2, '瓦斯公司', 'icon-coffee', 2, 1),
    (83, 84, 'user', 1, 0, 2, '住戶管理', 'fa fa-cloud ', 1, 1),
    (84, NULL, 'user-dir', 1, 1, 1, '社區管理', 'fa fa-group', 3, 1),
    (85, 22, 'gen_parking', 1, 0, 2, '車位設定', 'icon-coffee', 5, 1),
    (86, 49, 'mailbox', 1, 0, 2, '郵件領取', 'fa fa-cubes', 2, 1),
    (87, 22, 'watermark', 1, 0, 2, 'pdf浮水印設定', 'icon-coffee', 4, 1),
    (88, 22, 'about', 1, 0, 2, '關於社區', 'icon-coffee', 3, 0),
    (89, 84, 'parkuser', 1, 0, 2, '獨立車位承租人', 'icon-food', 4, 1),
    (90, 84, 'app', 1, 0, 2, 'APP統計', 'icon-food', 5, 0),
    (91, 84, 'collect', 1, 0, 2, '住戶磁扣蒐集', 'icon-food', 6, 1),
    (92, NULL, 'cycle_img', 1, 0, 1, '公告輪播底圖', 'fa fa-newspaper-o', 5, 1),
    (93, NULL, 'marquee', 1, 0, 1, '跑馬燈', 'fa fa-book', 5, 1),
    (94, 22, 'landing', 1, 0, 2, '網站鎖定畫面背景', 'icon-coffee', 6, 1),
    (95, NULL, 'feedback', 1, 0, 1, '富網通意見箱', 'fa fa-group ', 99, 1);
/*!40000 ALTER TABLE `sys_module` ENABLE KEYS */;


-- 資料導出被取消選擇。
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

-- 資料導出被取消選擇。
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
  `tenant_flag` tinyint(1) unsigned DEFAULT '0' COMMENT '是否為租屋房客　0:否,1:是',
  `suggest_flag` tinyint(1) unsigned DEFAULT '1' COMMENT '意見箱權限　0:否,1:是',
  `living_here` tinyint(1) DEFAULT '1' COMMENT '是否為住戶(目前是否住在該戶)',
  `del` tinyint(1) DEFAULT '0' COMMENT '是否已刪除  1:yes,0:no',
  `is_contact` tinyint(1) unsigned DEFAULT '0' COMMENT '緊急聯絡人　0:否,1:是',
  `is_owner` tinyint(1) unsigned DEFAULT '0' COMMENT '所有權人　0:否,1:是',
  `owner_addr` varchar(200) DEFAULT NULL COMMENT '所有權人地址 或 緊急聯絡人地址',
  `account` varchar(10) DEFAULT NULL COMMENT '帳號（物業人員登入）',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用戶資料表';

-- 正在導出表  edoma.sys_user 的資料：~1 rows (大約)
/*!40000 ALTER TABLE `sys_user` DISABLE KEYS */;
INSERT INTO `sys_user` (`comm_id`, `sn`, `is_sync`, `building_id`, `name`, `role`, `addr_part_01`, `addr_part_02`, `addr`, `title`, `id`, `app_id`, `act_code`, `gender`, `tenant_flag`, `suggest_flag`, `living_here`, `del`, `is_contact`, `is_owner`, `owner_addr`, `account`, `password`, `is_manager`, `manager_title`, `voting_right`, `gas_right`, `email`, `tel`, `phone`, `is_chang_pwd`, `start_date`, `end_date`, `forever`, `launch`, `is_default`, `updated`, `created`, `created_by`, `last_login_ip`, `last_login_time`, `login_time`, `last_login_agent`, `use_cnt`, `app_last_login_ip`, `app_login_time`, `app_last_login_time`, `app_use_cnt`) VALUES
    ('%s', 1, 0, NULL, '管理者', 'M', NULL, NULL, NULL, '總幹事', NULL, 'ccc', NULL, 2, 0, 1, NULL, 0, 0, 0, '', 'admin', 'c4983d36fb195428c9e8c79dfa9bcb0eb20f74e0', 0, '', 0, 0, 'inn.tang@chupei.com.tw', NULL, '0928886052', 1, NOW(), NULL, 1, 1, 0, NOW(), NOW(), '', '192.168.1.68', NOW(), NULL, '[OS] Unknown Windows OS\n[Agent] Chrome 49.0.2623.87', 1, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `sys_user` ENABLE KEYS */;


-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_user_group 的資料：~5 rows (大約)
/*!40000 ALTER TABLE `sys_user_group` DISABLE KEYS */;
INSERT INTO `sys_user_group` (`sn`, `title`, `launch`, `id`, `sort`, `update_date`, `creare_date`) VALUES
    (1, '住戶', 1, 'user', 500, '2015-03-18 16:17:58', NULL),
    (2, '管委會', 1, 'advuser', 400, '2015-03-17 17:08:04', NULL),
    (3, '總幹事', 1, 'secretary', 100, '2015-03-18 10:35:16', NULL),
    (4, '警衛', 1, 'guard', 300, '2015-08-31 09:49:02', NULL),
    (5, '富網通', 1, 'fu', 501, '2015-08-05 11:42:02', NULL);
/*!40000 ALTER TABLE `sys_user_group` ENABLE KEYS */;





-- 資料導出被取消選擇。
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

-- 資料導出被取消選擇。
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

-- 資料導出被取消選擇。
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 資料導出被取消選擇。
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='照片';


-- 資料導出被取消選擇。
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
INSERT INTO `sys_user_belong_group` (`sys_user_sn`, `sys_user_group_sn`, `launch`, `update_date`)
VALUES (1, 5, 1, NOW());
/*!40000 ALTER TABLE `sys_user_belong_group` ENABLE KEYS */;


-- 資料導出被取消選擇。
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

-- 資料導出被取消選擇。
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



-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- 正在導出表  edoma.sys_user_group_b_auth 的資料：~171 rows (大約)
/*!40000 ALTER TABLE `sys_user_group_b_auth` DISABLE KEYS */;

INSERT INTO `sys_user_group_b_auth`
(`sn`, `sys_user_group_sn`, `module_sn`, `launch`, `update_date`)
VALUES
(NULL, 3, 32, 1, NOW()),
(NULL, 3, 33, 1, NOW()),
(NULL, 3, 30, 1, NOW()),
(NULL, 3, 85, 1, NOW()),
(NULL, 3, 87, 1, NOW()),
(NULL, 3, 94, 1, NOW()),
(NULL, 3, 83, 1, NOW()),
(NULL, 3, 76, 1, NOW()),
(NULL, 3, 77, 1, NOW()),
(NULL, 3, 89, 1, NOW()),
(NULL, 3, 91, 1, NOW()),
(NULL, 3, 46, 1, NOW()),
(NULL, 3, 37, 1, NOW()),
(NULL, 3, 92, 1, NOW()),
(NULL, 3, 93, 1, NOW()),
(NULL, 3, 68, 1, NOW()),
(NULL, 3, 57, 1, NOW()),
(NULL, 3, 48, 1, NOW()),
(NULL, 3, 39, 1, NOW()),
(NULL, 3, 40, 1, NOW()),
(NULL, 3, 55, 1, NOW()),
(NULL, 3, 58, 1, NOW()),
(NULL, 3, 81, 1, NOW()),
(NULL, 3, 82, 1, NOW()),
(NULL, 3, 73, 1, NOW()),
(NULL, 3, 79, 1, NOW()),
(NULL, 3, 80, 1, NOW()),
(NULL, 3, 22, 1, NOW()),
(NULL, 3, 51, 1, NOW()),
(NULL, 3, 84, 1, NOW()),
(NULL, 3, 54, 1, NOW()),
(NULL, 3, 60, 1, NOW()),
(NULL, 3, 66, 1, NOW()),
(NULL, 3, 78, 1, NOW()),
(NULL, 5, 32, 1, NOW()),
(NULL, 5, 33, 1, NOW()),
(NULL, 5, 30, 1, NOW()),
(NULL, 5, 85, 1, NOW()),
(NULL, 5, 87, 1, NOW()),
(NULL, 5, 94, 0, NOW()),
(NULL, 5, 83, 0, NOW()),
(NULL, 5, 76, 0, NOW()),
(NULL, 5, 77, 0, NOW()),
(NULL, 5, 89, 0, NOW()),
(NULL, 5, 91, 0, NOW()),
(NULL, 5, 46, 0, NOW()),
(NULL, 5, 37, 0, NOW()),
(NULL, 5, 92, 0, NOW()),
(NULL, 5, 93, 0, NOW()),
(NULL, 5, 68, 0, NOW()),
(NULL, 5, 57, 0, NOW()),
(NULL, 5, 48, 0, NOW()),
(NULL, 5, 39, 0, NOW()),
(NULL, 5, 40, 0, NOW()),
(NULL, 5, 55, 0, NOW()),
(NULL, 5, 58, 1, NOW()),
(NULL, 5, 81, 0, NOW()),
(NULL, 5, 82, 0, NOW()),
(NULL, 5, 73, 0, NOW()),
(NULL, 5, 79, 0, NOW()),
(NULL, 5, 80, 0, NOW()),
(NULL, 5, 22, 1, NOW()),
(NULL, 5, 51, 1, NOW()),
(NULL, 5, 84, 0, NOW()),
(NULL, 5, 54, 0, NOW()),
(NULL, 5, 60, 0, NOW()),
(NULL, 5, 66, 0, NOW()),
(NULL, 5, 78, 0, NOW()),
(NULL, 2, 32, 0, NOW()),
(NULL, 2, 33, 0, NOW()),
(NULL, 2, 30, 0, NOW()),
(NULL, 2, 85, 0, NOW()),
(NULL, 2, 87, 0, NOW()),
(NULL, 2, 94, 0, NOW()),
(NULL, 2, 83, 0, NOW()),
(NULL, 2, 76, 0, NOW()),
(NULL, 2, 77, 0, NOW()),
(NULL, 2, 89, 0, NOW()),
(NULL, 2, 91, 0, NOW()),
(NULL, 2, 46, 0, NOW()),
(NULL, 2, 37, 1, NOW()),
(NULL, 2, 92, 0, NOW()),
(NULL, 2, 93, 0, NOW()),
(NULL, 2, 68, 0, NOW()),
(NULL, 2, 57, 0, NOW()),
(NULL, 2, 48, 1, NOW()),
(NULL, 2, 39, 1, NOW()),
(NULL, 2, 40, 0, NOW()),
(NULL, 2, 55, 0, NOW()),
(NULL, 2, 58, 0, NOW()),
(NULL, 2, 81, 0, NOW()),
(NULL, 2, 82, 0, NOW()),
(NULL, 2, 73, 0, NOW()),
(NULL, 2, 79, 0, NOW()),
(NULL, 2, 80, 0, NOW()),
(NULL, 2, 22, 0, NOW()),
(NULL, 2, 51, 0, NOW()),
(NULL, 2, 84, 0, NOW()),
(NULL, 2, 54, 0, NOW()),
(NULL, 2, 60, 0, NOW()),
(NULL, 2, 66, 0, NOW()),
(NULL, 2, 78, 0, NOW());

/*!40000 ALTER TABLE `sys_user_group_b_auth` ENABLE KEYS */;








-- 資料導出被取消選擇。
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

-- 資料導出被取消選擇。
-- 導出  表 edoma.user_message 結構
DROP TABLE IF EXISTS `user_message`;
CREATE TABLE IF NOT EXISTS `user_message` (
  `sn` bigint(20) NOT NULL AUTO_INCREMENT,
  `edit_user_sn` bigint(20) DEFAULT NULL COMMENT '訊息編輯者',
  `to_user_sn` int(11) NOT NULL,
  `to_user_app_id` varchar(10) DEFAULT NULL,
  `to_user_name` varchar(10) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `msg_content` text NOT NULL COMMENT '訊息',
  `is_sync` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已同步至server',
  `post_date` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='訊息';

-- 資料導出被取消選擇。
-- 導出  表 edoma.user_message_assign 結構
DROP TABLE IF EXISTS `user_message_assign`;
CREATE TABLE IF NOT EXISTS `user_message_assign` (
  `sn` bigint(20) NOT NULL AUTO_INCREMENT,
  `edit_user_sn` bigint(20) DEFAULT NULL COMMENT '訊息編輯者',
  `to_user_sn` varchar(500) NOT NULL,
  `to_user_name` varchar(500) DEFAULT NULL,
  `to_user_count` smallint(5) unsigned DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `msg_content` text NOT NULL COMMENT '訊息',
  `is_sync` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已同步至server',
  `post_date` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='訊息';



-- 資料導出被取消選擇。
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



-- 資料導出被取消選擇。
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
  `user_name` varchar(60) DEFAULT NULL COMMENT '議題發佈人姓名',
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='投票主表';


-- 資料導出被取消選擇。
-- 導出  表 edoma.voting_option 結構
DROP TABLE IF EXISTS `voting_option`;
CREATE TABLE IF NOT EXISTS `voting_option` (
  `sn` int(10) NOT NULL AUTO_INCREMENT,
  `voting_sn` int(10) NOT NULL,
  `text` varchar(50) NOT NULL,
  `is_del` int(1) NOT NULL DEFAULT '0' COMMENT '是否刪除 1Y/0N',
  `is_sync` int(1) NOT NULL DEFAULT '0' COMMENT '是否同步 1Y/0N',
  PRIMARY KEY (`sn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='投票選項';

-- 資料導出被取消選擇。
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='投票記錄';


-- 資料導出被取消選擇。
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='前端單元';

-- 正在導出表  edoma.web_setting 的資料：22 rows
/*!40000 ALTER TABLE `web_setting` DISABLE KEYS */;
INSERT INTO `web_setting` (`sn`, `title`, `key`, `value`, `memo`, `type`, `sort`, `launch`, `update_date`) VALUES
    (1, '網站名稱', 'website_title', 'E-DOMA e化你家', '', 'text', 10, 1, NOW()),
    (19, '公告輪播停留秒數', 'bulletin_cycle_sec', '1', '', 'text', 39, 1, NOW()),
    (3, '社區名稱', 'comm_name', '請輸入社區名稱', '', 'text', 30, 1, NOW()),
    (12, '社區簡介', 'comm_desc', '請輸入社區簡介', '', 'textarea', 36, 1, NOW()),
    (6, '管委職稱', 'manager_title', '主委,財委,委員,財務委員,水電委員,監察委員', '請以半形逗點(,)隔開，例如：主委,財委,委員', 'text', 40, 1, NOW()),
    (7, '戶別識別1_名稱', 'building_part_01', '棟別', '請輸入『棟別』或『門牌號碼』，若戶別為英數字，可設定棟別可輸入英數字 ，門牌號可輸入數字', 'text', 53, 1, NOW()),
    (8, '戶別識別1_內容', 'building_part_01_value', 'A,B,C', '請以半形逗點(,)隔開，例如：A,B,C', 'text', 60, 1, NOW()),
    (9, '戶別識別2_名稱', 'building_part_02', '樓層', '', 'text', 70, 1, NOW()),
    (10, '戶別識別2_內容', 'building_part_02_value', '1,2,3,4,5,6,7,8,9,10,11,12', '請以半形逗點(,)隔開，例如：1,2,3', 'text', 80, 1, NOW()),
    (11, '戶別識別3_名稱', 'building_part_03', '住戶人數編號', '', 'text', 90, 1, NOW()),
    (4, 'Google Search ID', 'google_search_id', '017154571463157724076:p-zsbzzctk4', '', 'text', 9999, 0, NOW()),
    (5, 'Google analytics', 'google_analytics', '  (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){', '', 'text', 9999, 0, NOW()),
    (13, '車位識別1_名稱', 'parking_part_01', '停車位棟別', '', 'text', 100, 1, NOW()),
    (14, '車位識別1_內容', 'parking_part_01_value', 'A,B,C', '請以半形逗點(,)隔開，例如：A,B,C', 'text', 110, 1, NOW()),
    (15, '車位識別2_名稱', 'parking_part_02', '停車位樓層', '', 'text', 120, 1, NOW()),
    (16, '車位識別2_內容', 'parking_part_02_value', '1F,B1,B2,B3', '請以半形逗點(,)隔開，例如：B1,B2,B3', 'text', 130, 1, NOW()),
    (17, '車位識別3_名稱', 'parking_part_03', '車位編號', '', 'text', 140, 1, NOW()),
    (18, '郵件類型', 'mail_box_type', '掛號信,包裹,代收包裹,送洗衣物', '請以半形逗點(,)隔開，例如：掛號信,包裹,代收包裹 ，若欲增加新的類型，請勿更改原先順序', 'text', 38, 1, NOW()),
    (20, '社區電話', 'comm_tel', '請輸入社區電話', '', 'text', 32, 1, NOW()),
    (21, '社區地址', 'comm_addr', '請輸入社區地址', '', 'text', 35, 1, NOW()),
    (22, '地址識別_門牌號碼', 'addr_part_01', '', '請以半形逗點(,)隔開，例如：環中路100號,環中路102號,環中路104巷1號', 'text', 50, 1, NOW()),
    (23, '地址識別_樓層', 'addr_part_02', '一,二,三,四,五,六,七,八,九,十,十一,十二', '請以半形逗點(,)隔開，例如：1,2,3', 'text', 52, 1, NOW());
/*!40000 ALTER TABLE `web_setting` ENABLE KEYS */;


-- 資料導出被取消選擇。
-- 導出  表 edoma.web_setting_photo 結構
DROP TABLE IF EXISTS `web_setting_photo`;
CREATE TABLE IF NOT EXISTS `web_setting_photo` (
  `sn` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序號',
  `img_filename` varchar(60) NOT NULL COMMENT '檔名',
  `title` varchar(60) NOT NULL COMMENT '說明',
  `updated` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='照片';


/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

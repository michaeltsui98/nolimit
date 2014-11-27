/*
Navicat MySQL Data Transfer

Source Server         : 172.16.0.3===222_3306
Source Server Version : 50527
Source Host           : 172.16.0.3:3306
Source Database       : dodojc

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2014-11-26 17:19:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `activity_apply`
-- ----------------------------
DROP TABLE IF EXISTS `activity_apply`;
CREATE TABLE `activity_apply` (
  `activity_apply_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '系统自动产生id',
  `activity_apply_subject` varchar(10) NOT NULL COMMENT '''活动科目：GS0024：生命安全,GS0025：心理健康'',',
  `activity_apply_proposer_uid` varchar(50) NOT NULL COMMENT '活动申请人的ID',
  `activity_apply_proposer_name` varchar(50) NOT NULL COMMENT '申请人姓名（冗余）',
  `activity_apply_proposer_school` varchar(50) NOT NULL COMMENT '申请人学校名（冗余）',
  `activity_apply_title` varchar(50) NOT NULL COMMENT '活动标题',
  `activity_apply_type` tinyint(1) NOT NULL COMMENT '活动类型：1：基础理论，2：专业知识，3：教学技能，4：教育技能，5：自主学习',
  `activity_apply_class` tinyint(1) NOT NULL COMMENT '研修的表现形式:1、专家沙龙（视频），2、示范课（视频），3、说课PPT（视频），4、互动课堂（直播），',
  `activity_apply_phase` varchar(10) NOT NULL COMMENT '活动学段：xd001：小学,xd002：初中,xd003：高中',
  `activity_apply_description` text NOT NULL COMMENT '活动简介',
  `activity_apply_reason` text NOT NULL COMMENT '申请理由',
  `activity_apply_contact` varchar(100) NOT NULL COMMENT '联系方式',
  `activity_apply_status` tinyint(1) NOT NULL COMMENT '活动状态：1:申请中，2:已通过，3：未通过',
  `activity_apply_date` int(10) NOT NULL,
  `activity_apply_publisher` varchar(10) NOT NULL,
  PRIMARY KEY (`activity_apply_id`),
  KEY `proposerUid` (`activity_apply_proposer_uid`),
  KEY `applyType` (`activity_apply_type`),
  KEY `applyClass` (`activity_apply_class`),
  KEY `activityPhase` (`activity_apply_phase`),
  KEY `activityStatus` (`activity_apply_status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='教师活动申请表';

-- ----------------------------
-- Records of activity_apply
-- ----------------------------
INSERT INTO `activity_apply` VALUES ('2', 'GS0025', 'w36451978107373010061', '数学老师', '长江数字小学', '数学老师申请的第一个研训', '1', '1', 'xd001', '这是活动简介', '这里是申请理由', '13988888888', '1', '1399966094', 'v01');
INSERT INTO `activity_apply` VALUES ('3', 'GS0025', 'w36451978107373010061', '数学老师', '长江数字小学', '123123', '3', '2', 'xd003', '       123123             ', '\r\n        123123            ', '123123', '1', '1400724035', 'v01');
INSERT INTO `activity_apply` VALUES ('4', 'GS0025', 'w36451978107373010061', '数学老师', '长江数字小学', '234', '2', '1', 'xd003', '到是覆盖士大夫       ', '是大法官         ', '是大法官', '1', '1400724337', 'v02');
INSERT INTO `activity_apply` VALUES ('5', 'GS0025', 'w36451978107373010061', '数学老师', '长江数字小学', '123123', '2', '3', 'xd001', '               123     ', '123\r\n                    ', '123', '1', '1400724814', 'v01');
INSERT INTO `activity_apply` VALUES ('6', 'GS0025', 'w36451978107373010061', '数学老师', '长江数字小学', '123 ', '2', '3', 'xd003', '                   123 ', '\r\n         123           ', '123123 ', '1', '1400724865', 'v11');
INSERT INTO `activity_apply` VALUES ('7', 'GS0025', 'w36451978107373010061', '数学老师', '长江数字小学', '玩儿', '1', '1', 'xd001', '                玩儿    ', '\r\n                  玩儿  ', ' 玩儿', '1', '1400725021', 'v01');
INSERT INTO `activity_apply` VALUES ('8', 'GS0024', 'm36359802300862200030', '徐杰', '长江数字小学', '12222222222222222', '1', '1', 'xd001', '                    ', '\r\n                    ', '13647200710', '1', '1401611793', 'v11');

-- ----------------------------
-- Table structure for `activity_city`
-- ----------------------------
DROP TABLE IF EXISTS `activity_city`;
CREATE TABLE `activity_city` (
  `activity_city_id` int(10) NOT NULL AUTO_INCREMENT,
  `activity_city_pid` int(10) NOT NULL COMMENT '关联的活动ID',
  `activity_city_code` varchar(10) NOT NULL COMMENT '地区编码',
  PRIMARY KEY (`activity_city_id`),
  UNIQUE KEY `activity_city_unique` (`activity_city_pid`,`activity_city_code`),
  KEY `activity_id_index` (`activity_city_pid`),
  KEY `city_code_index` (`activity_city_code`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8 COMMENT='活动地区范围';

-- ----------------------------
-- Records of activity_city
-- ----------------------------
INSERT INTO `activity_city` VALUES ('176', '16', '420100');
INSERT INTO `activity_city` VALUES ('178', '16', '420200');
INSERT INTO `activity_city` VALUES ('177', '16', '420500');
INSERT INTO `activity_city` VALUES ('181', '16', '420600');
INSERT INTO `activity_city` VALUES ('180', '16', '420900');
INSERT INTO `activity_city` VALUES ('179', '16', '429004');
INSERT INTO `activity_city` VALUES ('195', '17', '420100');
INSERT INTO `activity_city` VALUES ('197', '17', '420200');
INSERT INTO `activity_city` VALUES ('196', '17', '420500');
INSERT INTO `activity_city` VALUES ('24', '18', '420100');
INSERT INTO `activity_city` VALUES ('26', '19', '420100');
INSERT INTO `activity_city` VALUES ('27', '19', '420500');
INSERT INTO `activity_city` VALUES ('31', '20', '420100');
INSERT INTO `activity_city` VALUES ('143', '21', '420100');
INSERT INTO `activity_city` VALUES ('28', '22', '420100');
INSERT INTO `activity_city` VALUES ('29', '22', '420600');
INSERT INTO `activity_city` VALUES ('30', '22', '429501');
INSERT INTO `activity_city` VALUES ('172', '23', '420100');
INSERT INTO `activity_city` VALUES ('173', '24', '420100');
INSERT INTO `activity_city` VALUES ('174', '24', '420500');
INSERT INTO `activity_city` VALUES ('175', '24', '429004');
INSERT INTO `activity_city` VALUES ('135', '25', '420100');
INSERT INTO `activity_city` VALUES ('136', '25', '420500');
INSERT INTO `activity_city` VALUES ('137', '25', '429004');
INSERT INTO `activity_city` VALUES ('154', '26', '420100');
INSERT INTO `activity_city` VALUES ('156', '26', '420200');
INSERT INTO `activity_city` VALUES ('155', '26', '420500');
INSERT INTO `activity_city` VALUES ('157', '26', '429004');
INSERT INTO `activity_city` VALUES ('202', '27', '420100');
INSERT INTO `activity_city` VALUES ('184', '28', '420100');
INSERT INTO `activity_city` VALUES ('185', '28', '420500');
INSERT INTO `activity_city` VALUES ('188', '28', '420600');
INSERT INTO `activity_city` VALUES ('189', '28', '420900');
INSERT INTO `activity_city` VALUES ('186', '28', '422800');
INSERT INTO `activity_city` VALUES ('187', '28', '429004');
INSERT INTO `activity_city` VALUES ('162', '29', '420100');
INSERT INTO `activity_city` VALUES ('163', '30', '420100');
INSERT INTO `activity_city` VALUES ('164', '31', '420100');
INSERT INTO `activity_city` VALUES ('165', '32', '420100');
INSERT INTO `activity_city` VALUES ('200', '33', '420100');
INSERT INTO `activity_city` VALUES ('201', '33', '421100');
INSERT INTO `activity_city` VALUES ('167', '34', '420100');
INSERT INTO `activity_city` VALUES ('203', '35', '420100');
INSERT INTO `activity_city` VALUES ('205', '41', '420100');

-- ----------------------------
-- Table structure for `activity_evaluate`
-- ----------------------------
DROP TABLE IF EXISTS `activity_evaluate`;
CREATE TABLE `activity_evaluate` (
  `activity_evaluate_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '系统自动生成id',
  `activity_evaluate_pid` int(10) NOT NULL COMMENT '活动ID',
  `activity_evaluate_score` tinyint(1) NOT NULL COMMENT '活动分数:1,2,3,4,5',
  `activity_evaluate_updater_id` varchar(50) NOT NULL COMMENT '打分人的id',
  `activity_evaluate_date` int(10) DEFAULT NULL COMMENT '打分时间',
  PRIMARY KEY (`activity_evaluate_id`),
  UNIQUE KEY `scoreOnlyIndex` (`activity_evaluate_pid`,`activity_evaluate_updater_id`),
  KEY `activityIdIndex` (`activity_evaluate_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动评价表';

-- ----------------------------
-- Records of activity_evaluate
-- ----------------------------

-- ----------------------------
-- Table structure for `activity_experience`
-- ----------------------------
DROP TABLE IF EXISTS `activity_experience`;
CREATE TABLE `activity_experience` (
  `activity_experience_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '系统自动产生id',
  `activity_experiece_pid` int(10) NOT NULL COMMENT '活动id',
  `activity_experience_updater_id` varchar(50) NOT NULL COMMENT '上传心得的用户id',
  `activity_experience_date` int(10) NOT NULL COMMENT '上传时间',
  `activity_experience_content` text NOT NULL COMMENT '心得内容',
  PRIMARY KEY (`activity_experience_id`),
  KEY `activityIdIndex` (`activity_experiece_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动心得表';

-- ----------------------------
-- Records of activity_experience
-- ----------------------------

-- ----------------------------
-- Table structure for `activity_info`
-- ----------------------------
DROP TABLE IF EXISTS `activity_info`;
CREATE TABLE `activity_info` (
  `activity_info_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '数据ID',
  `activity_info_subject` varchar(10) NOT NULL COMMENT '活动科目：GS0024：生命安全,GS0025：心理健康',
  `activity_info_type` tinyint(1) NOT NULL COMMENT '活动类型：1：基础理论,2：专业知识,3：教学技能,4：教育技能,5：自主学习',
  `activity_info_is_sync` tinyint(1) NOT NULL COMMENT '是否是同步课程：0：不是，1：是',
  `activity_info_visibility` tinyint(1) NOT NULL COMMENT '活动可见性：0：私有，1：公开',
  `activity_info_phase` varchar(10) NOT NULL COMMENT '活动学段：xd001：小学 xd002：初中 xd003：高中',
  `activity_info_class` tinyint(2) NOT NULL COMMENT '研修的表现形式：1、专家沙龙（视频），2、示范课（视频），3、说课PPT（视频）,4、互动课堂（直播）',
  `activity_info_live_url` varchar(200) DEFAULT '' COMMENT '直播用到的视频连接',
  `activity_info_release_date` int(10) NOT NULL COMMENT '活动的发布时间',
  `activity_info_update_date` int(10) NOT NULL COMMENT '活动的最新修改时间',
  `activity_info_start_date` int(10) NOT NULL COMMENT '活动正式开始的时间',
  `activity_info_status` tinyint(1) NOT NULL COMMENT '活动状态:0：已关闭，1：已发布2：已开始',
  `activity_info_title` varchar(50) NOT NULL COMMENT '活动的标题',
  `activity_info_icon` varchar(100) DEFAULT NULL COMMENT '活动标题图片',
  `activity_info_updater_id` varchar(50) NOT NULL COMMENT '活动管理员id',
  `activity_info_host` text NOT NULL COMMENT '活动主持人',
  `activity_info_description` text NOT NULL COMMENT '活动介绍',
  `activity_info_hits` int(10) NOT NULL DEFAULT '0' COMMENT '活动被点击次数',
  `activity_info_publisher` varchar(10) DEFAULT '' COMMENT '教材版本:v01人教版，v02鄂教版',
  `activity_info_grade` varchar(10) DEFAULT 'GO000' COMMENT '年级',
  `activity_info_weight` int(5) NOT NULL DEFAULT '0' COMMENT '权重',
  `activity_info_area` text NOT NULL COMMENT '活动地区：市州代码用逗号分隔',
  `activity_info_hit` int(10) NOT NULL DEFAULT '0' COMMENT '活动浏览次数',
  PRIMARY KEY (`activity_info_id`),
  KEY `subjectIndex` (`activity_info_subject`) USING BTREE,
  KEY `syncIndex` (`activity_info_is_sync`),
  KEY `stateIndex` (`activity_info_status`),
  KEY `publisherIndex` (`activity_info_publisher`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='活动信息表';

-- ----------------------------
-- Records of activity_info
-- ----------------------------
INSERT INTO `activity_info` VALUES ('16', 'GS0025', '2', '0', '1', 'xd002', '3', '', '1399513058', '1402560796', '1400117853', '1', '123wwwwwwwwwwwwwwwwwwww', '536ae6493a6eb.jpg', '448', '123,我', '', '0', 'v11', 'GO000', '0', '420100,420500,420200,429004,420900,420600', '6');
INSERT INTO `activity_info` VALUES ('17', 'GS0025', '3', '0', '1', 'xd001', '1', '', '1399513152', '1403052986', '1400549941', '2', '活动编号178', '536ae63f9b9b4.jpg', '448', '我,你', '<p>123888</p>', '0', 'v11', 'GO000', '0', '420100,420500,420200', '12');
INSERT INTO `activity_info` VALUES ('18', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1400468601', '1400468616', '1401419002', '0', '123', '', '448', '123', '<p>1231234</p>', '0', 'v00', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('19', 'GS0025', '1', '1', '1', 'xd001', '1', '', '1400470430', '1400478646', '1401248031', '0', '44444', '', '448', '214124', '<p>141214</p>', '0', 'v11', 'GO000', '0', '420100,420500', '0');
INSERT INTO `activity_info` VALUES ('20', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1400470505', '1400478659', '1401161705', '0', '111', '', '448', '111', '<p>111</p>', '0', 'v00', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('21', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1400470517', '1402297499', '1401161705', '0', '111', '', '448', '111', '<p>111</p>', '0', 'v11', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('22', 'GS0025', '1', '1', '1', 'xd000', '3', '', '1400478484', '1400478653', '1401169677', '0', '检索啊', '', '448', '检索吧', '<p>检索吧<br /></p>', '0', 'v00', 'GO000', '0', '420100,420600,429501', '0');
INSERT INTO `activity_info` VALUES ('23', 'GS0025', '1', '1', '1', 'xd001', '2', '', '1400478599', '1402560714', '1400737793', '1', '1111', '537aa590b7272.jpg', '448', '检索测试', '<p>检索测试</p>', '0', 'v11', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('24', 'GS0025', '1', '1', '1', 'xd001', '2', '', '1401174657', '1402560739', '1401347416', '1', '新活动24wwwwwwwwwwwwww', '53843a3839e56.jpg', '448', '教师一,教师二,教师三', '<p>5555555</p>', '0', 'v11', 'GO000', '0', '420100,420500,429004', '0');
INSERT INTO `activity_info` VALUES ('25', 'GS0025', '1', '1', '1', 'xd001', '1', '', '1401178162', '1401841707', '1401264558', '0', '新活动255', '538448167731a.jpg', '448', '我啊', '<p>9999999</p>', '0', 'v11', 'GO000', '0', '420100,420500,429004', '0');
INSERT INTO `activity_info` VALUES ('26', 'GS0024', '1', '1', '1', 'xd001', '1', '', '1401435385', '1402535950', '1403076960', '1', '生命活动11', '538834d45de86.jpg', '1', '讲师1,讲师2', '<p>生命安全的第一个活动</p>', '0', 'v11', 'GO000', '0', '420100,420500,420200,429004', '48');
INSERT INTO `activity_info` VALUES ('27', 'GS0025', '1', '1', '1', 'xd001', '1', '', '1401442351', '1403256680', '1403688747', '1', '78787878723423424521423424243', 'activity/xl/539e5e8db10f4.jpg', '448', '1234', '<p>1234</p>', '0', 'v11', 'GO000', '0', '420100', '51');
INSERT INTO `activity_info` VALUES ('28', 'GS0024', '2', '1', '1', 'xd001', '2', '', '1402537739', '1402625551', '1402624123', '1', '生命活动12', '539906ea3156f.jpg', '1', '11,22,33', '<p>呵呵呵呵呵呵</p>', '0', 'v11', 'GO000', '0', '420100,420500,422800,429004,420600,420900', '6');
INSERT INTO `activity_info` VALUES ('29', 'GS0024', '1', '1', '1', 'xd001', '1', '', '1402554868', '1402554882', '1402641260', '1', '生命活动13', '', '1', '1', '<p>3</p>', '0', 'v11', 'GO000', '0', '420100', '1');
INSERT INTO `activity_info` VALUES ('30', 'GS0024', '1', '1', '1', 'xd001', '1', '', '1402554944', '1402554944', '1402641338', '1', '生命活动14', '', '1', '14', '<p>14</p>', '0', 'v11', 'GO000', '0', '420100', '3');
INSERT INTO `activity_info` VALUES ('31', 'GS0024', '1', '1', '1', 'xd001', '2', '', '1402554960', '1402554960', '1403678156', '1', '15', '', '1', '15', '<p>15</p>', '0', 'v11', 'GO000', '0', '420100', '2');
INSERT INTO `activity_info` VALUES ('32', 'GS0024', '1', '1', '1', 'xd001', '1', '', '1402554978', '1402554978', '1403678174', '1', '16', '', '1', '16', '<p>16<br /></p>', '0', 'v11', 'GO000', '0', '420100', '8');
INSERT INTO `activity_info` VALUES ('33', 'GS0024', '3', '1', '1', 'xd002', '2', '', '1402554998', '1403054189', '1403764589', '1', '17', 'activity/sm/53a0e862a9ab1.jpg', '1', '17', '<p>17</p>', '0', 'v11', 'GO000', '0', '420100,421100', '27');
INSERT INTO `activity_info` VALUES ('34', 'GS0024', '1', '1', '1', 'xd001', '1', '', '1402555018', '1402555018', '1403246209', '1', '18', '', '1', '18', '<p>18</p>', '0', 'v11', 'GO000', '0', '420100', '1');
INSERT INTO `activity_info` VALUES ('35', 'GS0024', '1', '0', '1', 'xd001', '2', '', '1413516483', '1413516483', '1413516445', '1', '活动数据测试', 'activity/sm/54408c951d203.JPG', '1', '徐云飞', 'teeeeee<script>window.parent.UE.instants[\'ueditorInstant0\']._setup(document);</script>', '0', 'v11', 'GO000', '0', '420100', '11');
INSERT INTO `activity_info` VALUES ('36', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1416547682', '1416547682', '1416979547', '1', '123123', '', '443', '123123', '13123<script>window.parent.UE.instants[\'ueditorInstant0\']._setup(document);</script>', '0', 'v11', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('37', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1416547725', '1416547725', '1416979547', '1', '123123', '', '443', '123123', '131234<script>window.parent.UE.instants[\'ueditorInstant0\']._setup(document);</script>', '0', 'v11', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('38', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1416547812', '1416547812', '1416547819', '1', '88888', '', '443', '888', '88888<script>window.parent.UE.instants[\'ueditorInstant0\']._setup(document);</script>', '0', 'v11', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('39', 'GS0025', '1', '0', '1', 'xd000', '1', '', '1416548199', '1416548199', '1417066606', '1', '999999', '', '443', '9999', '9999<script>window.parent.UE.instants[\'ueditorInstant0\']._setup(document);</script>', '0', 'v11', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('40', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1416548250', '1416548250', '1417066657', '1', '111111', '', '443', '111', '111<script>window.parent.UE.instants[\'ueditorInstant0\']._setup(document);</script>', '0', 'v11', 'GO000', '0', '420100', '0');
INSERT INTO `activity_info` VALUES ('41', 'GS0025', '0', '0', '1', 'xd000', '0', '', '1416548480', '1416554261', '1417066886', '1', '123888', '', '443', '2222', '<p>222222</p><script _ue_org_tagname=\"script\">window.parent.UE.instants[\'ueditorInstant0\']._setup(document);</script>', '0', 'v11', 'GO000', '0', '420100', '0');

-- ----------------------------
-- Table structure for `activity_member`
-- ----------------------------
DROP TABLE IF EXISTS `activity_member`;
CREATE TABLE `activity_member` (
  `activity_member_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '系统自动产生id',
  `activity_member_pid` int(10) NOT NULL COMMENT '活动ID',
  `activity_member_uid` varchar(50) NOT NULL COMMENT '参与活动的学员id',
  `activity_member_real_name` varchar(10) NOT NULL COMMENT '成员名字（冗余）',
  `activity_member_icon` varchar(100) DEFAULT NULL,
  `activity_member_area_code` varchar(10) DEFAULT NULL COMMENT '地区编码（冗余）',
  `activity_member_area_name` varchar(50) DEFAULT NULL COMMENT '地区名称（冗余）',
  `activity_member_city_code` varchar(20) DEFAULT NULL,
  `activity_member_city_name` varchar(50) DEFAULT NULL,
  `activity_member_school_code` varchar(50) DEFAULT NULL COMMENT '学校编号（冗余）',
  `activity_member_school_name` varchar(50) DEFAULT NULL COMMENT '学校名称（冗余）',
  `activity_member_evaluate` tinyint(2) NOT NULL COMMENT '对学员评价',
  `activity_member_date` int(10) NOT NULL,
  `activity_member_experience_blogtitle` varchar(50) NOT NULL DEFAULT '',
  `activity_member_experience_blogid` varchar(10) DEFAULT '',
  `activity_member_experience_date` int(10) DEFAULT NULL,
  `activity_member_phase` varchar(10) NOT NULL,
  `activity_member_subject` varchar(10) NOT NULL,
  PRIMARY KEY (`activity_member_id`),
  UNIQUE KEY `activityMemberIndex` (`activity_member_pid`,`activity_member_uid`),
  KEY `activityIdIndex` (`activity_member_uid`),
  KEY `subject_index` (`activity_member_subject`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COMMENT='活动学院表';

-- ----------------------------
-- Records of activity_member
-- ----------------------------
INSERT INTO `activity_member` VALUES ('40', '24', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '0', '1401239161', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('41', '17', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '5', '1401245568', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('42', '25', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '3', '1401245585', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('43', '24', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420109', '东湖开发区', '420100', '武汉市', '121584', '长江实验小学', '4', '1401245610', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('44', '17', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420109', '东湖开发区', '420100', '武汉市', '121584', '长江实验小学', '2', '1401245622', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('53', '26', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '5', '1401436970', '11111', '1367', '1401437208', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('54', '25', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420109', '东湖开发区', '420100', '武汉市', '121584', '长江实验小学', '0', '1401765775', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('55', '27', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '0', '1401874174', '11111', '1377', '1402110191', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('56', '16', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '0', '1402300094', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('57', '23', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '0', '1402300120', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('58', '34', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420109', '东湖开发区', '420100', '武汉市', '121584', '长江实验小学', '0', '1402561447', '', '', '0', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('59', '27', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420109', '东湖开发区', '420100', '武汉市', '121584', '长江实验小学', '0', '1402630637', '', '', '0', 'xd001', 'GS0025');
INSERT INTO `activity_member` VALUES ('60', '24', 'a37758282708118320005', '孙定涛', 'http://dev-images.dodoedu.com/shequPage/common/image/user-64.gif', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '0', '1402883303', '', '', '0', '', 'GS0025');
INSERT INTO `activity_member` VALUES ('61', '32', 't37880655900186820006', '李师师', 'http://dev-images.dodoedu.com/image/20141391840873-19472-9118-1069-64.jpg', '420109', '东湖开发区', '420100', '武汉市', '121502', '长江数字学校', '0', '1402991080', '', '', '0', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('62', '33', 'm36359802300862200030', '徐杰', 'http://dev-images.dodoedu.com/image/20131365387797-15696-3030-4448-64.jpg', '420199', '', '420100', '', '121584', '长江实验小学', '0', '1410414871', 'ewewweewewew', '1506', '1413161193', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('63', '26', 'm36359802300862200030', '徐杰', 'http://dev-images.dodoedu.com/image/20131365387797-15696-3030-4448-64.jpg', '420199', '', '420100', '', '121584', '长江实验小学', '0', '1410414885', '', '', '0', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('64', '32', 'm36359802300862200030', '徐杰', 'http://dev-images.dodoedu.com/image/20131365387797-15696-3030-4448-64.jpg', '420199', '', '420100', '', '121584', '长江实验小学', '0', '1412992473', '', '', '0', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('65', '26', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420199', '', '420100', '', '121584', '长江实验小学', '0', '1413016291', '测', '1509', '1413170375', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('66', '31', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420199', '', '420100', '', '121584', '长江实验小学', '0', '1413852048', '', '', '0', 'xd001', 'GS0024');
INSERT INTO `activity_member` VALUES ('67', '28', 'w36451978107373010061', '数学老师', 'http://dev-images.dodoedu.com/image/20141392865031-15810-1711-6807-64.jpeg', '420199', '', '420100', '', '121584', '长江实验小学', '0', '1413852060', '', '', '0', 'xd001', 'GS0024');

-- ----------------------------
-- Table structure for `activity_resourse`
-- ----------------------------
DROP TABLE IF EXISTS `activity_resourse`;
CREATE TABLE `activity_resourse` (
  `activity_resourse_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '系统自动产生的ID',
  `activity_resourse_pid` int(10) NOT NULL COMMENT '活动ID',
  `activity_resourse_content` varchar(200) NOT NULL COMMENT '资源文件的指针OR内容',
  `activity_resourse_type` tinyint(1) NOT NULL COMMENT '活动资源的类型：1、预告内容，2、过程内容',
  `activity_resourse_weight` int(10) NOT NULL DEFAULT '0' COMMENT '资源权重',
  `activity_resourse_date` int(10) NOT NULL COMMENT '添加时间',
  `activity_resourse_title` varchar(100) DEFAULT NULL,
  `activity_resourse_icon` varchar(200) DEFAULT NULL,
  `activity_resource_key` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`activity_resourse_id`),
  KEY `activityIdIndex` (`activity_resourse_pid`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COMMENT='活动资源表';

-- ----------------------------
-- Records of activity_resourse
-- ----------------------------
INSERT INTO `activity_resourse` VALUES ('96', '17', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1399514631', '小学样稿1.pdf', null, null);
INSERT INTO `activity_resourse` VALUES ('98', '16', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1399514668', '小学样稿1.pdf', null, null);
INSERT INTO `activity_resourse` VALUES ('99', '17', '559', '2', '0', '1399885989', 'zhuangjia_zhang.mp4', '6bd6753772f2e451.png', '53708f42b24f4.mp4');
INSERT INTO `activity_resourse` VALUES ('100', '17', '560', '2', '0', '1399886008', 'zhuangjia_zhang.mp4', '6bd6753772f2e451.png', '53708f42b24f4.mp4');
INSERT INTO `activity_resourse` VALUES ('101', '17', '591', '2', '0', '1400739269', 'zhuangjia_zhang(3)', '6eb051aed1374bee.png', '5360a36ca5957.mp4');
INSERT INTO `activity_resourse` VALUES ('102', '17', '592', '2', '0', '1400739379', 'zhuangjia_zhang(4)', '6eb051aed1374bee.png', '5360a36ca5957.mp4');
INSERT INTO `activity_resourse` VALUES ('103', '17', '593', '2', '0', '1400739470', 'zhuangjia_zhang5', '6eb051aed1374bee.png', '5360a36ca5957.mp4');
INSERT INTO `activity_resourse` VALUES ('104', '16', '600', '2', '0', '1400999756', '3', 'b0219746b4f85e5d.png', '5372c5728dc24.flv');
INSERT INTO `activity_resourse` VALUES ('105', '16', '601', '2', '0', '1400999832', '3', 'b0219746b4f85e5d.png', '5372c5728dc24.flv');
INSERT INTO `activity_resourse` VALUES ('106', '26', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1401435450', '多多教育社区网页标题设置V1.2.docx', null, null);
INSERT INTO `activity_resourse` VALUES ('107', '26', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1401435458', '多多教育社区网页标题设置V1.2.docx', null, null);
INSERT INTO `activity_resourse` VALUES ('111', '26', '610', '2', '0', '1401435844', '3', 'b0219746b4f85e5d.png', '5372c5728dc24.flv');
INSERT INTO `activity_resourse` VALUES ('112', '26', '611', '2', '0', '1401435861', 'zhuangjia_zhang5', '6954ed18b1f21358.png', '537ee77d44bf4.mp4');
INSERT INTO `activity_resourse` VALUES ('113', '26', '612', '2', '0', '1401436066', '他人眼中的我-样课截取3', 'b0219746b4f85e5d.png', '5372c5728dc24.flv');
INSERT INTO `activity_resourse` VALUES ('114', '26', '613', '2', '0', '1401436213', '4', 'b0219746b4f85e5d.png', '5372c5728dc24.flv');
INSERT INTO `activity_resourse` VALUES ('116', '16', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1401866465', '小学样稿1.pdf', null, null);
INSERT INTO `activity_resourse` VALUES ('117', '16', '630', '2', '0', '1401866494', 'zhuangjia_zhang5.mp4', '8c04343f50ec8491.png', '538ec8f9248cc.mp4');
INSERT INTO `activity_resourse` VALUES ('118', '16', '631', '2', '0', '1401866662', 'zhuangjia_zhang5.mp4', '8c04343f50ec8491.png', '538ec8f9248cc.mp4');
INSERT INTO `activity_resourse` VALUES ('119', '16', '632', '2', '0', '1401866752', '4.14他人眼中的我-样课截取4.flv', 'c7ad6157e05fcb5c.png', '538ec890664cc.flv');
INSERT INTO `activity_resourse` VALUES ('120', '27', '632', '2', '0', '1401874153', '4.14他人眼中的我-样课截取4.flv', 'c7ad6157e05fcb5c.png', '538ec890664cc.flv');
INSERT INTO `activity_resourse` VALUES ('121', '24', '654', '2', '0', '1402559124', 'zhuangjia_zhang5.mp4', '8c04343f50ec8491.png', '538ec8f9248cc.mp4');
INSERT INTO `activity_resourse` VALUES ('122', '27', '662', '2', '0', '1402884794', '四年级 第1课《男孩女孩不一样》', 'b1fb043006a672c1.png', '539a67d9065fd.FLV');
INSERT INTO `activity_resourse` VALUES ('123', '27', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1403256486', '博盛游戏', null, null);
INSERT INTO `activity_resourse` VALUES ('124', '27', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1403256631', '博盛游戏', null, null);
INSERT INTO `activity_resourse` VALUES ('125', '27', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1403256718', '博盛游戏', null, null);
INSERT INTO `activity_resourse` VALUES ('126', '27', 'http://dev-jc.dodoedu.com/xl/resource/view/900', '1', '0', '1403257118', '博盛游戏', null, null);
INSERT INTO `activity_resourse` VALUES ('127', '35', '1123', '2', '0', '1413516694', 'zhuangjia_zhang5.mp4', 'f9d6c6a93cdecf54.png', '54408d8fb20e4.mp4');

-- ----------------------------
-- Table structure for `activity_site`
-- ----------------------------
DROP TABLE IF EXISTS `activity_site`;
CREATE TABLE `activity_site` (
  `activity_site_id` int(10) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) NOT NULL COMMENT '主键',
  `site_name` varchar(50) NOT NULL COMMENT '小站名称',
  `site_logo` varchar(100) NOT NULL,
  `site_describe` text,
  `site_join` int(10) NOT NULL COMMENT '加入人数',
  `site_activity_subject` varchar(10) NOT NULL COMMENT '小站对应学科',
  `site_weight` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  PRIMARY KEY (`activity_site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='活动小站';

-- ----------------------------
-- Records of activity_site
-- ----------------------------
INSERT INTO `activity_site` VALUES ('10', '64181726', '搞笑吧', 'http://dev-images.dodoedu.com/image/20141399603120-15802-7592-2262-145.jpg', '小站的描述信息as,just do it.测试aaa', '1', 'GS0025', '0');
INSERT INTO `activity_site` VALUES ('12', '73096994', 'eeee', 'http://dev-images.dodoedu.com/shequPage/common/image/site-145.gif', 'eee', '3', 'GS0025', '0');
INSERT INTO `activity_site` VALUES ('13', '99622169', '心理健康教育数字资讯', 'http://dev-images.dodoedu.com/shequPage/common/image/site-145.gif', '心理健康教育相关资讯集合', '1', 'GS0025', '0');
INSERT INTO `activity_site` VALUES ('14', '64181726', '搞笑吧', 'http://dev-images.dodoedu.com/image/20141399603120-15802-7592-2262-145.jpg', '小站的描述信息as,just do it.测试aaa', '1', 'GS0024', '0');
INSERT INTO `activity_site` VALUES ('15', '73096994', 'eeee', 'http://dev-images.dodoedu.com/shequPage/common/image/site-145.gif', 'eee', '3', 'GS0024', '0');

-- ----------------------------
-- Table structure for `evaluate`
-- ----------------------------
DROP TABLE IF EXISTS `evaluate`;
CREATE TABLE `evaluate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `evaluate_title` varchar(255) DEFAULT NULL COMMENT '测评试卷标题',
  `evaluate_id` int(10) DEFAULT NULL COMMENT '测评试卷id',
  `evaluate_type_id` varchar(24) DEFAULT NULL COMMENT 'GS0025:心理, GS0024:生命科学',
  `evaluate_user_name` varchar(255) DEFAULT NULL COMMENT '测评发布者用户名',
  `evaluate_user_id` varchar(255) DEFAULT NULL COMMENT '测评发布者用户id',
  `evaluate_classify` tinyint(1) DEFAULT NULL COMMENT '1:综合,2:单元,3:趣味',
  `evaluate_end_time` varchar(255) DEFAULT NULL COMMENT '测评截止日期',
  `time` int(10) DEFAULT NULL COMMENT '添加测评的时间',
  `evaluate_version` varchar(255) DEFAULT NULL COMMENT '测评的版本',
  `evaluate_field` varchar(255) DEFAULT NULL COMMENT '学段',
  `evaluate_grade` varchar(255) DEFAULT NULL COMMENT '年级',
  `evaluate_son_id` int(10) DEFAULT NULL COMMENT '知识节点',
  `evaluate_type` int(10) DEFAULT '0' COMMENT '0：内部,1:外部',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COMMENT='测评表';

-- ----------------------------
-- Records of evaluate
-- ----------------------------
INSERT INTO `evaluate` VALUES ('28', '记叙文写作练习', '162', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1400057066', 'v11', 'xd001', 'GO003', '6396', '0');
INSERT INTO `evaluate` VALUES ('29', '商的近似数（五）', '105', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1400057066', '	v11', 'xd001', 'GO003', '6396', '0');
INSERT INTO `evaluate` VALUES ('30', '循环小数（一）', '106', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1400057083', '	v11', 'xd001', 'GO003', '6396', '0');
INSERT INTO `evaluate` VALUES ('31', '循环小数（二）', '107', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1400057083', '	v11', 'xd001', 'GO003', '6396', '0');
INSERT INTO `evaluate` VALUES ('36', '解方程（二）', '123', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1400057615', '	v11', 'xd001', 'GO003', '6385', '0');
INSERT INTO `evaluate` VALUES ('37', '解方程（三）', '124', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1400057615', '	v11', 'xd001', 'GO003', '6385', '0');
INSERT INTO `evaluate` VALUES ('38', '解方程（四）', '125', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1400057615', '	v11', 'xd001', 'GO003', '6385', '0');
INSERT INTO `evaluate` VALUES ('41', '解方程（五）', '126', 'GS0025', '多多乙', 't37880655900186820006', '2', '0', '1400139615', '	v11', 'xd001', 'GO003', '6385', '0');
INSERT INTO `evaluate` VALUES ('42', '小数乘整数测试二', '127', 'GS0025', '多多乙', 't37880655900186820006', '2', '0', '1400139615', '	v11', 'xd001', 'GO003', '6385', '0');
INSERT INTO `evaluate` VALUES ('45', '独立性（小学）', '10001', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1401965006', '	v11', 'xd001', 'GO003', '6396', '1');
INSERT INTO `evaluate` VALUES ('46', '自尊', '10057', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1401965006', '	v11', 'xd001', 'GO003', '6396', '1');
INSERT INTO `evaluate` VALUES ('47', '职业兴趣测量表', '10139', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1401965006', '	v11', 'xd001', 'GO003', '6396', '1');
INSERT INTO `evaluate` VALUES ('54', '2014知识竞赛测试', '165', 'GS0025', 'asdf', 'm36359802300862200030', '1', '7/6/2014', '1402101618', 'v11', 'xd001', 'GO003', '0', '0');
INSERT INTO `evaluate` VALUES ('56', '湖北省第十四届青少年爱国主义读书教育活动网络知识竞赛模拟测试', '164', 'GS0025', 'asdf', 'm36359802300862200030', '1', '7/6/2014', '1402101807', 'v11', 'xd001', 'GO003', '0', '0');
INSERT INTO `evaluate` VALUES ('57', '2014知识竞赛测试', '165', 'GS0024', ' 游龙', 'm36359802300862200030', '1', '7/31/2014', '1402122632', 'v11', 'xd001', 'GO004', '0', '0');
INSERT INTO `evaluate` VALUES ('58', '湖北省第十四届青少年爱国主义读书教育活动网络知识竞赛模拟测试', '164', 'GS0024', ' 游龙', 'm36359802300862200030', '1', '18/6/2014', '1402122632', 'v11', 'xd001', 'GO004', '0', '0');
INSERT INTO `evaluate` VALUES ('59', '小数乘整数测试四', '129', 'GS0024', ' 游龙', 'm36359802300862200030', '2', '0', '1402123025', 'v11', 'xd001', 'GO004', '6975', '0');
INSERT INTO `evaluate` VALUES ('60', '小数乘整数检测五', '130', 'GS0024', ' 游龙', 'm36359802300862200030', '2', '0', '1402123025', 'v11', 'xd001', 'GO004', '6975', '0');
INSERT INTO `evaluate` VALUES ('61', '列解方程（一）', '131', 'GS0024', ' 游龙', 'm36359802300862200030', '2', '0', '1402123025', 'v11', 'xd001', 'GO004', '6975', '0');
INSERT INTO `evaluate` VALUES ('64', '心理成熟（中学）', '251', 'GS0025', 'asdf', 'm36359802300862200030', '1', '15/6/2014', '1402392756', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('67', '独立性（小学）', '10001', 'GS0025', 'asdf', 'm36359802300862200030', '3', '0', '1402553621', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('68', '学习动机（中学）', '10014', 'GS0025', 'asdf', 'm36359802300862200030', '3', '0', '1402553621', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('72', '友谊质量（小学）', '10056', 'GS0025', 'asdf', 'm36359802300862200030', '3', '0', '1402621542', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('73', '职业兴趣测量表', '10139', 'GS0025', 'asdf', 'm36359802300862200030', '3', '0', '1402621542', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('75', '心理成熟（中学）', '251', 'GS0025', 'asdf', 'm36359802300862200030', '3', '0', '1402875823', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('76', 'fdas', '163', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973132', 'v11', 'xd001', 'GO003', '6730', '0');
INSERT INTO `evaluate` VALUES ('77', '商的近似数（五）', '105', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973132', 'v11', 'xd001', 'GO003', '6730', '0');
INSERT INTO `evaluate` VALUES ('78', 'fdas', '163', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973531', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('79', '记叙文写作练习', '162', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973531', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('80', '商的近似数（五）', '105', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973531', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('81', '小数乘整数检测一', '121', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973531', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('82', '解方程（一）', '122', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973531', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('83', '解方程（二）', '123', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973531', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('84', '解方程（四）', '125', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('85', '解方程（五）', '126', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('86', '小数乘整数测试二', '127', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('87', '小数乘整数测试三', '128', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('88', '小数乘整数测试四', '129', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('89', '小数乘整数检测五', '130', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('90', '列解方程（一）', '131', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('91', '认识方程（五）', '120', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('92', '认识方程（四）', '119', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('93', '认识方程（三）', '118', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('94', '循环小数（一）', '106', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('95', '循环小数（二）', '107', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973532', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('96', '循环小数（三）', '108', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1402973533', 'v11', 'xd001', 'GO004', '6761', '0');
INSERT INTO `evaluate` VALUES ('99', '2014知识竞赛测试', '165', 'GS0025', 'asdf', 'm36359802300862200030', '1', '7/7/2014', '1403055615', 'v11', 'xd001', 'GO005', '0', '0');
INSERT INTO `evaluate` VALUES ('101', '2014知识竞赛测试', '165', 'GS0025', 'asdf', 'm36359802300862200030', '1', '6/18/2014', '1403056487', 'v11', 'xd001', 'GO006', '0', '0');
INSERT INTO `evaluate` VALUES ('102', '学习动机（中学）', '10014', 'GS0025', 'asdf', 'm36359802300862200030', '1', '6/27/2014', '1403056747', 'v11', 'xd001', 'GO006', '0', '1');
INSERT INTO `evaluate` VALUES ('103', '学习动机（中学）', '10014', 'GS0025', 'asdf', 'm36359802300862200030', '3', '0', '1403057656', 'v11', 'xd001', 'GO006', '0', '1');
INSERT INTO `evaluate` VALUES ('104', '2014知识竞赛测试', '165', 'GS0025', 'asdf', 'm36359802300862200030', '1', '6/30/2014', '1403062216', 'v11', 'xd001', 'GO004', '0', '0');
INSERT INTO `evaluate` VALUES ('105', '2014知识竞赛模拟测试', '164', 'GS0025', 'asdf', 'm36359802300862200030', '1', '6/30/2014', '1403062216', 'v11', 'xd001', 'GO004', '0', '0');
INSERT INTO `evaluate` VALUES ('106', 'fdas', '163', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1409801458', 'v11', 'xd001', 'GO005', '6777', '0');
INSERT INTO `evaluate` VALUES ('107', '记叙文写作练习', '162', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1409801458', 'v11', 'xd001', 'GO005', '6777', '0');
INSERT INTO `evaluate` VALUES ('108', '商的近似数（五）', '105', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1409801458', 'v11', 'xd001', 'GO005', '6777', '0');
INSERT INTO `evaluate` VALUES ('109', '小数乘整数检测一', '121', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1409801458', 'v11', 'xd001', 'GO005', '6777', '0');
INSERT INTO `evaluate` VALUES ('110', '解方程（一）', '122', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1409801458', 'v11', 'xd001', 'GO005', '6777', '0');
INSERT INTO `evaluate` VALUES ('111', '解方程（二）', '123', 'GS0025', 'asdf', 'm36359802300862200030', '2', '0', '1409801458', 'v11', 'xd001', 'GO005', '6777', '0');
INSERT INTO `evaluate` VALUES ('112', '学习动机（中学）', '10014', 'GS0025', 'asdf', 'm36359802300862200030', '1', '0', '1409818890', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('113', '友谊质量（小学）', '10056', 'GS0025', 'asdf', 'm36359802300862200030', '1', '0', '1409818890', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('114', '职业兴趣测量表', '10139', 'GS0025', 'asdf', 'm36359802300862200030', '1', '0', '1409818890', 'v11', 'xd001', 'GO004', '0', '1');
INSERT INTO `evaluate` VALUES ('115', '2014知识竞赛测试', '165', 'GS0025', 'asdf', 'm36359802300862200030', '1', '9/4/2014', '1409818927', 'v11', 'xd001', 'GO008', '0', '0');
INSERT INTO `evaluate` VALUES ('116', '2014知识竞赛模拟测试', '164', 'GS0025', 'asdf', 'm36359802300862200030', '1', '9/4/2014', '1409818927', 'v11', 'xd001', 'GO008', '0', '0');

-- ----------------------------
-- Table structure for `evaluate_record`
-- ----------------------------
DROP TABLE IF EXISTS `evaluate_record`;
CREATE TABLE `evaluate_record` (
  `evaluate_record_id` int(10) NOT NULL AUTO_INCREMENT,
  `id` int(10) unsigned DEFAULT NULL COMMENT '测评关联id',
  `evaluate_record_user_id` varchar(255) DEFAULT NULL COMMENT '测评者id',
  `evaluate_record_status` tinyint(1) DEFAULT NULL COMMENT '1:未完成测评,2:完成测评,3：已过期',
  `evaluate_record_end_time` int(10) DEFAULT NULL COMMENT '测评结束时间',
  `evaluate_record_score` varchar(255) DEFAULT NULL COMMENT '测评成绩',
  `evaluate_id` int(10) DEFAULT NULL COMMENT '测评试卷id',
  `evaluate_subject` varchar(255) DEFAULT NULL COMMENT '学科',
  `evaluate_classify` int(1) DEFAULT NULL COMMENT '1:综合,2:单元',
  `evaluate_class_id` int(10) DEFAULT NULL COMMENT '班级id',
  `evaluate_course_id` int(10) unsigned DEFAULT '0' COMMENT '课程id',
  PRIMARY KEY (`evaluate_record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='测评记录表';

-- ----------------------------
-- Records of evaluate_record
-- ----------------------------
INSERT INTO `evaluate_record` VALUES ('55', '38', 'l35789637007221180030', '2', '1400746900', '4', '125', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('57', '42', 'l35789637007221180030', '3', '1400745455', '', '127', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('60', '38', 'l35789637007221180030', '2', '1400746900', '4', '125', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('61', '41', 'l35789637007221180030', '2', '1401929778', '6', '126', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('64', '38', 'w36451978107373010061', '1', '1401764734', '', '125', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('65', '42', 'w36451978107373010061', '1', '1401610940', '', '127', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('66', '37', 'w36451978107373010061', '3', '1401267153', '', '124', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('67', '37', 'l35789637007221180030', '3', '1401274136', '', '124', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('68', '36', 'l35789637007221180030', '3', '1401329425', '', '123', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('69', '42', 'l35789637007221180030', '1', '1402992633', '', '127', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('71', '39', 'l35789637007221180030', '3', '1401343469', '', '165', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('74', '40', 'l35789637007221180030', '1', '1401776586', '', '164', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('75', '41', 'w36451978107373010061', '1', '1401760537', '', '126', 'GS0025', '2', '337640', '148');
INSERT INTO `evaluate_record` VALUES ('76', '36', 'w36451978107373010061', '3', '1401610940', '', '123', 'GS0025', '2', '337640', '148');
INSERT INTO `evaluate_record` VALUES ('77', '45', 'l35789637007221180030', '2', '1402046274', '0', '10001', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('78', '56', 'l35789637007221180030', '1', '1402106783', '', '164', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('79', '54', 'l35789637007221180030', '1', '1402106779', '', '165', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('80', '57', 'l35789637007221180030', '1', '1402563998', '', '165', 'GS0024', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('81', '58', 'l35789637007221180030', '1', '1402564003', '', '164', 'GS0024', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('82', '64', 'l35789637007221180030', '3', '1402966053', '', '251', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('86', '70', 'l35789637007221180030', '1', '1402565944', '', '10139', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('87', '67', 'l35789637007221180030', '1', '1403165476', '', '10001', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('88', '45', 'l35789637007221180030', '1', '1402564109', '', '163', 'GS0025', '0', '0', '0');
INSERT INTO `evaluate_record` VALUES ('89', '68', 'l35789637007221180030', '1', '1403165483', '', '10014', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('90', '69', 'l35789637007221180030', '1', '1402565938', '', '10056', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('91', '73', 'l35789637007221180030', '1', '1402900859', '', '10139', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('92', '72', 'l35789637007221180030', '1', '1402900858', '', '10056', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('93', '79', 'w36451978107373010061', '1', '1403056868', '', '162', 'GS0025', '2', '337607', '231');
INSERT INTO `evaluate_record` VALUES ('94', '81', 'w36451978107373010061', '1', '1403056873', '', '121', 'GS0025', '2', '337607', '231');
INSERT INTO `evaluate_record` VALUES ('96', '104', 'l35789637007221180030', '1', '1403161455', '', '165', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('97', '79', 'l35789637007221180030', '1', '1403158223', '', '162', 'GS0025', '2', '337607', '231');
INSERT INTO `evaluate_record` VALUES ('98', '57', 's35951247001862320095', '1', '1403574680', '', '165', 'GS0024', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('99', '58', 's35951247001862320095', '3', '1403574753', '', '164', 'GS0024', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('100', '67', 's35951247001862320095', '1', '1409280875', '', '10001', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('101', '68', 's35951247001862320095', '1', '1408607030', '', '10014', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('102', '73', 's35951247001862320095', '1', '1408607060', '', '10139', 'GS0025', '3', '0', '0');
INSERT INTO `evaluate_record` VALUES ('103', '42', 's35951247001862320095', '1', '1409711693', '', '127', 'GS0025', '2', '337607', '148');
INSERT INTO `evaluate_record` VALUES ('104', '99', 's35951247001862320095', '3', '1409729598', '', '165', 'GS0025', '1', '0', '0');
INSERT INTO `evaluate_record` VALUES ('105', '107', 'w36451978107373010061', '1', '1409811586', '', '162', 'GS0025', '2', '393606', '265');

-- ----------------------------
-- Table structure for `evaluate_unit`
-- ----------------------------
DROP TABLE IF EXISTS `evaluate_unit`;
CREATE TABLE `evaluate_unit` (
  `evaluate_unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluate_unit_user_id` varchar(255) DEFAULT NULL COMMENT '添加测评者id',
  `evaluate_class_id` int(10) DEFAULT NULL COMMENT '接收的班级id',
  `evaluate_son_id` int(10) DEFAULT NULL COMMENT '知识节点',
  `id` int(10) DEFAULT NULL COMMENT '测评关联id',
  `evaluate_end_time` int(10) DEFAULT NULL COMMENT '测评截止日期 0:没有截止日期 ',
  `time` int(10) DEFAULT NULL,
  `evaluate_subject_id` varchar(255) DEFAULT NULL COMMENT 'GS0025:心理, GS0024:生命科学',
  `evaluate_course_id` int(10) DEFAULT NULL COMMENT '测评课程id',
  `evaluate_book_version` varchar(255) DEFAULT NULL COMMENT '学习教程的版本',
  PRIMARY KEY (`evaluate_unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COMMENT='单元测评表';

-- ----------------------------
-- Records of evaluate_unit
-- ----------------------------
INSERT INTO `evaluate_unit` VALUES ('16', 'w36451978107373010061', '337607', '6385', '38', '0', '1400660603', 'GS0025', '148', 'v11');
INSERT INTO `evaluate_unit` VALUES ('17', 'w36451978107373010061', '337607', '6385', '41', '0', '1400660603', 'GS0025', '148', 'v11');
INSERT INTO `evaluate_unit` VALUES ('18', 'w36451978107373010061', '337607', '6385', '42', '0', '1400660603', 'GS0025', '148', 'v11');
INSERT INTO `evaluate_unit` VALUES ('19', 'w36451978107373010061', '337607', '6385', '36', '1400904188', '1400904188', 'GS0025', '148', 'v11');
INSERT INTO `evaluate_unit` VALUES ('20', 'w36451978107373010061', '337607', '6385', '37', '1400904188', '1400904188', 'GS0025', '148', 'v11');
INSERT INTO `evaluate_unit` VALUES ('21', 'l35789637007221180030', '337607', '6396', '45', '0', '1402011256', 'GS0025', '148', 'v11');
INSERT INTO `evaluate_unit` VALUES ('22', 'w36451978107373010061', '337607', '6761', '79', '0', '1403056547', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('23', 'w36451978107373010061', '337607', '6761', '80', '0', '1403056547', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('24', 'w36451978107373010061', '337607', '6761', '78', '0', '1403056685', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('25', 'w36451978107373010061', '337607', '6761', '81', '0', '1403056685', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('26', 'w36451978107373010061', '337607', '6761', '82', '0', '1403056685', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('27', 'w36451978107373010061', '337607', '6761', '86', '0', '1403056717', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('28', 'w36451978107373010061', '337607', '6761', '87', '0', '1403056717', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('29', 'w36451978107373010061', '337607', '6761', '88', '0', '1403056717', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('30', 'w36451978107373010061', '337607', '6761', '92', '0', '1403056718', 'GS0025', '231', 'v11');
INSERT INTO `evaluate_unit` VALUES ('31', 'w36451978107373010061', '337607', '6761', '78', '0', '1403142415', 'GS0025', '233', 'v11');
INSERT INTO `evaluate_unit` VALUES ('32', 'w36451978107373010061', '337607', '6761', '79', '0', '1403142415', 'GS0025', '233', 'v11');
INSERT INTO `evaluate_unit` VALUES ('33', 'w36451978107373010061', '337607', '6761', '80', '0', '1403142415', 'GS0025', '233', 'v11');
INSERT INTO `evaluate_unit` VALUES ('34', 'w36451978107373010061', '337607', '6761', '81', '0', '1403142415', 'GS0025', '233', 'v11');
INSERT INTO `evaluate_unit` VALUES ('35', 'w36451978107373010061', '337607', '6777', '106', '0', '1409801758', 'GS0025', '263', 'v11');
INSERT INTO `evaluate_unit` VALUES ('36', 'w36451978107373010061', '337607', '6777', '107', '0', '1409801758', 'GS0025', '263', 'v11');
INSERT INTO `evaluate_unit` VALUES ('37', 'w36451978107373010061', '337607', '6777', '108', '0', '1409801758', 'GS0025', '263', 'v11');
INSERT INTO `evaluate_unit` VALUES ('38', 'w36451978107373010061', '337607', '6777', '109', '0', '1409801758', 'GS0025', '263', 'v11');
INSERT INTO `evaluate_unit` VALUES ('39', 'w36451978107373010061', '337607', '6777', '106', '0', '1409802011', 'GS0025', '265', 'v11');
INSERT INTO `evaluate_unit` VALUES ('40', 'w36451978107373010061', '337607', '6777', '107', '0', '1409802011', 'GS0025', '265', 'v11');
INSERT INTO `evaluate_unit` VALUES ('41', 'w36451978107373010061', '337607', '6777', '108', '0', '1409802011', 'GS0025', '265', 'v11');
INSERT INTO `evaluate_unit` VALUES ('42', 'w36451978107373010061', '337607', '6777', '109', '0', '1409802011', 'GS0025', '265', 'v11');
INSERT INTO `evaluate_unit` VALUES ('43', 'w36451978107373010061', '337607', '6777', '110', '0', '1409802011', 'GS0025', '265', 'v11');
INSERT INTO `evaluate_unit` VALUES ('44', 'w36451978107373010061', '337607', '6777', '111', '0', '1409802050', 'GS0025', '265', 'v11');
INSERT INTO `evaluate_unit` VALUES ('45', 'w36451978107373010061', '337607', '6777', '106', '0', '1409802144', 'GS0025', '266', 'v11');
INSERT INTO `evaluate_unit` VALUES ('46', 'w36451978107373010061', '337607', '6777', '107', '0', '1409802144', 'GS0025', '266', 'v11');
INSERT INTO `evaluate_unit` VALUES ('47', 'w36451978107373010061', '337607', '6777', '108', '0', '1409802152', 'GS0025', '266', 'v11');
INSERT INTO `evaluate_unit` VALUES ('48', 'w36451978107373010061', '337607', '6777', '109', '0', '1409802152', 'GS0025', '266', 'v11');
INSERT INTO `evaluate_unit` VALUES ('49', 'w36451978107373010061', '337607', '6777', '110', '0', '1409802335', 'GS0025', '266', 'v11');
INSERT INTO `evaluate_unit` VALUES ('50', 'w36451978107373010061', '337607', '6777', '111', '0', '1409802476', 'GS0025', '266', 'v11');
INSERT INTO `evaluate_unit` VALUES ('51', 'w36451978107373010061', '337607', '6777', '106', '0', '1409815593', 'GS0025', '264', 'v11');
INSERT INTO `evaluate_unit` VALUES ('52', 'w36451978107373010061', '337607', '6777', '107', '0', '1409815593', 'GS0025', '264', 'v11');
INSERT INTO `evaluate_unit` VALUES ('53', 'w36451978107373010061', '337607', '6777', '108', '0', '1409815593', 'GS0025', '264', 'v11');
INSERT INTO `evaluate_unit` VALUES ('54', 'w36451978107373010061', '337607', '6777', '109', '0', '1409815593', 'GS0025', '264', 'v11');
INSERT INTO `evaluate_unit` VALUES ('55', 'w36451978107373010061', '337607', '6777', '110', '0', '1409815603', 'GS0025', '264', 'v11');
INSERT INTO `evaluate_unit` VALUES ('56', 'w36451978107373010061', '337607', '6777', '111', '0', '1409815609', 'GS0025', '264', 'v11');

-- ----------------------------
-- Table structure for `game`
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `game_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(10) unsigned NOT NULL COMMENT '分类id',
  `name` varchar(50) NOT NULL COMMENT '游戏名称',
  `swf_url` varchar(150) NOT NULL COMMENT '游戏的swf 地址',
  `plays` int(10) DEFAULT '0' COMMENT '玩的次数',
  `img` varchar(150) DEFAULT NULL COMMENT '游戏图片',
  `js` varchar(200) DEFAULT NULL COMMENT '游戏介绍',
  `remark` float(10,2) DEFAULT '0.00' COMMENT '评分',
  `remark_num` int(10) DEFAULT '0' COMMENT '评分次数',
  `operation` varchar(200) DEFAULT NULL COMMENT '游戏操作方法',
  `game_order` int(10) DEFAULT NULL COMMENT '游戏排序',
  `target` tinyint(1) DEFAULT '0' COMMENT '0 本地窗口打开，1新窗口打开',
  PRIMARY KEY (`game_id`),
  UNIQUE KEY `cate_id` (`cate_id`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='游戏信息表';

-- ----------------------------
-- Records of game
-- ----------------------------
INSERT INTO `game` VALUES ('3', '8', '认识交通标志', 'http://www.bjjtgl.gov.cn/portals/0/flash/03.swf', '0', '53916a5d5b476.jpg', '游戏采用看交通个标志，选择出标志的名称，没有时间限制，加油哦！', '0.00', '0', '鼠标点击操作', '0', '0');
INSERT INTO `game` VALUES ('6', '8', '交通红绿灯', 'http://s5.4399.com:8080/4399swf/upload_swf/ftp6/weijianp/20111014/8.swf', '0', '5391790c8ca7c.jpg', '拥挤的交通总需要一个交警来维持秩序，快来做这个光荣而伟大的职业吧！\r\n', '0.00', '0', '游戏加载完后点击PLAY GAME - 然后点击START - 接着点击OK即可开始游戏\r\n', '0', '0');
INSERT INTO `game` VALUES ('7', '8', '帮小火车开路', 'http://flash.7k7k.com/fl_9/20101018/huoche/huoche.swf', '0', '539179d57f172.jpg', '帮小火车开路', '0.00', '0', '点击火车道连接处开关变道', '0', '0');
INSERT INTO `game` VALUES ('9', '8', '疯狂过马路', 'http://flash.2144.cn/qigongzhu/28/lbenir67.swf', '0', '5391874b15c05.jpg', '你有1分钟的时间，躲开勇敢的司机，带领无畏的行人们顺利抵达马路对面吧，越多越好。', '0.00', '0', '【方向键】控制游戏', '0', '0');
INSERT INTO `game` VALUES ('11', '9', '浴火小枪手', 'http://flash.2144.cn/qigongzhu/3/l5kw4w8h.swf', '0', '5392fc0f7f2ce.jpg', '手拿水枪的小消防员，到处消灭火灾并收集金币，水枪还可以对着地面射，让反冲力把主角顶到高处', '0.00', '0', 'play开始游戏。', '0', '0');
INSERT INTO `game` VALUES ('12', '9', '救火英雄', 'http://flash.2144.cn/qigongzhu/126/ladgdp1q.swf', '0', '5392fcf76be17.jpg', '一家超市发生了火灾，你作为救火队员在火灾中迅速地救出被困人员。', '0.00', '0', '点击START GAME，再点击CONTINUE开始游戏。点SKIP可以跳过剧情介绍.', '0', '0');
INSERT INTO `game` VALUES ('13', '9', '勇敢消防员', 'http://flash.2144.cn/qigongzhu/3/lhu1xccx.swf', '0', '5392fd8c8bc4b.jpg', '鼠标左右晃动移动阿布的位置，点击左键水枪喷射，持续瞄准每个窗口的火焰喷水，直至火焰熄灭。', '0.00', '0', '点击[开始]按钮开始游戏', '0', '1');
INSERT INTO `game` VALUES ('14', '10', '找出安全隐患', 'http://sda.4399.com/4399swf/upload_swf/ftp11/chenweihong/20130827/cwh2.swf', '0', '5392ff4aaf3a8.jpg', '学习日常安全知识，必须从娃娃抓起，一起来指导幼儿注意日常存在的安全隐患吧。', '0.00', '0', '游戏加载完毕后点击[开始游戏]即可开始游戏', '0', '0');
INSERT INTO `game` VALUES ('15', '10', '地震脱险', 'http://sda.4399.com/4399swf/upload_swf/ftp10/chenweihong/20130423/57a.swf', '0', '53930006e5d26.jpg', '帮助游戏的同学顺利到操场躲避吧！大家要认真学习这地震脱险的知识哦！', '0.00', '0', '游戏加载完毕后点击[开始]即可开始游戏', '0', '0');
INSERT INTO `game` VALUES ('16', '10', '火灾逃生游戏', 'http://s2.4399.com:8080/4399swf/upload_swf/ftp6/taodongb/20111024/013.swf', '0', '539300bc159a9.jpg', '请你找出正确的路线逃出火场。', '0.00', '0', '游戏加载完毕点击start - 再点击[进入游戏]即可开始游戏', '0', '0');
INSERT INTO `game` VALUES ('17', '10', '拯救溺水者', 'http://flash.2144.cn/qigongzhu/88/lgzyugq7.swf', '0', '5393016e7cb1b.jpg', '通过放置一些物品来让落水者在洪水中能够浮到水面之上', '0.00', '0', '点击PLAY，选择NEW GAME开始游戏。', '0', '0');
INSERT INTO `game` VALUES ('18', '10', '洪水逃生现场', 'http://s5.4399.com:8080/4399swf/upload_swf/ftp2/liwen/20101203/35.swf', '0', '5393024451a7a.jpg', '逃出这个高危险的地方哦~！', '0.00', '0', '游戏加载完毕后点击CONTINUE - 再点击PLAY GAME - 然后点击PLAY即可开始游戏', '0', '0');
INSERT INTO `game` VALUES ('19', '11', '食品安全', 'http://f1.doyo.cn/flash/swf//81/8a8c16c816144bd2ac4e.swf', '0', '539304e352f7a.jpg', '无', '0.00', '0', '游戏中使用鼠标操作，鼠标点击选择正确的食品', '0', '0');
INSERT INTO `game` VALUES ('20', '11', '卫生饮食', 'http://s1.4399.com:8080/4399swf/upload_swf/ftp/20060625/2.swf', '0', '53930571ef147.jpg', '饮食当然要卫生!坏东西吃了会影响健康哦~~消灭污染源是我们的责任，一起来试试吧！', '0.00', '0', '游戏加载完毕点击IDS-PLAY - 再点击IDS-OK即可开始游戏', '0', '0');
INSERT INTO `game` VALUES ('21', '11', '餐厅卫生检查', 'http://flash.2144.cn/qigongzhu/82/miv7ahre.swf', '0', '539305edac6d1.jpg', '作为一个食品卫生的监督者，你要突击检查下这家餐厅，现在赶快找出所需要检查的东西吧！', '0.00', '0', '游戏加载完毕点击PLAY GAME - 再点击play - 然后点击skip即可开始游戏', '0', '0');
INSERT INTO `game` VALUES ('23', '1', '旋转方格', 'http://heyi.dodoedu.com/remote/game/content.do?type=xzfg', '0', '53930b644e9c0.jpg', '旋转方格', '8.00', '2', '', '0', '0');
INSERT INTO `game` VALUES ('24', '1', '方块记忆', 'http://heyi.dodoedu.com/remote/game/content.do?type=fkjy', '0', '53930c509a304.jpg', '测验记忆力', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('25', '1', '记忆矩阵', 'http://heyi.dodoedu.com/remote/game/content.do?type=jyjz', '0', '53930cddae0c3.jpg', '测试记忆力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('26', '1', '记忆配对', 'http://heyi.dodoedu.com/remote/game/index.do?type=jypd', '0', '53930d56d7394.jpg', '测试记忆力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('27', '2', '小鸟作战', 'http://heyi.dodoedu.com/remote/game/index.do?type=xnzz', '0', '53930dc5d00ef.jpg', '测试注意力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('28', '2', '舒尔特方格', 'http://heyi.dodoedu.com/remote/game/index.do?type=setfg', '0', '53930ec1bdd94.jpg', '测试注意力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('29', '2', '口不对心', 'http://heyi.dodoedu.com/remote/game/index.do?type=kbdx', '0', '53930f2bcf3b7.jpg', '测试注意力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('30', '2', '颜色速配', 'http://heyi.dodoedu.com/remote/game/index.do?type=yssp', '0', '53930f8c4a145.jpg', '测试注意力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('32', '3', '跑丸', 'http://heyi.dodoedu.com/remote/game/index.do?type=pw', '0', '5393101364ee3.jpg', '逻辑推理的小游戏', '0.00', '0', '', '0', '1');
INSERT INTO `game` VALUES ('33', '3', '围堵小猫', 'http://heyi.dodoedu.com/remote/game/index.do?type=wdxm', '0', '53931080245d4.jpg', '逻辑推理的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('34', '3', '除法风暴', 'http://heyi.dodoedu.com/remote/game/index.do?type=uffb', '0', '539310d981fdc.jpg', '逻辑推理的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('35', '3', '乘法风暴', 'http://heyi.dodoedu.com/remote/game/index.do?type=effb', '0', '539311621bfef.jpg', '逻辑推理的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('36', '4', '手脚速配', 'http://heyi.dodoedu.com/remote/game/index.do?type=sjsp', '0', '539311d326038.jpg', '测试感知能力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('37', '4', '水果速配', 'http://heyi.dodoedu.com/remote/game/index.do?type=sgsp', '0', '539312852d044.jpg', '测试感知能力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('38', '4', '空间知觉', 'http://heyi.dodoedu.com/remote/game/index.do?type=kjzj', '0', '539312da90ea7.jpg', '测试感知能力的小游戏', '0.00', '0', '', '0', '0');
INSERT INTO `game` VALUES ('39', '4', '逆向思维', 'http://heyi.dodoedu.com/remote/game/index.do?type=nxsw', '0', '5393137f25d94.jpg', '测试感知能力的小游戏', '0.00', '0', '', '0', '0');

-- ----------------------------
-- Table structure for `game_cate`
-- ----------------------------
DROP TABLE IF EXISTS `game_cate`;
CREATE TABLE `game_cate` (
  `cate_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL COMMENT '分类名称',
  `is_ok` tinyint(1) DEFAULT NULL COMMENT '是否可用',
  `cate_order` int(10) DEFAULT NULL COMMENT '排序',
  `xk` char(15) DEFAULT NULL COMMENT '学科编号',
  PRIMARY KEY (`cate_id`),
  UNIQUE KEY `name` (`name`,`xk`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='游戏分类';

-- ----------------------------
-- Records of game_cate
-- ----------------------------
INSERT INTO `game_cate` VALUES ('1', '记忆力', '1', '1', 'GS0025');
INSERT INTO `game_cate` VALUES ('2', '注意力', '1', '2', 'GS0025');
INSERT INTO `game_cate` VALUES ('3', '逻辑推理', '1', '3', 'GS0025');
INSERT INTO `game_cate` VALUES ('4', '感知能力', '1', '4', 'GS0025');
INSERT INTO `game_cate` VALUES ('8', '交通安全', '1', '1', 'GS0024');
INSERT INTO `game_cate` VALUES ('9', '消防急救', '1', '2', 'GS0024');
INSERT INTO `game_cate` VALUES ('10', '逃生脱险', '1', '3', 'GS0024');
INSERT INTO `game_cate` VALUES ('11', '食品安全', '1', '4', 'GS0024');

-- ----------------------------
-- Table structure for `lesson_folder`
-- ----------------------------
DROP TABLE IF EXISTS `lesson_folder`;
CREATE TABLE `lesson_folder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '备课夹ID',
  `user_id` char(21) NOT NULL COMMENT '所属用户',
  `subject` varchar(10) NOT NULL COMMENT '所属学科',
  `stage` varchar(10) NOT NULL COMMENT '所属学段',
  `edition` varchar(10) NOT NULL COMMENT '所属版本',
  `grade` varchar(10) NOT NULL COMMENT '所属年级',
  `chapter` varchar(10) NOT NULL COMMENT '章节',
  `node` varchar(10) NOT NULL COMMENT '知识节点',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `description` text NOT NULL COMMENT '描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否公开，1公开、0不公开',
  `original` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '原创',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  `resource_layout` tinyint(3) unsigned NOT NULL DEFAULT '6' COMMENT '资源格数量',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `user_id_create_time` (`user_id`,`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COMMENT='课程夹';

-- ----------------------------
-- Records of lesson_folder
-- ----------------------------
INSERT INTO `lesson_folder` VALUES ('8', 'l35789637007221180030', 'GS0025', 'xd001', 'v01', 'GO007', '', '', '备课夹', '这是备课夹的描述', '1', '1', '1', '6', '1398318848');
INSERT INTO `lesson_folder` VALUES ('9', 'l35789637007221180030', 'GS0025', 'xd001', 'v01', 'GO003', '6383', '6385', '测试备课夹索引', '测试备课夹索引', '1', '1', '37', '6', '1398565822');
INSERT INTO `lesson_folder` VALUES ('10', 'l35789637007221180030', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '测试备课夹索引', '测试备课夹索引', '1', '1', '1', '6', '1398565865');
INSERT INTO `lesson_folder` VALUES ('11', 'l35789637007221180030', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '测试备课夹索引', '测试备课夹索引', '1', '1', '1', '6', '1398565930');
INSERT INTO `lesson_folder` VALUES ('12', 'l35789637007221180030', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '测试备课夹索引', '测试备课夹索引', '1', '1', '1', '6', '1398566609');
INSERT INTO `lesson_folder` VALUES ('13', 'l35789637007221180030', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '测试新的索引', '测试新的索引描述', '1', '1', '29', '6', '1398579104');
INSERT INTO `lesson_folder` VALUES ('15', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28测试专用啊', '4.28要上的课', '0', '1', '0', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('16', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.29', '4.29要上的课', '1', '1', '5', '6', '1398583467');
INSERT INTO `lesson_folder` VALUES ('18', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '三单元第二节', '三单元第二节', '1', '1', '67', '15', '1398586039');
INSERT INTO `lesson_folder` VALUES ('19', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6383', '6385', '这是一个新的备课夹', '这是一个新的备课夹描述', '1', '1', '314', '24', '1399531333');
INSERT INTO `lesson_folder` VALUES ('26', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6383', '6385', '', '', '0', '1', '0', '6', '1400059979');
INSERT INTO `lesson_folder` VALUES ('27', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6383', '6385', '', '', '0', '1', '0', '6', '1400060271');
INSERT INTO `lesson_folder` VALUES ('28', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6384', '6386', '', '', '0', '1', '0', '6', '1400061263');
INSERT INTO `lesson_folder` VALUES ('29', 'w35935506404531840025', 'GS0025', 'xd001', 'v01', 'GO003-1', '6384', '6387', '', '', '0', '1', '0', '6', '1400061592');
INSERT INTO `lesson_folder` VALUES ('30', 'w35935506404531840025', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '测试新的索引', '测试新的索引描述', '1', '0', '18', '12', '1398579104');
INSERT INTO `lesson_folder` VALUES ('31', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6384', '6386', '', '', '0', '1', '0', '6', '1400138654');
INSERT INTO `lesson_folder` VALUES ('32', 'w35935506404531840025', 'GS0025', 'xd001', 'v01', 'GO003-1', '6391', '6394', '', '', '0', '1', '0', '6', '1400401662');
INSERT INTO `lesson_folder` VALUES ('33', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '测试新的索引', '测试新的索引描述', '1', '0', '42', '6', '1398579104');
INSERT INTO `lesson_folder` VALUES ('35', 'w36451978107373010061', 'GS0025', 'xd001', 'v02', 'GO003', '6444', '6445', '保护孩子的自尊心', '', '0', '1', '40', '6', '1400642886');
INSERT INTO `lesson_folder` VALUES ('36', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6384', '6386', '', '', '0', '1', '13', '9', '1400655857');
INSERT INTO `lesson_folder` VALUES ('37', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6383', '6385', '这是一个新的备课夹', '这是一个新的备课夹描述', '1', '0', '322', '24', '1399531333');
INSERT INTO `lesson_folder` VALUES ('38', 'w35935506404531840025', 'GS0025', 'xd001', 'v01', 'GO003-1', '6395', '6402', '', '', '0', '1', '1', '6', '1400835431');
INSERT INTO `lesson_folder` VALUES ('39', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28', '4.28要上的课', '1', '0', '2', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('41', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28', '4.28要上的课', '1', '0', '0', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('42', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28', '4.28要上的课', '1', '0', '0', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('43', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28', '4.28要上的课', '1', '0', '0', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('44', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28', '4.28要上的课', '1', '0', '0', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('45', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28', '4.28要上的课', '1', '0', '0', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('46', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '1', '82', '6', '1401153870');
INSERT INTO `lesson_folder` VALUES ('47', 'w35935506404531840025', 'GS0025', 'xd001', 'v01', 'GO003-1', '6383', '6385', '这是一个新的备课夹', '这是一个新的备课夹描述', '1', '0', '305', '24', '1399531333');
INSERT INTO `lesson_folder` VALUES ('48', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO004', '', '', '4.28', '4.28要上的课', '1', '0', '0', '6', '1398583454');
INSERT INTO `lesson_folder` VALUES ('49', 'w36451978107373010061', 'GS0025', 'xd001', 'v02', 'GO003', '6444', '6445', '小学一年级第一章程序开发 lesson1', '', '1', '1', '54', '9', '1401182771');
INSERT INTO `lesson_folder` VALUES ('50', 'w36451978107373010061', 'GS0025', 'xd001', 'v02', 'GO003', '6444', '6445', '小学一年级第一章程序开发 lesson1', '', '1', '0', '12', '9', '1401182771');
INSERT INTO `lesson_folder` VALUES ('51', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6383', '6385', '', '', '0', '1', '6', '9', '1401245128');
INSERT INTO `lesson_folder` VALUES ('52', 'w36451978107373010061', 'GS0025', 'xd001', 'v02', 'GO003', '6444', '6445', '小学一年级第一章程序开发 lesson1', '', '1', '0', '8', '9', '1401182771');
INSERT INTO `lesson_folder` VALUES ('53', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '36', '12', '1401153870');
INSERT INTO `lesson_folder` VALUES ('54', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '26', '6', '1401153870');
INSERT INTO `lesson_folder` VALUES ('55', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6391', '6394', '', '', '0', '1', '8', '9', '1401334641');
INSERT INTO `lesson_folder` VALUES ('56', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '18', '12', '1401153870');
INSERT INTO `lesson_folder` VALUES ('57', 'm36359802300862200030', 'GS0024', 'xd001', 'v01', 'GO003-1', '6381', '6382', '', '', '0', '1', '2', '6', '1401437400');
INSERT INTO `lesson_folder` VALUES ('58', 'a37758282708118320005', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '测试新的索引', '测试新的索引描述', '1', '0', '42', '6', '1398579104');
INSERT INTO `lesson_folder` VALUES ('59', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '21', '12', '1401153870');
INSERT INTO `lesson_folder` VALUES ('60', 'm36359802300862200030', 'GS0025', 'xd001', 'v01', 'GO003', '', '', '三单元第二节', '三单元第二节', '1', '0', '66', '15', '1398586039');
INSERT INTO `lesson_folder` VALUES ('61', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6485', '6486', '', '', '0', '1', '1', '6', '1401614095');
INSERT INTO `lesson_folder` VALUES ('62', 'w36451978107373010061', 'GS0025', 'xd001', 'v02', 'GO003', '6444', '6445', '小学一年级第一章程序开发 lesson1', '', '1', '0', '52', '9', '1401182771');
INSERT INTO `lesson_folder` VALUES ('63', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '34', '12', '1401153870');
INSERT INTO `lesson_folder` VALUES ('64', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '', '', '0', '1', '0', '6', '1402123679');
INSERT INTO `lesson_folder` VALUES ('65', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '', '', '0', '1', '0', '6', '1402123743');
INSERT INTO `lesson_folder` VALUES ('66', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '', '', '0', '1', '0', '6', '1402123901');
INSERT INTO `lesson_folder` VALUES ('67', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '我的生命', '', '1', '1', '3', '6', '1402123922');
INSERT INTO `lesson_folder` VALUES ('68', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '1', '7', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('69', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '1', '8', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('70', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '名称', '', '0', '1', '0', '6', '1402124773');
INSERT INTO `lesson_folder` VALUES ('71', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '79', '6', '1401153870');
INSERT INTO `lesson_folder` VALUES ('72', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '5', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('73', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '5', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('74', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('75', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('76', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '5', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('77', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '81', '6', '1401153870');
INSERT INTO `lesson_folder` VALUES ('78', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '22', '6', '1401153870');
INSERT INTO `lesson_folder` VALUES ('79', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '17', '12', '1401153870');
INSERT INTO `lesson_folder` VALUES ('80', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '3', '1402123966');
INSERT INTO `lesson_folder` VALUES ('81', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('82', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('83', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('84', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('85', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('86', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('87', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('88', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('89', 'w36451978107373010061', 'GS0025', 'xd001', 'v01', 'GO003-1', '6435', '6436', '心里测试，传播正能量', '', '1', '0', '35', '12', '1401153870');
INSERT INTO `lesson_folder` VALUES ('90', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('91', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('92', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '4', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('93', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '我的生命', '', '1', '0', '3', '6', '1402123922');
INSERT INTO `lesson_folder` VALUES ('94', 'm36359802300862200030', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '6936', '生命2', '', '1', '0', '7', '6', '1402123966');
INSERT INTO `lesson_folder` VALUES ('95', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '3', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('96', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '4', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('97', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '1', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('98', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '1', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('99', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '1', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('100', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '3', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('101', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '1', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('102', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '2', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('103', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '2', '6', '1402124388');
INSERT INTO `lesson_folder` VALUES ('104', 'm36359802300862200030', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '心理正常', '', '1', '0', '5', '9', '1402124388');
INSERT INTO `lesson_folder` VALUES ('106', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO003', '6729', '', '这是新的备', '', '1', '1', '33', '12', '1402566402');
INSERT INTO `lesson_folder` VALUES ('109', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO003', '6729', '', '这是新的备', '', '1', '0', '17', '6', '1402566402');
INSERT INTO `lesson_folder` VALUES ('113', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO003', '6731', '', '第三课 我要学', '', '1', '1', '68', '15', '1403056422');
INSERT INTO `lesson_folder` VALUES ('114', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO003', '6935', '', '【备课】', '', '0', '1', '0', '6', '1403056870');
INSERT INTO `lesson_folder` VALUES ('115', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO004', '6974', '6975', '1 妈妈肚子里的我【备课】', '', '1', '1', '28', '9', '1403056887');
INSERT INTO `lesson_folder` VALUES ('116', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO004', '6974', '', '【备课】', '', '0', '1', '0', '6', '1403072236');
INSERT INTO `lesson_folder` VALUES ('117', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO003', '6729', '', '这是新的备', '', '1', '0', '33', '12', '1402566402');
INSERT INTO `lesson_folder` VALUES ('119', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO003', '6729', '', '这是新的备', '', '1', '0', '34', '12', '1402566402');
INSERT INTO `lesson_folder` VALUES ('120', 'w36451978107373010061', 'GS0024', 'xd001', 'v11', 'GO005', '7030', '7032', '6 看热闹 易误伤【备课】', '', '0', '1', '8', '6', '1403571290');
INSERT INTO `lesson_folder` VALUES ('123', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO004', '6771', '', '第十一课 面对陌生人【备课】', '', '1', '1', '49', '9', '1403745956');
INSERT INTO `lesson_folder` VALUES ('124', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO005', '6777', '', '第一课 长大的梦想 【备课】', '', '0', '1', '1', '6', '1409801108');
INSERT INTO `lesson_folder` VALUES ('125', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO004', '6771', '', '第十一课 面对陌生人【备课】', '', '1', '0', '27', '9', '1403745956');
INSERT INTO `lesson_folder` VALUES ('126', 'w36451978107373010061', 'GS0025', 'xd001', 'v11', 'GO003', '6729', '', '第一课 我上学我骄傲【备课】', '', '1', '1', '9', '6', '1415081601');

-- ----------------------------
-- Table structure for `lesson_resource`
-- ----------------------------
DROP TABLE IF EXISTS `lesson_resource`;
CREATE TABLE `lesson_resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `resource_id` int(10) unsigned NOT NULL COMMENT '资源ID',
  `resource_type` tinyint(3) NOT NULL COMMENT '资源类型',
  `resource_preview` varchar(100) NOT NULL COMMENT '资源预览图',
  `folder_id` int(10) unsigned NOT NULL COMMENT '所属课程夹ID',
  `sort` tinyint(3) NOT NULL COMMENT '在课程夹中的排序',
  `title` varchar(100) NOT NULL COMMENT '资源标题',
  `description` text NOT NULL COMMENT '资源描述',
  `create_time` int(10) NOT NULL COMMENT '资源关联时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=473 DEFAULT CHARSET=utf8 COMMENT='课程资源关联';

-- ----------------------------
-- Records of lesson_resource
-- ----------------------------
INSERT INTO `lesson_resource` VALUES ('4', '1', '1', '', '2', '4', '1', '1', '1398418938');
INSERT INTO `lesson_resource` VALUES ('5', '1', '1', '74379150ee9211a6.png', '2', '2', '2', '2', '1398418950');
INSERT INTO `lesson_resource` VALUES ('6', '1', '1', 'f0eda57525169184.png', '2', '3', '3', '3', '1398418956');
INSERT INTO `lesson_resource` VALUES ('42', '548', '3', '0f9a84ba9e6aecaa.png', '19', '24', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人d', '1399890888');
INSERT INTO `lesson_resource` VALUES ('44', '542', '4', '50107ede7c71a7b2.png', '19', '11', '生命安全课', '', '1399890974');
INSERT INTO `lesson_resource` VALUES ('67', '548', '3', '0f9a84ba9e6aecaa.png', '19', '12', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1399952226');
INSERT INTO `lesson_resource` VALUES ('69', '542', '4', '50107ede7c71a7b2.png', '19', '13', '生命安全课题目', '', '1399961903');
INSERT INTO `lesson_resource` VALUES ('71', '548', '3', '0f9a84ba9e6aecaa.png', '19', '9', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1399962923');
INSERT INTO `lesson_resource` VALUES ('72', '548', '3', '0f9a84ba9e6aecaa.png', '19', '5', '教育资源建设技术规范', 'wwew人人', '1399962958');
INSERT INTO `lesson_resource` VALUES ('75', '542', '4', '50107ede7c71a7b2.png', '19', '1', '生命安全课', '', '1400059784');
INSERT INTO `lesson_resource` VALUES ('76', '538', '3', '74379150ee9211a6.png', '19', '1', '流程图.docx', '基本原则载', '1400059790');
INSERT INTO `lesson_resource` VALUES ('77', '548', '3', '0f9a84ba9e6aecaa.png', '19', '1', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1400059792');
INSERT INTO `lesson_resource` VALUES ('78', '548', '3', '0f9a84ba9e6aecaa.png', '26', '0', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1400059982');
INSERT INTO `lesson_resource` VALUES ('79', '542', '4', '50107ede7c71a7b2.png', '26', '1', '生命安全课', '', '1400059983');
INSERT INTO `lesson_resource` VALUES ('80', '538', '3', '74379150ee9211a6.png', '26', '4', '流程图.docx', '基本原则载', '1400059986');
INSERT INTO `lesson_resource` VALUES ('82', '542', '4', '50107ede7c71a7b2.png', '28', '0', '生命安全课', '', '1400061465');
INSERT INTO `lesson_resource` VALUES ('87', '542', '4', '50107ede7c71a7b2.png', '19', '0', '生命安全课', '', '1400123334');
INSERT INTO `lesson_resource` VALUES ('89', '538', '3', '74379150ee9211a6.png', '19', '24', '流程图.docx', '基本原则载', '1400123442');
INSERT INTO `lesson_resource` VALUES ('90', '538', '3', '74379150ee9211a6.png', '19', '24', '流程图.docx', '基本原则载', '1400123463');
INSERT INTO `lesson_resource` VALUES ('97', '562', '4', '4a7f0db04ab6471a.png', '19', '3', '', '', '1400207967');
INSERT INTO `lesson_resource` VALUES ('98', '555', '3', 'f0eda57525169184.png', '19', '0', '', '', '1400207975');
INSERT INTO `lesson_resource` VALUES ('109', '580', '1', 'c9b182f284ec9294.png', '19', '7', '《Go语言编程》.txt', '', '1400226891');
INSERT INTO `lesson_resource` VALUES ('110', '581', '1', 'c9b182f284ec9294.png', '19', '1', '《Go语言编程》.txt', '', '1400226998');
INSERT INTO `lesson_resource` VALUES ('120', '587', '3', 'e10f13e216fc1a63.png', '19', '2', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400229071');
INSERT INTO `lesson_resource` VALUES ('122', '586', '1', '8fceeb1a21fbb88c.png', '19', '10', 'HTML5高级篇-canvas.ppt', '', '1400229330');
INSERT INTO `lesson_resource` VALUES ('138', '0', '0', '', '20', '1', '添加文本', '<p><br /><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong></p><p><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong></strong></p><p><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong></strong></strong></p><p><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong></strong></strong></strong></p><p><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong></strong></strong></strong></strong></p><p><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"></strong></strong></strong></strong></strong></p><p><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong></p><p><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\">添加文本</strong><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"><strong style=\"color:#333333;display:inline-block;vertical-align:middle;font-size:18px;border-left-width:4px;border-left-style:solid;border-left-color:#96A6A6;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;background-color:#FFFFFF;padding-left:8px;\"></strong></strong></strong></strong></strong><br /></p>', '1400296845');
INSERT INTO `lesson_resource` VALUES ('143', '0', '0', '', '18', '0', '123', '<p>123<br /></p><p>1212</p><p>1212</p><p>1212</p>', '1400395240');
INSERT INTO `lesson_resource` VALUES ('154', '587', '3', 'e10f13e216fc1a63.png', '18', '0', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400642177');
INSERT INTO `lesson_resource` VALUES ('155', '0', '0', '', '18', '2', '', '<p>qweqweqwe<br /></p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qwe</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qwe</span></p><p><span style=\"font-size:36px;\">q</span></p><p><span style=\"font-size:36px;\">we</span></p><p><br /></p><p>qwe</p><p>q</p><p>we</p><p><br /></p>', '1400642908');
INSERT INTO `lesson_resource` VALUES ('156', '589', '3', 'e8967cfb2a2c89d4.png', '35', '0', '博盛简报221.doc', '12222', '1400642934');
INSERT INTO `lesson_resource` VALUES ('157', '587', '3', 'e10f13e216fc1a63.png', '35', '1', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400642936');
INSERT INTO `lesson_resource` VALUES ('158', '585', '1', '6b5059371fa85b5c.png', '35', '2', 'js面向对象基础', '', '1400642938');
INSERT INTO `lesson_resource` VALUES ('159', '577', '0', '986717f1cc4f72d0.png', '18', '3', 'jquery ajax注意问题.txt', '', '1400654419');
INSERT INTO `lesson_resource` VALUES ('160', '574', '0', '986717f1cc4f72d0.png', '18', '4', 'jquery ajax注意问题.txt', 'jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。\n最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，\n过程中他问我要用到什么，我也是想都没想...', '1400654422');
INSERT INTO `lesson_resource` VALUES ('161', '0', '0', '', '18', '5', '', '<p><img src=\"http://dev-images.dodoedu.com/jiaocai/537c58b897f95.jpg\" title=\"包子.jpg\" /></p>', '1400658135');
INSERT INTO `lesson_resource` VALUES ('162', '542', '4', '50107ede7c71a7b2.png', '37', '0', '生命安全课', '', '1400123334');
INSERT INTO `lesson_resource` VALUES ('163', '555', '3', 'f0eda57525169184.png', '37', '0', '', '', '1400207975');
INSERT INTO `lesson_resource` VALUES ('164', '542', '4', '50107ede7c71a7b2.png', '37', '1', '生命安全课', '', '1400059784');
INSERT INTO `lesson_resource` VALUES ('165', '538', '3', '74379150ee9211a6.png', '37', '1', '流程图.docx', '基本原则载', '1400059790');
INSERT INTO `lesson_resource` VALUES ('166', '548', '3', '0f9a84ba9e6aecaa.png', '37', '1', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1400059792');
INSERT INTO `lesson_resource` VALUES ('167', '581', '1', 'c9b182f284ec9294.png', '37', '1', '《Go语言编程》.txt', '', '1400226998');
INSERT INTO `lesson_resource` VALUES ('168', '587', '3', 'e10f13e216fc1a63.png', '37', '2', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400229071');
INSERT INTO `lesson_resource` VALUES ('169', '562', '4', '4a7f0db04ab6471a.png', '37', '3', '', '', '1400207967');
INSERT INTO `lesson_resource` VALUES ('170', '548', '3', '0f9a84ba9e6aecaa.png', '37', '5', '教育资源建设技术规范', 'wwew人人', '1399962958');
INSERT INTO `lesson_resource` VALUES ('171', '580', '1', 'c9b182f284ec9294.png', '37', '7', '《Go语言编程》.txt', '', '1400226891');
INSERT INTO `lesson_resource` VALUES ('172', '548', '3', '0f9a84ba9e6aecaa.png', '37', '9', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1399962923');
INSERT INTO `lesson_resource` VALUES ('173', '586', '1', '8fceeb1a21fbb88c.png', '37', '10', 'HTML5高级篇-canvas.ppt', '', '1400229330');
INSERT INTO `lesson_resource` VALUES ('174', '542', '4', '50107ede7c71a7b2.png', '37', '11', '生命安全课', '', '1399890974');
INSERT INTO `lesson_resource` VALUES ('175', '548', '3', '0f9a84ba9e6aecaa.png', '37', '12', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1399952226');
INSERT INTO `lesson_resource` VALUES ('176', '542', '4', '50107ede7c71a7b2.png', '37', '13', '生命安全课题目', '', '1399961903');
INSERT INTO `lesson_resource` VALUES ('177', '548', '3', '0f9a84ba9e6aecaa.png', '37', '24', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人d', '1399890888');
INSERT INTO `lesson_resource` VALUES ('178', '538', '3', '74379150ee9211a6.png', '37', '24', '流程图.docx', '基本原则载', '1400123442');
INSERT INTO `lesson_resource` VALUES ('179', '538', '3', '74379150ee9211a6.png', '37', '24', '流程图.docx', '基本原则载', '1400123463');
INSERT INTO `lesson_resource` VALUES ('180', '590', '0', 'b0219746b4f85e5d.png', '33', '0', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400809475');
INSERT INTO `lesson_resource` VALUES ('181', '566', '3', '0f9a84ba9e6aecaa.png', '33', '1', 'CELTS-41.1 教育资源建设技术规范 信息模型.pdf', '', '1400809477');
INSERT INTO `lesson_resource` VALUES ('182', '0', '0', '', '18', '7', 'qweqw', '', '1400829671');
INSERT INTO `lesson_resource` VALUES ('183', '590', '0', 'b0219746b4f85e5d.png', '18', '8', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400829731');
INSERT INTO `lesson_resource` VALUES ('184', '590', '0', 'b0219746b4f85e5d.png', '18', '9', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400829731');
INSERT INTO `lesson_resource` VALUES ('185', '589', '3', 'e8967cfb2a2c89d4.png', '18', '10', '博盛简报221.doc', '12222', '1400829731');
INSERT INTO `lesson_resource` VALUES ('186', '587', '3', 'e10f13e216fc1a63.png', '18', '11', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', '', '1400829731');
INSERT INTO `lesson_resource` VALUES ('187', '585', '1', '6b5059371fa85b5c.png', '18', '3', 'js面向对象基础', '', '1400829738');
INSERT INTO `lesson_resource` VALUES ('188', '590', '0', 'b0219746b4f85e5d.png', '38', '1', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400835475');
INSERT INTO `lesson_resource` VALUES ('189', '590', '0', 'b0219746b4f85e5d.png', '38', '2', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400835475');
INSERT INTO `lesson_resource` VALUES ('190', '589', '3', 'e8967cfb2a2c89d4.png', '30', '2', '博盛简报221.doc', '12222', '1400839297');
INSERT INTO `lesson_resource` VALUES ('191', '566', '3', '0f9a84ba9e6aecaa.png', '33', '2', 'CELTS-41.1 教育资源建设技术规范 信息模型.pdf', '', '1400895437');
INSERT INTO `lesson_resource` VALUES ('202', '586', '1', '8fceeb1a21fbb88c.png', '18', '0', 'HTML5高级篇-canvas.ppt', '222', '1400910513');
INSERT INTO `lesson_resource` VALUES ('203', '587', '3', 'e10f13e216fc1a63.png', '18', '0', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910642');
INSERT INTO `lesson_resource` VALUES ('204', '586', '1', '8fceeb1a21fbb88c.png', '18', '1', 'HTML5高级篇-canvas.ppt', '222', '1400910683');
INSERT INTO `lesson_resource` VALUES ('205', '587', '3', 'e10f13e216fc1a63.png', '18', '0', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910728');
INSERT INTO `lesson_resource` VALUES ('206', '586', '1', '8fceeb1a21fbb88c.png', '18', '3', 'HTML5高级篇-canvas.ppt', '222', '1400910739');
INSERT INTO `lesson_resource` VALUES ('207', '587', '3', 'e10f13e216fc1a63.png', '18', '3', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910826');
INSERT INTO `lesson_resource` VALUES ('208', '585', '1', '6b5059371fa85b5c.png', '18', '3', 'js面向对象基础', '', '1400910837');
INSERT INTO `lesson_resource` VALUES ('210', '587', '3', 'e10f13e216fc1a63.png', '18', '3', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910856');
INSERT INTO `lesson_resource` VALUES ('211', '585', '1', '6b5059371fa85b5c.png', '18', '3', 'js面向对象基础', '', '1400910894');
INSERT INTO `lesson_resource` VALUES ('212', '0', '0', '', '18', '6', '', '<p><strong><span style=\"font-size:36px;\">asdasd</span></strong></p>', '1400917989');
INSERT INTO `lesson_resource` VALUES ('213', '0', '0', '', '18', '4', 'jquery ajax注意问题.txt', '<p><strong><span style=\"font-size:36px;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想... &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></strong> &nbsp; &nbsp; &nbsp;</p>', '1400919484');
INSERT INTO `lesson_resource` VALUES ('214', '0', '0', '', '18', '4', 'jquery ajax注意问题.txt', '<p><strong><span style=\"font-size:36px;background-color:#FF0000;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想...</span></strong><strong><span style=\"font-size:36px;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></strong></p>', '1400919550');
INSERT INTO `lesson_resource` VALUES ('215', '0', '0', '', '18', '4', 'jquery ajax注意问题.txt', '<p><em><strong><span style=\"font-size:14px;text-decoration:underline;background-color:#FF0000;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想...</span></strong></em><strong><span style=\"font-size:36px;\"></span></strong></p>', '1400919730');
INSERT INTO `lesson_resource` VALUES ('216', '0', '0', '', '18', '4', 'jquery ajax注意问题2.txt', '<p><em><strong><span style=\"font-size:14px;text-decoration:underline;background-color:#FF0000;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想...</span></strong></em><strong><span style=\"font-size:36px;\"></span></strong></p>', '1400919740');
INSERT INTO `lesson_resource` VALUES ('217', '588', '3', 'e10f13e216fc1a63.png', '36', '0', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', '', '1400988628');
INSERT INTO `lesson_resource` VALUES ('218', '590', '0', 'b0219746b4f85e5d.png', '35', '3', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400988788');
INSERT INTO `lesson_resource` VALUES ('220', '0', '0', '', '33', '0', '3.13他人眼中的我-样课截取2.flv', '<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p><p><br /></p><p><br /></p><p><span style=\"font-size:36px;\"> wewe<span style=\"font-size:36px;color:#C00000;\">wew</span>ewew &nbsp; </span></p><p><span style=\"font-size:36px;\"><br /></span></p><p><span style=\"font-size:36px;\"><br /></span></p><p><span style=\"font-size:36px;\"><span style=\"font-size:14px;\"> </span><span style=\"font-size:14px;\"> &nbsp; &nbsp; &nbsp;wewe</span><span style=\"font-size:14px;color:#C00000;\">wew</span><span style=\"font-size:14px;\">ewew &nbsp; </span> &nbsp; </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>', '1401004634');
INSERT INTO `lesson_resource` VALUES ('223', '604', '3', '96272a719ccd575e.png', '33', '4', '2013年湖北博盛数字教育服务有限公司部门及负责人年度考核成绩.xlsx', '', '1401005550');
INSERT INTO `lesson_resource` VALUES ('224', '590', '0', 'b0219746b4f85e5d.png', '36', '1', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1401006818');
INSERT INTO `lesson_resource` VALUES ('226', '582', '0', '8fceeb1a21fbb88c.png', '36', '2', 'HTML5高级篇-canvas.ppt', 'HTML5 移动开发\n——\nCanvas 篇\n\n内容大纲\n1.\n\ncanvas 简介\n\n2.\n\ncanvas 基础应用\n\n3.\n\ncanvas 动画\n\n1 、 canvas 简介\n\n\ncanvas 元素本身没有绘图能力，所有的绘制工作必须通过\nJavaScript 实现。\n\n\n\n画布是一个矩形区域，可以控制其每一像素。\n\n\n\n...', '1401075552');
INSERT INTO `lesson_resource` VALUES ('227', '542', '0', '50107ede7c71a7b2.png', '36', '3', '生命安全课', '', '1401075558');
INSERT INTO `lesson_resource` VALUES ('228', '602', '3', '96272a719ccd575e.png', '41', '0', '2013年湖北博盛数字教育服务有限公司部门及负责人年度考核成绩.xlsx', '湖北博盛数字教育服务有限公司部门及负责人年度考核\n2013年\n部门考核分数\n序号\n\n部门\n\n年度考核分数\n\n1\n\n财务部\n\n93.33\n\n2\n\n技术部\n\n91.84\n\n3\n\n教学服务部\n\n90.08\n\n4\n\n市场部\n\n87.23\n\n5\n\n产品策划部\n\n86.91\n\n6\n\n综合办公室\n\n84.63\n\n部门负责人考...', '1401087148');
INSERT INTO `lesson_resource` VALUES ('229', '605', '3', '', '36', '4', 'peepcode-git.pdf', '', '1401089803');
INSERT INTO `lesson_resource` VALUES ('231', '599', '0', '', '46', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('232', '542', '4', '50107ede7c71a7b2.png', '47', '0', '生命安全课', '', '1400123334');
INSERT INTO `lesson_resource` VALUES ('233', '555', '3', 'f0eda57525169184.png', '47', '4', '', '', '1400207975');
INSERT INTO `lesson_resource` VALUES ('234', '542', '4', '50107ede7c71a7b2.png', '47', '1', '生命安全课', '', '1400059784');
INSERT INTO `lesson_resource` VALUES ('235', '538', '3', '74379150ee9211a6.png', '47', '1', '流程图.docx', '基本原则载', '1400059790');
INSERT INTO `lesson_resource` VALUES ('236', '548', '3', '0f9a84ba9e6aecaa.png', '47', '1', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1400059792');
INSERT INTO `lesson_resource` VALUES ('237', '581', '1', 'c9b182f284ec9294.png', '47', '6', '《Go语言编程》.txt', '', '1400226998');
INSERT INTO `lesson_resource` VALUES ('238', '587', '3', 'e10f13e216fc1a63.png', '47', '5', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400229071');
INSERT INTO `lesson_resource` VALUES ('239', '562', '4', '4a7f0db04ab6471a.png', '47', '1', '', '', '1400207967');
INSERT INTO `lesson_resource` VALUES ('241', '580', '1', 'c9b182f284ec9294.png', '47', '3', '《Go语言编程》.txt', '', '1400226891');
INSERT INTO `lesson_resource` VALUES ('243', '586', '1', '8fceeb1a21fbb88c.png', '47', '8', 'HTML5高级篇-canvas.ppt', '', '1400229330');
INSERT INTO `lesson_resource` VALUES ('244', '542', '4', '50107ede7c71a7b2.png', '47', '7', '生命安全课', '', '1399890974');
INSERT INTO `lesson_resource` VALUES ('245', '548', '3', '0f9a84ba9e6aecaa.png', '47', '9', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人', '1399952226');
INSERT INTO `lesson_resource` VALUES ('246', '542', '4', '50107ede7c71a7b2.png', '47', '10', '生命安全课题目', '', '1399961903');
INSERT INTO `lesson_resource` VALUES ('247', '548', '3', '0f9a84ba9e6aecaa.png', '47', '24', 'CELTS-41.1 教育资源建设技术规范 信息模型', 'wwew人人d', '1399890888');
INSERT INTO `lesson_resource` VALUES ('248', '538', '3', '74379150ee9211a6.png', '47', '24', '流程图.docx', '基本原则载', '1400123442');
INSERT INTO `lesson_resource` VALUES ('249', '538', '3', '74379150ee9211a6.png', '47', '24', '流程图.docx', '基本原则载', '1400123463');
INSERT INTO `lesson_resource` VALUES ('252', '0', '0', '', '46', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('253', '582', '1', '8fceeb1a21fbb88c.png', '49', '0', 'HTML5高级篇-canvas.ppt', 'HTML5 移动开发\n——\nCanvas 篇\n\n内容大纲\n1.\n\ncanvas 简介\n\n2.\n\ncanvas 基础应用\n\n3.\n\ncanvas 动画\n\n1 、 canvas 简介\n\n\ncanvas 元素本身没有绘图能力，所有的绘制工作必须通过\nJavaScript 实现。\n\n\n\n画布是一个矩形区域，可以控制其每一像素。\n\n\n\n...', '1401182802');
INSERT INTO `lesson_resource` VALUES ('254', '0', '0', '', '49', '1', '程序开发人员必学5大技能', '<p>程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能</p>', '1401182915');
INSERT INTO `lesson_resource` VALUES ('255', '572', '1', '5922c5e0896a5bf6.png', '49', '2', '多多教育社区(2014年整体介绍版).pptx', '多多教育社区\n\n湖北省中小学实名制教育主题社区\n\n湖北省教育信息化发展中心\n长江出版传媒股份有限公司\n2014 年 3 月\n1\n\n一、多多社区整体介绍\n二、多多社区用户核心价值\n三、多多社区核心功能\n四、多多社区核心产品\n五、多....', '1401182922');
INSERT INTO `lesson_resource` VALUES ('256', '582', '1', '8fceeb1a21fbb88c.png', '50', '0', 'HTML5高级篇-canvas.ppt', 'HTML5 移动开发\n——\nCanvas 篇\n\n内容大纲\n1.\n\ncanvas 简介\n\n2.\n\ncanvas 基础应用\n\n3.\n\ncanvas 动画\n\n1 、 canvas 简介\n\n\ncanvas 元素本身没有绘图能力，所有的绘制工作必须通过\nJavaScript 实现。\n\n\n\n画布是一个矩形区域，可以控制其每一像素。\n\n\n\n...', '1401182802');
INSERT INTO `lesson_resource` VALUES ('257', '0', '0', '', '50', '1', '程序开发人员必学5大技能', '<p>程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能</p>', '1401182915');
INSERT INTO `lesson_resource` VALUES ('258', '572', '1', '5922c5e0896a5bf6.png', '50', '2', '多多教育社区(2014年整体介绍版).pptx', '多多教育社区\n\n湖北省中小学实名制教育主题社区\n\n湖北省教育信息化发展中心\n长江出版传媒股份有限公司\n2014 年 3 月\n1\n\n一、多多社区整体介绍\n二、多多社区用户核心价值\n三、多多社区核心功能\n四、多多社区核心产品\n五、多....', '1401182922');
INSERT INTO `lesson_resource` VALUES ('259', '589', '3', 'e8967cfb2a2c89d4.png', '51', '0', '博盛简报221.doc', '12222', '1401245137');
INSERT INTO `lesson_resource` VALUES ('260', '586', '1', '8fceeb1a21fbb88c.png', '51', '1', 'HTML5高级篇-canvas.ppt', '222', '1401245141');
INSERT INTO `lesson_resource` VALUES ('261', '542', '4', '50107ede7c71a7b2.png', '51', '4', '生命安全课', '', '1401245148');
INSERT INTO `lesson_resource` VALUES ('262', '607', '3', '153c674c6322ee8d.png', '51', '3', 'PHP 编码规范.docx', '24234', '1401245186');
INSERT INTO `lesson_resource` VALUES ('263', '0', '0', '', '51', '2', '', '<p>adfsaf<img src=\"http://dev-images.dodoedu.com/jiaocai/53854e16c043b.png\" title=\"QQ截图20140510115953.png\" /></p>', '1401245217');
INSERT INTO `lesson_resource` VALUES ('265', '582', '1', '8fceeb1a21fbb88c.png', '52', '0', 'HTML5高级篇-canvas.ppt', 'HTML5 移动开发\n——\nCanvas 篇\n\n内容大纲\n1.\n\ncanvas 简介\n\n2.\n\ncanvas 基础应用\n\n3.\n\ncanvas 动画\n\n1 、 canvas 简介\n\n\ncanvas 元素本身没有绘图能力，所有的绘制工作必须通过\nJavaScript 实现。\n\n\n\n画布是一个矩形区域，可以控制其每一像素。\n\n\n\n...', '1401182802');
INSERT INTO `lesson_resource` VALUES ('266', '0', '0', '', '52', '1', '程序开发人员必学5大技能', '<p>程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能</p>', '1401182915');
INSERT INTO `lesson_resource` VALUES ('267', '572', '1', '5922c5e0896a5bf6.png', '52', '2', '多多教育社区(2014年整体介绍版).pptx', '多多教育社区\n\n湖北省中小学实名制教育主题社区\n\n湖北省教育信息化发展中心\n长江出版传媒股份有限公司\n2014 年 3 月\n1\n\n一、多多社区整体介绍\n二、多多社区用户核心价值\n三、多多社区核心功能\n四、多多社区核心产品\n五、多....', '1401182922');
INSERT INTO `lesson_resource` VALUES ('272', '0', '0', '', '51', '6', '身心健康', '<p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><div><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\"><br /></span></div><p><br /></p>', '1401257885');
INSERT INTO `lesson_resource` VALUES ('274', '0', '0', '', '53', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('275', '599', '0', '', '53', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('276', '0', '0', '', '51', '7', '身心健康2', '<p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:left;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;font-size:12px;word-wrap:break-word;line-height:21px;text-align:center;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><div style=\"text-align:center;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\"><br /></span></div><p><br /></p>', '1401259378');
INSERT INTO `lesson_resource` VALUES ('277', '0', '0', '', '51', '8', '小学一年级第一章程序开发 lesson1', '', '1401260418');
INSERT INTO `lesson_resource` VALUES ('279', '606', '3', 'b0a3060cf159cc2b.png', '53', '2', 'peepcode-git.pdf', '', '1401334257');
INSERT INTO `lesson_resource` VALUES ('280', '0', '0', '', '54', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('281', '599', '0', '', '54', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('282', '0', '0', '', '56', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('283', '599', '0', '', '56', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('284', '606', '3', 'b0a3060cf159cc2b.png', '56', '2', 'peepcode-git.pdf', '', '1401334257');
INSERT INTO `lesson_resource` VALUES ('286', '0', '0', '', '51', '5', '小学一年级第一章程序开发 lesson1', '', '1401358429');
INSERT INTO `lesson_resource` VALUES ('287', '590', '0', 'b0219746b4f85e5d.png', '55', '0', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1401419435');
INSERT INTO `lesson_resource` VALUES ('288', '542', '4', '50107ede7c71a7b2.png', '55', '1', '生命安全课', '', '1401419439');
INSERT INTO `lesson_resource` VALUES ('291', '0', '0', '', '57', '0', '', '<p style=\"text-align:center;\"><br /></p><p style=\"text-align:center;\"><br /></p><p style=\"text-align:center;\"><br /></p><p style=\"text-align:center;\"><img id=\"equationview\" name=\"equationview\" src=\"http://latex.codecogs.com/gif.latex?\\frac{3}{4} \\sum_{a}^{b}{x} \" width=\"120\" height=\"120\" border=\"0\" hspace=\"0\" vspace=\"0\" style=\"width:120px;height:120px;\" /></p>', '1401437694');
INSERT INTO `lesson_resource` VALUES ('292', '590', '0', 'b0219746b4f85e5d.png', '58', '0', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400809475');
INSERT INTO `lesson_resource` VALUES ('293', '0', '0', '', '58', '0', '3.13他人眼中的我-样课截取2.flv', '<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p><p><br /></p><p><br /></p><p><span style=\"font-size:36px;\"> wewe<span style=\"font-size:36px;color:#C00000;\">wew</span>ewew &nbsp; </span></p><p><span style=\"font-size:36px;\"><br /></span></p><p><span style=\"font-size:36px;\"><br /></span></p><p><span style=\"font-size:36px;\"><span style=\"font-size:14px;\"> </span><span style=\"font-size:14px;\"> &nbsp; &nbsp; &nbsp;wewe</span><span style=\"font-size:14px;color:#C00000;\">wew</span><span style=\"font-size:14px;\">ewew &nbsp; </span> &nbsp; </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>', '1401004634');
INSERT INTO `lesson_resource` VALUES ('294', '566', '3', '0f9a84ba9e6aecaa.png', '58', '1', 'CELTS-41.1 教育资源建设技术规范 信息模型.pdf', '', '1400809477');
INSERT INTO `lesson_resource` VALUES ('295', '566', '3', '0f9a84ba9e6aecaa.png', '58', '2', 'CELTS-41.1 教育资源建设技术规范 信息模型.pdf', '', '1400895437');
INSERT INTO `lesson_resource` VALUES ('296', '604', '3', '96272a719ccd575e.png', '58', '4', '2013年湖北博盛数字教育服务有限公司部门及负责人年度考核成绩.xlsx', '', '1401005550');
INSERT INTO `lesson_resource` VALUES ('297', '0', '0', '', '59', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('298', '599', '0', '', '59', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('299', '606', '3', 'b0a3060cf159cc2b.png', '59', '2', 'peepcode-git.pdf', '', '1401334257');
INSERT INTO `lesson_resource` VALUES ('300', '0', '0', '', '60', '0', '123', '<p>123<br /></p><p>1212</p><p>1212</p><p>1212</p>', '1400395240');
INSERT INTO `lesson_resource` VALUES ('301', '587', '3', 'e10f13e216fc1a63.png', '60', '0', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400642177');
INSERT INTO `lesson_resource` VALUES ('302', '586', '1', '8fceeb1a21fbb88c.png', '60', '0', 'HTML5高级篇-canvas.ppt', '222', '1400910513');
INSERT INTO `lesson_resource` VALUES ('303', '587', '3', 'e10f13e216fc1a63.png', '60', '0', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910642');
INSERT INTO `lesson_resource` VALUES ('304', '587', '3', 'e10f13e216fc1a63.png', '60', '0', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910728');
INSERT INTO `lesson_resource` VALUES ('305', '586', '1', '8fceeb1a21fbb88c.png', '60', '1', 'HTML5高级篇-canvas.ppt', '222', '1400910683');
INSERT INTO `lesson_resource` VALUES ('306', '0', '0', '', '60', '2', '', '<p>qweqweqwe<br /></p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qw</p><p>e</p><p>qwe</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p>q</p><p>we</p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qw</span></p><p><span style=\"font-size:36px;\">e</span></p><p><span style=\"font-size:36px;\">qwe</span></p><p><span style=\"font-size:36px;\">q</span></p><p><span style=\"font-size:36px;\">we</span></p><p><br /></p><p>qwe</p><p>q</p><p>we</p><p><br /></p>', '1400642908');
INSERT INTO `lesson_resource` VALUES ('307', '577', '0', '986717f1cc4f72d0.png', '60', '3', 'jquery ajax注意问题.txt', '', '1400654419');
INSERT INTO `lesson_resource` VALUES ('308', '585', '1', '6b5059371fa85b5c.png', '60', '3', 'js面向对象基础', '', '1400829738');
INSERT INTO `lesson_resource` VALUES ('309', '586', '1', '8fceeb1a21fbb88c.png', '60', '3', 'HTML5高级篇-canvas.ppt', '222', '1400910739');
INSERT INTO `lesson_resource` VALUES ('310', '587', '3', 'e10f13e216fc1a63.png', '60', '3', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910826');
INSERT INTO `lesson_resource` VALUES ('311', '585', '1', '6b5059371fa85b5c.png', '60', '3', 'js面向对象基础', '', '1400910837');
INSERT INTO `lesson_resource` VALUES ('312', '587', '3', 'e10f13e216fc1a63.png', '60', '3', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', 'Flexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n第1页\n\nFlexpaper 二次开发入门教程\n\najava.org 独立 java 社区发布\n\n目录\n\n前言.......................................................................................................................................', '1400910856');
INSERT INTO `lesson_resource` VALUES ('313', '585', '1', '6b5059371fa85b5c.png', '60', '3', 'js面向对象基础', '', '1400910894');
INSERT INTO `lesson_resource` VALUES ('314', '574', '0', '986717f1cc4f72d0.png', '60', '4', 'jquery ajax注意问题.txt', 'jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。\n最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，\n过程中他问我要用到什么，我也是想都没想...', '1400654422');
INSERT INTO `lesson_resource` VALUES ('315', '0', '0', '', '60', '4', 'jquery ajax注意问题.txt', '<p><strong><span style=\"font-size:36px;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想... &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></strong> &nbsp; &nbsp; &nbsp;</p>', '1400919484');
INSERT INTO `lesson_resource` VALUES ('316', '0', '0', '', '60', '4', 'jquery ajax注意问题.txt', '<p><strong><span style=\"font-size:36px;background-color:#FF0000;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想...</span></strong><strong><span style=\"font-size:36px;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></strong></p>', '1400919550');
INSERT INTO `lesson_resource` VALUES ('317', '0', '0', '', '60', '4', 'jquery ajax注意问题.txt', '<p><em><strong><span style=\"font-size:14px;text-decoration:underline;background-color:#FF0000;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想...</span></strong></em><strong><span style=\"font-size:36px;\"></span></strong></p>', '1400919730');
INSERT INTO `lesson_resource` VALUES ('318', '0', '0', '', '60', '4', 'jquery ajax注意问题2.txt', '<p><em><strong><span style=\"font-size:14px;text-decoration:underline;background-color:#FF0000;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;jQuery 最强大的亮点莫过于它的 CSS3 selector 和极其简单的 Ajax 请求调用。最近一哥们在做一个 Ajax 长连接的项目，页面需要和服务器保持长连接，而且在连接超时后需要重新请求连接，过程中他问我要用到什么，我也是想都没想...</span></strong></em><strong><span style=\"font-size:36px;\"></span></strong></p>', '1400919740');
INSERT INTO `lesson_resource` VALUES ('319', '0', '0', '', '60', '5', '', '<p><img src=\"http://dev-images.dodoedu.com/jiaocai/537c58b897f95.jpg\" title=\"包子.jpg\" /></p>', '1400658135');
INSERT INTO `lesson_resource` VALUES ('320', '0', '0', '', '60', '6', '', '<p><strong><span style=\"font-size:36px;\">asdasd</span></strong></p>', '1400917989');
INSERT INTO `lesson_resource` VALUES ('321', '0', '0', '', '60', '7', 'qweqw', '', '1400829671');
INSERT INTO `lesson_resource` VALUES ('322', '590', '0', 'b0219746b4f85e5d.png', '60', '8', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400829731');
INSERT INTO `lesson_resource` VALUES ('323', '590', '0', 'b0219746b4f85e5d.png', '60', '9', '3.13他人眼中的我-样课截取2.flv', 'wewewewewew', '1400829731');
INSERT INTO `lesson_resource` VALUES ('324', '589', '3', 'e8967cfb2a2c89d4.png', '60', '10', '博盛简报221.doc', '12222', '1400829731');
INSERT INTO `lesson_resource` VALUES ('325', '587', '3', 'e10f13e216fc1a63.png', '60', '11', 'Flexpaper二次开发入门教程(ajava.org发布).pdf', '', '1400829731');
INSERT INTO `lesson_resource` VALUES ('326', '0', '0', '', '5', '13', '1', '<p>1<br /></p>', '1401776832');
INSERT INTO `lesson_resource` VALUES ('327', '628', '1', '5922c5e0896a5bf6.png', '55', '2', '多多教育社区(2014年整体介绍版).pptx', '', '1401853372');
INSERT INTO `lesson_resource` VALUES ('328', '582', '1', '8fceeb1a21fbb88c.png', '62', '0', 'HTML5高级篇-canvas.ppt', 'HTML5 移动开发\n——\nCanvas 篇\n\n内容大纲\n1.\n\ncanvas 简介\n\n2.\n\ncanvas 基础应用\n\n3.\n\ncanvas 动画\n\n1 、 canvas 简介\n\n\ncanvas 元素本身没有绘图能力，所有的绘制工作必须通过\nJavaScript 实现。\n\n\n\n画布是一个矩形区域，可以控制其每一像素。\n\n\n\n...', '1401182802');
INSERT INTO `lesson_resource` VALUES ('329', '0', '0', '', '62', '1', '程序开发人员必学5大技能', '<p>程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能程序开发人员必学5大技能</p>', '1401182915');
INSERT INTO `lesson_resource` VALUES ('330', '572', '1', '5922c5e0896a5bf6.png', '62', '2', '多多教育社区(2014年整体介绍版).pptx', '多多教育社区\n\n湖北省中小学实名制教育主题社区\n\n湖北省教育信息化发展中心\n长江出版传媒股份有限公司\n2014 年 3 月\n1\n\n一、多多社区整体介绍\n二、多多社区用户核心价值\n三、多多社区核心功能\n四、多多社区核心产品\n五、多....', '1401182922');
INSERT INTO `lesson_resource` VALUES ('331', '0', '0', '', '63', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('332', '599', '0', '', '63', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('333', '606', '3', 'b0a3060cf159cc2b.png', '63', '2', 'peepcode-git.pdf', '', '1401334257');
INSERT INTO `lesson_resource` VALUES ('334', '0', '0', '', '71', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('335', '599', '0', '', '71', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('336', '0', '0', '', '77', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('337', '599', '0', '', '77', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('338', '0', '0', '', '78', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('339', '599', '0', '', '78', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('340', '0', '0', '', '79', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('341', '599', '0', '', '79', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('342', '606', '3', 'b0a3060cf159cc2b.png', '79', '2', 'peepcode-git.pdf', '', '1401334257');
INSERT INTO `lesson_resource` VALUES ('343', '0', '0', '', '89', '0', '传播正能量啊！', '<p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;color:#C00000;\">花与人的身心健康</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">花是精神文明的标志。人们在庭前、阳台上种花，使生活充满生 机。在工作与学习环境布置白色花卉，使人有高雅之感。在书房与客厅放置红、黄花卉，使人产生热情、友好之感。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">“室雅何须大，花香不在多。”室内摆花卉不宜多。夜丁香也不宜放在屋内，因为它的香气过浓，久闻可引起头晕。《群芳谱》载：“腊梅人多爱其香，可远闻不可近嗅，嗅之头痛，屡试不爽。”紫罗兰、晚香玉、含羞草、鸢鸢尾、紫丁香等的花香伤害嗓音，有时甚至可暂时使嗓子变哑。它的机理在于使喉头充血和不完全麻痹。因此，有经验的歌唱家，往往在登台表演之前，不亲自接赠授的花朵。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天等于做脑保健操</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天可以使人拥有健康的心理，著名心理学家默里认为，人类至少有20种心理需要，通过聊天可以满足部分心理需要，使精神愉悦。医学专家认为，人老首先是脑细胞和脑容量减少，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">聊天有助于刺激脑神经的生长，增进思维和语言表达的逻辑性、敏锐性和准确性，经常聊天，等于在做脑保健操。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚间的心灵鸡汤</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">一天的快乐，不仅在于如何开始、如何持续，也在于如何结束。带着好心情结束一天，</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">不但可以有助于睡眠，也可以为第二天的愉快心情做好铺垫。</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><strong><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">可爱的苹果</span></strong></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">晚餐最好以清淡为宜。苹果是一定要吃的，这不仅仅是因为它含 有多种维生素、纤维，容易被消化，同时也因为苹果的香味可带给人好心情。国外医学界研究得出新结论：在日常生活中，多闻苹果香味可以解忧郁。科研人员曾做过多次实验，发现在众多气味中，苹果香</span></p><p style=\"padding:0px 0px 15px;word-wrap:break-word;font-family:Arial, Helvetica, &#39;Microsoft YaHei&#39;, simsun;line-height:21px;background-color:#FFFFFF;margin-top:0px;margin-bottom:0px;\"><span style=\"line-height:24px;font-family:微软雅黑, &#39;Microsoft YaHei&#39;;font-size:16px;\">对人心理的影响是最大的，它具有明显的消除压抑感的作用。</span></p><p><br /></p>', '1401181904');
INSERT INTO `lesson_resource` VALUES ('344', '599', '0', '', '89', '1', '多多学校的功能与特点.txt', '', '1401172488');
INSERT INTO `lesson_resource` VALUES ('345', '606', '3', 'b0a3060cf159cc2b.png', '89', '2', 'peepcode-git.pdf', '', '1401334257');
INSERT INTO `lesson_resource` VALUES ('351', '0', '0', '', '69', '4', 'u-boart', '<p><img src=\"http://dev-images.dodoedu.com/jiaocai/53758007c4135.jpg\" /></p>', '1402623938');
INSERT INTO `lesson_resource` VALUES ('352', '0', '0', '', '47', '0', '', '<p><span style=\"font-size:36px;\">dajiahao </span></p>', '1402628253');
INSERT INTO `lesson_resource` VALUES ('353', '652', '3', '0ca987cd03b81b0e.png', '47', '2', '【电子课本】第十一课 我的情绪标签', '我 的 情 绪 标 签\n观察岛\n\n\n\n请回忆一下，有哪些事曾令你欣喜若狂？又有哪些事\n\n曾让你伤心懊恼？\n\n想一想，参加重大比赛的运动员，他们在赛场上会有\n\n哪些情绪变化？\n\n\n\n活动营\n\n活动一\n\n搭高塔\n\n在搭高塔的比\n赛中，你的....', '1402628270');
INSERT INTO `lesson_resource` VALUES ('354', '0', '0', '', '106', '0', '[多图]传谷歌将推新设计语言 统一Android界面', '<p><span style=\"font-weight:700;font-size:16px;color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;line-height:24px;background-color:#FBFBFB;\">据科技博客Android Police报道，谷歌年度开发者大会将于6月25日举行，该搜索巨头正在计划统一或整合Android应用和服务的设计元素，推出名为“量子纸”(Quantum Paper)的设计语言。</span><span style=\"color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;font-size:16px;line-height:24px;background-color:#FBFBFB;\">消息称，谷歌正在着手开发一款经过完全重新设计的“L”版Android系统，革新程度堪比苹果iOS 7所带来的巨大变化，谷歌计划统一或整合Android应用和服务的设计元素。值得指出的是，为了提取最好的元素，Android应用和服务必须遵循类似 的设计，以达到一致和无缝的表现。</span></p>', '1402882846');
INSERT INTO `lesson_resource` VALUES ('355', '0', '0', '', '109', '0', '[多图]传谷歌将推新设计语言 统一Android界面', '<p><span style=\"font-weight:700;font-size:16px;color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;line-height:24px;background-color:#FBFBFB;\">据科技博客Android Police报道，谷歌年度开发者大会将于6月25日举行，该搜索巨头正在计划统一或整合Android应用和服务的设计元素，推出名为“量子纸”(Quantum Paper)的设计语言。</span><span style=\"color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;font-size:16px;line-height:24px;background-color:#FBFBFB;\">消息称，谷歌正在着手开发一款经过完全重新设计的“L”版Android系统，革新程度堪比苹果iOS 7所带来的巨大变化，谷歌计划统一或整合Android应用和服务的设计元素。值得指出的是，为了提取最好的元素，Android应用和服务必须遵循类似 的设计，以达到一致和无缝的表现。</span></p>', '1402882846');
INSERT INTO `lesson_resource` VALUES ('356', '0', '0', '', '106', '7', '', '<p>asdfasdfasdf</p>', '1402888501');
INSERT INTO `lesson_resource` VALUES ('357', '645', '0', 'd1c30bc8f1d69377.png', '106', '1', 'wwwDialog备份3', '', '1402907444');
INSERT INTO `lesson_resource` VALUES ('358', '0', '0', '', '106', '2', '', '<p><img src=\"http://dev-images.dodoedu.com/jiaocai/539eae1331bde.png\" title=\"QQ截图20140510115953.png\" /></p>', '1402908183');
INSERT INTO `lesson_resource` VALUES ('359', '663', '4', '', '106', '3', '名词单数变复数', '名词单数变复数', '1402908192');
INSERT INTO `lesson_resource` VALUES ('365', '663', '4', '', '113', '2', '名词单数变复数', '名词单数变复数', '1403139325');
INSERT INTO `lesson_resource` VALUES ('371', '0', '0', '', '117', '0', '[多图]传谷歌将推新设计语言 统一Android界面', '<p><span style=\"font-weight:700;font-size:16px;color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;line-height:24px;background-color:#FBFBFB;\">据科技博客Android Police报道，谷歌年度开发者大会将于6月25日举行，该搜索巨头正在计划统一或整合Android应用和服务的设计元素，推出名为“量子纸”(Quantum Paper)的设计语言。</span><span style=\"color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;font-size:16px;line-height:24px;background-color:#FBFBFB;\">消息称，谷歌正在着手开发一款经过完全重新设计的“L”版Android系统，革新程度堪比苹果iOS 7所带来的巨大变化，谷歌计划统一或整合Android应用和服务的设计元素。值得指出的是，为了提取最好的元素，Android应用和服务必须遵循类似 的设计，以达到一致和无缝的表现。</span></p>', '1402882846');
INSERT INTO `lesson_resource` VALUES ('372', '645', '0', 'd1c30bc8f1d69377.png', '117', '1', 'wwwDialog备份3', '', '1402907444');
INSERT INTO `lesson_resource` VALUES ('373', '0', '0', '', '117', '2', '', '<p><img src=\"http://dev-images.dodoedu.com/jiaocai/539eae1331bde.png\" title=\"QQ截图20140510115953.png\" /></p>', '1402908183');
INSERT INTO `lesson_resource` VALUES ('374', '663', '4', '', '117', '3', '名词单数变复数', '名词单数变复数', '1402908192');
INSERT INTO `lesson_resource` VALUES ('375', '0', '0', '', '117', '7', '', '<p>asdfasdfasdf</p>', '1402888501');
INSERT INTO `lesson_resource` VALUES ('378', '656', '1', '8fceeb1a21fbb88c.png', '80', '1', 'HTML5高级篇-canvas', '', '1403161351');
INSERT INTO `lesson_resource` VALUES ('379', '660', '3', 'de0b74f8e4d22425.png', '115', '0', 'HTML5移动Web开发指南', '...', '1403162247');
INSERT INTO `lesson_resource` VALUES ('380', '656', '1', '8fceeb1a21fbb88c.png', '115', '1', 'HTML5高级篇-canvas', '', '1403162249');
INSERT INTO `lesson_resource` VALUES ('392', '672', '3', '', '113', '4', 'test.pdf', '第一单元\n凿佻\n\n赠侃\n\n噪侉\n\n第一课\n\n憎侬 泽澡伽灶倮 曾怎佴 憎侬 躁蚤佟燥\n\n伽燥\n\n我上学我骄傲\n观察岛\n\n老师好！\n\n2\n\n心理健康教育\n\n憎侬\n\n泽澡佻\n\n赠佻 皂侏灶倮 曾蚤伲燥 曾怎佴 泽澡佶灶倮 造倩\n\n我 是 一 名 小 学 生 啦 ！\n...', '1403169019');
INSERT INTO `lesson_resource` VALUES ('394', '672', '3', '', '113', '1', 'test.pdf', '第一单元\n凿佻\n\n赠侃\n\n噪侉\n\n第一课\n\n憎侬 泽澡伽灶倮 曾怎佴 憎侬 躁蚤佟燥\n\n伽燥\n\n我上学我骄傲\n观察岛\n\n老师好！\n\n2\n\n心理健康教育\n\n憎侬\n\n泽澡佻\n\n赠佻 皂侏灶倮 曾蚤伲燥 曾怎佴 泽澡佶灶倮 造倩\n\n我 是 一 名 小 学 生 啦 ！\n...', '1403169116');
INSERT INTO `lesson_resource` VALUES ('397', '674', '3', 'e8967cfb2a2c89d4.png', '113', '5', '博盛简报', '', '1403169392');
INSERT INTO `lesson_resource` VALUES ('398', '672', '3', '', '113', '0', 'test.pdf', '第一单元\n凿佻\n\n赠侃\n\n噪侉\n\n第一课\n\n憎侬 泽澡伽灶倮 曾怎佴 憎侬 躁蚤佟燥\n\n伽燥\n\n我上学我骄傲\n观察岛\n\n老师好！\n\n2\n\n心理健康教育\n\n憎侬\n\n泽澡佻\n\n赠佻 皂侏灶倮 曾蚤伲燥 曾怎佴 泽澡佶灶倮 造倩\n\n我 是 一 名 小 学 生 啦 ！\n...', '1403169501');
INSERT INTO `lesson_resource` VALUES ('399', '0', '0', '', '113', '3', '123', '<p>123123<br /></p>', '1403169608');
INSERT INTO `lesson_resource` VALUES ('405', '0', '0', '', '119', '0', '[多图]传谷歌将推新设计语言 统一Android界面', '<p><span style=\"font-weight:700;font-size:16px;color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;line-height:24px;background-color:#FBFBFB;\">据科技博客Android Police报道，谷歌年度开发者大会将于6月25日举行，该搜索巨头正在计划统一或整合Android应用和服务的设计元素，推出名为“量子纸”(Quantum Paper)的设计语言。</span><span style=\"color:#434343;font-family:微软雅黑, Tahoma, Verdana, 宋体;font-size:16px;line-height:24px;background-color:#FBFBFB;\">消息称，谷歌正在着手开发一款经过完全重新设计的“L”版Android系统，革新程度堪比苹果iOS 7所带来的巨大变化，谷歌计划统一或整合Android应用和服务的设计元素。值得指出的是，为了提取最好的元素，Android应用和服务必须遵循类似 的设计，以达到一致和无缝的表现。</span></p>', '1402882846');
INSERT INTO `lesson_resource` VALUES ('406', '645', '0', 'd1c30bc8f1d69377.png', '119', '1', 'wwwDialog备份3', '', '1402907444');
INSERT INTO `lesson_resource` VALUES ('407', '0', '0', '', '119', '2', '', '<p><img src=\"http://dev-images.dodoedu.com/jiaocai/539eae1331bde.png\" title=\"QQ截图20140510115953.png\" /></p>', '1402908183');
INSERT INTO `lesson_resource` VALUES ('408', '663', '4', '', '119', '3', '名词单数变复数', '名词单数变复数', '1402908192');
INSERT INTO `lesson_resource` VALUES ('409', '0', '0', '', '119', '7', '', '<p>asdfasdfasdf</p>', '1402888501');
INSERT INTO `lesson_resource` VALUES ('410', '0', '0', '', '38', '0', '', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\">1212122</span></h1><p style=\"text-align:center;\"><strong>121212</strong></p><h3><span style=\"font-family:幼圆\">12121</span></h3><p style=\"text-indent:2em;\">2121212</p><h3><span style=\"font-family:幼圆\">121212</span></h3><p style=\"text-indent:2em;\">121212<span style=\"text-indent:2em;\"></span></p><h3><span class=\"ue_t\" style=\"font-family:幼圆;\">[标题 3]</span></h3><p class=\"ue_t\" style=\"text-indent:2em;\">对于“插入”选项卡上的库，在设计时都充分考虑了其中的项与文档整体外观的协调性。 您可以使用这些库来插入表格、页眉、页脚、列表、封面以及其他文档构可以方便地更改文档中所选文本的格式。 您还可以使用“开始”选项卡上的其他控件建基块。 您创建的图片、图表或关系图也将与当前的文档外观协调一致。</p><p class=\"ue_t\"><br /></p><p><br /></p>', '1403511083');
INSERT INTO `lesson_resource` VALUES ('413', '0', '0', '', '113', '7', '[键入文档标题]', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">234234</span></h1><p><br /></p>', '1403573964');
INSERT INTO `lesson_resource` VALUES ('415', '0', '0', '', '113', '6', '', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">堵塞是天赋</span></h1><p><br /></p>', '1403574335');
INSERT INTO `lesson_resource` VALUES ('418', '0', '0', '', '113', '9', '', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">asdfasdfsdf</span></h1><p><br /></p>', '1403575578');
INSERT INTO `lesson_resource` VALUES ('419', '0', '0', '', '113', '10', '', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">asdfasdfasdf</span></h1><p><br /></p>', '1403575740');
INSERT INTO `lesson_resource` VALUES ('420', '0', '0', '', '113', '11', '', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">ftghdfgh</span></h1><p><br /></p>', '1403575830');
INSERT INTO `lesson_resource` VALUES ('423', '0', '0', '', '113', '12', 'dsfgsdfg', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">dsfgsdfg</span></h1><p><br /></p>', '1403576020');
INSERT INTO `lesson_resource` VALUES ('425', '0', '0', '', '120', '0', '这里是文章的标题', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\">这里是文章的标题</span></h1><p style=\"text-align:center;\"><strong class=\"ue_t\">[键入文档副标题]</strong></p><h3><span class=\"ue_t\" style=\"font-family:幼圆\">[标题 1]</span></h3><p class=\"ue_t\" style=\"text-indent:2em;\">对于“插入”选项卡上的库，在设计时都充分考虑了其中的项与文档整体外观的协调性。 您可以使用这些库来插入表格、页眉、页脚、列表、封面以及其他文档构建基块。 您创建的图片、图表或关系图也将与当前的文档外观协调一致。</p><h3><span class=\"ue_t\" style=\"font-family:幼圆\">[标题 2]</span></h3><p class=\"ue_t\" style=\"text-indent:2em;\">在“开始”选项卡上，通过从快速样式库中为所选文本选择一种外观，您可以方便地更改文档中所选文本的格式。 您还可以使用“开始”选项卡上的其他控件来直接设置文本格式。大多数控件都允许您选择是使用当前主题外观，还是使用某种直接指定的格式。 </p><h3><span class=\"ue_t\" style=\"font-family:幼圆\">[标题 3]</span></h3><p class=\"ue_t\">对于“插入”选项卡上的库，在设计时都充分考虑了其中的项与文档整体外观的协调性。 您可以使用这些库来插入表格、页眉、页脚、列表、封面以及其他文档构建基块。 您创建的图片、图表或关系图也将与当前的文档外观协调一致。</p><p class=\"ue_t\"><br /></p><p><br /></p>', '1403576923');
INSERT INTO `lesson_resource` VALUES ('426', '0', '0', '', '113', '8', '我', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\">我</span></h1><p style=\"text-align:center;\"><strong class=\"ue_t\">[键入文档副标题]</strong></p><h3><span class=\"ue_t\" style=\"font-family:幼圆\">[标题 1]</span></h3><p class=\"ue_t\" style=\"text-indent:2em;\">对于“插入”选项卡上的库，在设计时都充分考虑了其中的项与文档整体外观的协调性。 您可以使用这些库来插入表格、页眉、页脚、列表、封面以及其他文档构建基块。 您创建的图片、图表或关系图也将与当前的文档外观协调一致。</p><h3><span class=\"ue_t\" style=\"font-family:幼圆\">[标题 2]</span></h3><p class=\"ue_t\" style=\"text-indent:2em;\">在“开始”选项卡上，通过从快速样式库中为所选文本选择一种外观，您<span style=\"text-indent:2em;\">来直接设置文本格式。大多数控件都允许您选择是使用当前主题外观，还是使用某种直接指定的格式。 </span></p><h3><span class=\"ue_t\" style=\"font-family:幼圆;\">[标题 3]</span></h3><p class=\"ue_t\" style=\"text-indent:2em;\">对于“插入”选项卡上的库，在设计时都充分考虑了其中的项与文档整体外观的协调性。 您可以使用这些库来插入表格、页眉、页脚、列表、封面以及其他文档构可以方便地更改文档中所选文本的格式。 您还可以使用“开始”选项卡上的其他控件建基块。 您创建的图片、图表或关系图也将与当前的文档外观协调一致。</p><p class=\"ue_t\"><br /></p><p><br /></p>', '1403590080');
INSERT INTO `lesson_resource` VALUES ('428', '672', '3', '', '123', '5', 'test.pdf', '第一单元\n凿佻\n\n赠侃\n\n噪侉\n\n第一课\n\n憎侬 泽澡伽灶倮 曾怎佴 憎侬 躁蚤佟燥\n\n伽燥\n\n我上学我骄傲\n观察岛\n\n老师好！\n\n2\n\n心理健康教育\n\n憎侬\n\n泽澡佻\n\n赠佻 皂侏灶倮 曾蚤伲燥 曾怎佴 泽澡佶灶倮 造倩\n\n我 是 一 名 小 学 生 啦 ！\n...', '1403745962');
INSERT INTO `lesson_resource` VALUES ('429', '673', '0', '20cec0bae9e25d22.png', '123', '3', 'Readme', '-----------------------------------------------------------------------------Beyond Compare 3\nby Scooter Software\nwww.scootersoftware.com\n-----------------------------------------------------------------------------1. 描述\n------Beyond Compare 是一个 Windows 平台上的文件和文件夹比较...', '1403745965');
INSERT INTO `lesson_resource` VALUES ('430', '664', '1', '6b5059371fa85b5c.png', '123', '2', 'j向对象基础', '123', '1403745966');
INSERT INTO `lesson_resource` VALUES ('432', '653', '3', '', '123', '0', '1年级课题目录', '一年级课题目录\n课标模块\n小小的“我”\n\n课题\n1、\n1 我从哪里来？\n2 身体里的“小乐队”\n3 肚子里的西瓜子\n4 身体里的“时钟”\n5 让我们去感受世界\n2、 安全过马路\n6 红灯停 绿灯行\n7 走路莫分神\n8 会“说话”的黄线\n9 弯道让车.....', '1403745973');
INSERT INTO `lesson_resource` VALUES ('434', '0', '0', '', '123', '7', '', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\"><img src=\"http://dev-images.dodoedu.com/jiaocai/53abcada5aa28.jpg\" title=\"背景墙.jpg\" /></span></h1><p><img src=\"http://dev-images.dodoedu.com/jiaocai/537c4a60cff37.JPG\" /></p>', '1403767711');
INSERT INTO `lesson_resource` VALUES ('436', '0', '0', '', '104', '1', '', '', '1404090809');
INSERT INTO `lesson_resource` VALUES ('443', '0', '0', '', '123', '6', 'acxvzxcvzxcv', '<h1 style=\"margin:0px 0px 20px;padding:0px 4px 0px 0px;text-align:center;border-bottom-color:#CCCCCC;border-bottom-width:2px;border-bottom-style:solid;\" name=\"tc\" label=\"Title center\"><span style=\"color:#C0504D;\" data-default=\"[键入文档标题]\">acxvzxcvzxcv</span></h1><p><br /></p>', '1404092158');
INSERT INTO `lesson_resource` VALUES ('444', '0', '0', '', '123', '1', '', '<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;555555 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>', '1404092163');
INSERT INTO `lesson_resource` VALUES ('445', '0', '0', '', '123', '4', '阿斯顿法师打发士大夫', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">阿斯顿法师打发士大夫</span></h1><p><br /></p>', '1404092190');
INSERT INTO `lesson_resource` VALUES ('446', '679', '0', '20cec0bae9e25d22.png', '104', '0', 'Readme', '', '1404177287');
INSERT INTO `lesson_resource` VALUES ('448', '663', '4', '', '104', '4', '名词单数变复数', '名词单数变复数', '1404177296');
INSERT INTO `lesson_resource` VALUES ('450', '735', '3', '1361f3efb565092e.png', '124', '0', 'HTML 编码规范', '', '1409801116');
INSERT INTO `lesson_resource` VALUES ('451', '672', '3', '', '125', '0', 'test.pdf', '第一单元\n凿佻\n\n赠侃\n\n噪侉\n\n第一课\n\n憎侬 泽澡伽灶倮 曾怎佴 憎侬 躁蚤佟燥\n\n伽燥\n\n我上学我骄傲\n观察岛\n\n老师好！\n\n2\n\n心理健康教育\n\n憎侬\n\n泽澡佻\n\n赠佻 皂侏灶倮 曾蚤伲燥 曾怎佴 泽澡佶灶倮 造倩\n\n我 是 一 名 小 学 生 啦 ！\n...', '1403745962');
INSERT INTO `lesson_resource` VALUES ('452', '0', '0', '', '125', '1', '', '<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;555555 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>', '1404092163');
INSERT INTO `lesson_resource` VALUES ('453', '664', '1', '6b5059371fa85b5c.png', '125', '2', 'j向对象基础', '123', '1403745966');
INSERT INTO `lesson_resource` VALUES ('454', '673', '0', '20cec0bae9e25d22.png', '125', '3', 'Readme', '-----------------------------------------------------------------------------Beyond Compare 3\nby Scooter Software\nwww.scootersoftware.com\n-----------------------------------------------------------------------------1. 描述\n------Beyond Compare 是一个 Windows 平台上的文件和文件夹比较...', '1403745965');
INSERT INTO `lesson_resource` VALUES ('455', '0', '0', '', '125', '4', '阿斯顿法师打发士大夫', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">阿斯顿法师打发士大夫</span></h1><p><br /></p>', '1404092190');
INSERT INTO `lesson_resource` VALUES ('456', '653', '3', '', '125', '5', '1年级课题目录', '一年级课题目录\n课标模块\n小小的“我”\n\n课题\n1、\n1 我从哪里来？\n2 身体里的“小乐队”\n3 肚子里的西瓜子\n4 身体里的“时钟”\n5 让我们去感受世界\n2、 安全过马路\n6 红灯停 绿灯行\n7 走路莫分神\n8 会“说话”的黄线\n9 弯道让车.....', '1403745973');
INSERT INTO `lesson_resource` VALUES ('457', '0', '0', '', '125', '6', 'acxvzxcvzxcv', '<h1 style=\"margin:0px 0px 20px;padding:0px 4px 0px 0px;text-align:center;border-bottom-color:#CCCCCC;border-bottom-width:2px;border-bottom-style:solid;\" name=\"tc\" label=\"Title center\"><span style=\"color:#C0504D;\" data-default=\"[键入文档标题]\">acxvzxcvzxcv</span></h1><p><br /></p>', '1404092158');
INSERT INTO `lesson_resource` VALUES ('458', '0', '0', '', '125', '7', '', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\"><img src=\"http://dev-images.dodoedu.com/jiaocai/53abcada5aa28.jpg\" title=\"背景墙.jpg\" /></span></h1><p><img src=\"http://dev-images.dodoedu.com/jiaocai/537c4a60cff37.JPG\" /></p>', '1403767711');
INSERT INTO `lesson_resource` VALUES ('459', '739', '3', '', '124', '5', '博盛简报', '', '1409822464');
INSERT INTO `lesson_resource` VALUES ('460', '654', '4', '', '104', '2', 'zhuangjia_zhang5.mp4', '', '1413791590');
INSERT INTO `lesson_resource` VALUES ('461', '549', '4', '64f5b58008e1fa5e.png', '104', '3', 't.mp4', '', '1413791598');
INSERT INTO `lesson_resource` VALUES ('462', '556', '3', '0904d9b3eff71194.png', '104', '5', '网上兼职流程介绍.doc', '446565', '1413791610');
INSERT INTO `lesson_resource` VALUES ('463', '665', '0', '', '104', '6', '生命安全教育课程目录 to 博盛', '555555', '1413791618');
INSERT INTO `lesson_resource` VALUES ('464', '607', '3', '153c674c6322ee8d.png', '126', '0', 'PHP 编码规范.docx', 'PHP File 文件格式\n常规\n\n对于只包含有 PHP 代码的文件，结束标志（ \"?>\"）是不允许存在的， PHP 自身不需要（\"?\n>\"）, 这样做, 可以防止它的末尾的被意外地注入相应。\n重要： 由 __HALT_COMPILER() 允许的任意的二进制代码的内容被 Zend ...', '1415081633');
INSERT INTO `lesson_resource` VALUES ('466', '0', '0', '', '126', '2', 'dfdsgdfgsdf', '<h1 label=\"Title center\" name=\"tc\" style=\"border-bottom-color:#cccccc;border-bottom-width:2px;border-bottom-style:solid;padding:0px 4px 0px 0px;text-align:center;margin:0px 0px 20px;\"><span style=\"color:#c0504d;\" data-default=\"[键入文档标题]\">dfdsgdfgsdf</span></h1><p>dfgsdfgsdfg</p><p><br /></p><p><img src=\"http://dev-images.dodoedu.com/jiaocai/54586eed0dd77.gif\" title=\"124_385647_3bdcd23a23ad448.gif\" /></p>', '1415081712');
INSERT INTO `lesson_resource` VALUES ('472', '735', '3', '1361f3efb565092e.png', '15', '1', 'HTML 编码规范', '', '1416379848');

-- ----------------------------
-- Table structure for `node_descript`
-- ----------------------------
DROP TABLE IF EXISTS `node_descript`;
CREATE TABLE `node_descript` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `field` varchar(255) DEFAULT NULL COMMENT '学段',
  `version` varchar(255) DEFAULT NULL COMMENT '版本',
  `grade` varchar(255) DEFAULT NULL COMMENT '年级',
  `son_node` int(10) DEFAULT NULL COMMENT '知识节点',
  `son_node_descript` varchar(255) DEFAULT NULL COMMENT '子节点的描述',
  `subject` varchar(255) DEFAULT NULL COMMENT '学科',
  `user_id` varchar(255) DEFAULT NULL COMMENT '添加描述的用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='知识节点的描述';

-- ----------------------------
-- Records of node_descript
-- ----------------------------
INSERT INTO `node_descript` VALUES ('1', 'xd001', 'v01', 'GO003', '6385', '据报道，海润影视IPO的初衷并非A股市场。资料显示，海润影视早在2010年就已开始筹备上市，本有望于2011年在香港上市，后因香港股市低迷而推迟上市计划。王存林也表示，公司不在香港上市是因为觉得香港市况不太好', 'GS0024', 'm36359802300862200030');
INSERT INTO `node_descript` VALUES ('2', 'xd001', 'v01', 'GO003', '6396', '444444443444343', 'GS0025', 'm36359802300862200030');
INSERT INTO `node_descript` VALUES ('3', 'xd001', 'v11', 'GO003', '6729', '6月7日是2014年的高考的第一天，对于这场关乎许多考生和家庭命运的考试来说试题的保密和安全受到万众瞩目。6月5日记者跟随安徽省芜湖市考试中心的工作人员实地探访了存放高考试卷的保密室，并且亲身感受了考试试卷转运过程', 'GS0025', 'm36359802300862200030');
INSERT INTO `node_descript` VALUES ('4', 'xd001', 'v11', 'GO005', '6791', '我问妈什么是理财？她说理财就是能赚会花，开源节流。我想我是学生，收入来源也就是压岁钱，要想存钱只能节流了，我暗下决心，不会赚钱我想我尽可能少花钱，改掉以前胡乱花钱的坏习惯，好好计划，积少成多，把买零食和饮料的零花钱节约下来，放进自己的储钱罐里，养成这个好习惯后，一定不会乱花钱。做个名副其实的“小小理财师”。', 'GS0025', 'm36359802300862200030');
INSERT INTO `node_descript` VALUES ('5', 'xd001', 'v11', 'GO004', '6761', '大连一名男子在青岛机场候机时被人打了两个嘴巴子没有还手，等到乘机抵达大连后，该男子将对方打得头破血流。\r\n\r\n5月29日23时左右，一架从青岛飞至大连的航班抵达周水子机场，在旅客大厅中，两名男子动起了手，一名男子被打得头破血流，警方赶到后将两人带走调查。', 'GS0025', 'm36359802300862200030');

-- ----------------------------
-- Table structure for `resource_type`
-- ----------------------------
DROP TABLE IF EXISTS `resource_type`;
CREATE TABLE `resource_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `node_id` int(10) DEFAULT NULL COMMENT '节点id',
  `classify_name` varchar(255) DEFAULT NULL COMMENT '资源分类名称',
  `apply_type` int(1) DEFAULT NULL COMMENT '1:内部,2:第三方',
  `type_id` varchar(255) DEFAULT NULL COMMENT '资源类型关联id',
  `api_url` varchar(255) DEFAULT NULL COMMENT '第三方的url',
  `model_name` varchar(255) DEFAULT NULL COMMENT '模型层名称',
  `func_name` varchar(255) DEFAULT NULL COMMENT '方法名',
  `xk` varchar(255) DEFAULT NULL COMMENT '学科',
  `study_type_id` int(1) DEFAULT NULL COMMENT '1:学习资源,2:测评',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='学习资源类型配置表';

-- ----------------------------
-- Records of resource_type
-- ----------------------------
INSERT INTO `resource_type` VALUES ('29', '97', '电子书包', '1', '1', '', '', '', 'GS0024', '1');
INSERT INTO `resource_type` VALUES ('30', '87', '电子课本', '1', '1', '', '', '', 'GS0025', '1');
INSERT INTO `resource_type` VALUES ('31', '87', '测评作业', '1', '100', '', '', '', 'GS0025', '2');
INSERT INTO `resource_type` VALUES ('32', '89', '电子课本', '1', '2', '', '', '', 'GS0025', '1');
INSERT INTO `resource_type` VALUES ('33', '90', '电子课本', '1', '2', '', '', '', 'GS0025', '1');
INSERT INTO `resource_type` VALUES ('34', '91', '电子课本', '1', '2', '', '', '', 'GS0025', '1');
INSERT INTO `resource_type` VALUES ('35', '87', '学习微视频', '1', '5', '', '', '', 'GS0025', '1');
INSERT INTO `resource_type` VALUES ('36', '97', '观摩课', '1', '6', '', '', '', 'GS0024', '1');
INSERT INTO `resource_type` VALUES ('39', '88', '电子书包', '1', '1,2,3', '', '', '', 'GS0025', '1');
INSERT INTO `resource_type` VALUES ('42', '88', '测评', '1', '100', '', '', '', 'GS0025', '2');

-- ----------------------------
-- Table structure for `sm_course`
-- ----------------------------
DROP TABLE IF EXISTS `sm_course`;
CREATE TABLE `sm_course` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '课程ID',
  `class_id` int(10) NOT NULL,
  `course_remark` varchar(200) DEFAULT NULL COMMENT '课程备注',
  `teacher_user_id` char(21) NOT NULL,
  `course_week` enum('7','6','5','4','3','2','1') NOT NULL COMMENT '开课星期',
  `course_date` char(10) NOT NULL COMMENT '开课日期',
  `course_sort` tinyint(2) NOT NULL COMMENT '开课的节次',
  `lesson_folder_id` int(10) DEFAULT NULL COMMENT '关联的备课夹ID',
  `course_status` tinyint(1) NOT NULL COMMENT '1:已备课；0：未备课',
  `course_grade` char(5) DEFAULT NULL COMMENT '年级',
  `course_stage` char(5) DEFAULT NULL COMMENT '学段',
  `teacher_appraise` varchar(600) DEFAULT NULL COMMENT '教师自我评价',
  `course_record` int(10) DEFAULT NULL COMMENT '课程记录',
  `course_impression` varchar(200) DEFAULT NULL COMMENT '课程产生的班级印象',
  `city_id` int(10) DEFAULT NULL COMMENT '城市ID',
  `town_id` int(10) DEFAULT NULL COMMENT '区县ID',
  `school_id` int(10) DEFAULT NULL COMMENT '学校ID',
  `course_appraise_status` tinyint(1) DEFAULT '0' COMMENT '课程的评价状态（0：无评价；1：有评价）',
  `course_question_status` tinyint(1) DEFAULT '0' COMMENT '课程问答状态（0：无问答;1:有问答）',
  `course_lesson_folder_status` tinyint(1) DEFAULT '0' COMMENT '课程备课夹状态（0:未备课；1已备课）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=320 DEFAULT CHARSET=utf8 COMMENT='生命健康课程';

-- ----------------------------
-- Records of sm_course
-- ----------------------------
INSERT INTO `sm_course` VALUES ('216', '337608', '', 'w36451978107373010061', '5', '2014-06-06', '1', '20', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '1', '1');
INSERT INTO `sm_course` VALUES ('217', '337607', '写个备注吧', 'w36451978107373010061', '5', '2014-06-06', '2', '54', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '1', '1', '1');
INSERT INTO `sm_course` VALUES ('218', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '1', '114', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('219', '337607', '', 'w36451978107373010061', '4', '2014-06-19', '2', '115', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('220', '393606', '', 'w36451978107373010061', '5', '2014-09-05', '1', '114', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('221', '337608', '', 'w36451978107373010061', '5', '2014-09-12', '1', '116', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('222', '393606', '', 'w36451978107373010061', '5', '2014-09-12', '2', '114', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('223', '393606', '', 'w36451978107373010061', '5', '2014-09-12', '3', '114', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('224', '393606', '', 'w36451978107373010061', '5', '2014-09-12', '4', '114', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('225', '393606', '', 'w36451978107373010061', '1', '2014-09-15', '1', '0', '0', 'GO003', 'xd001', null, '1442', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('226', '393606', '', 'm36359802300862200030', '2', '2014-09-16', '1', '85', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('227', '393606', '明天', 'm36359802300862200030', '3', '2014-09-17', '1', '94', '0', 'GO003', 'xd001', null, '1450', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('228', '393606', '第二节课', 'm36359802300862200030', '4', '2014-09-18', '2', '67', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('229', '393606', 'qwe', 'w36451978107373010061', '2', '2014-09-16', '1', '70', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('230', '393606', '', 'w36451978107373010061', '2', '2014-09-16', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('231', '393606', '', 'w36451978107373010061', '2', '2014-09-16', '5', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('232', '393606', '', 'w36451978107373010061', '4', '2014-09-18', '6', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('233', '393606', '', 'm36359802300862200030', '3', '2014-09-24', '1', '67', '0', 'GO003', 'xd001', null, '1453', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('234', '393606', '呵呵呵', 'm36359802300862200030', '2', '2014-09-23', '2', '0', '0', 'GO003', 'xd001', null, '1463', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('235', '393606', '', 'm36359802300862200030', '1', '2014-09-29', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('236', '393606', '', 'm36359802300862200030', '2', '2014-09-30', '2', '94', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('237', '393606', '', 'm36359802300862200030', '1', '2014-09-29', '1', '94', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('238', '393606', '', 'm36359802300862200030', '3', '2014-09-17', '2', '94', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('239', '393606', '你好', 'm36359802300862200030', '3', '2014-09-17', '3', '94', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('240', '393605', '呵呵呵', 'm36359802300862200030', '4', '2014-09-18', '1', '0', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('241', '393606', '', 'm36359802300862200030', '4', '2014-09-18', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('242', '393606', '', 'm36359802300862200030', '4', '2014-09-18', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('243', '393606', '', 'm36359802300862200030', '4', '2014-09-18', '5', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('244', '393606', '', 'm36359802300862200030', '4', '2014-09-18', '6', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('245', '393606', '', 'm36359802300862200030', '4', '2014-09-18', '7', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('246', '393606', 'Ios test\n', 'm36359802300862200030', '3', '2014-09-17', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('247', '393606', '呵呵呵', 'm36359802300862200030', '3', '2014-09-17', '5', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('248', '393606', '呵呵呵', 'm36359802300862200030', '3', '2014-09-17', '6', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('249', '393606', '呵呵呵', 'm36359802300862200030', '3', '2014-09-17', '7', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('250', '393606', '呵呵呵', 'm36359802300862200030', '5', '2014-09-19', '1', '0', '0', 'GO003', 'xd001', null, '1451', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('251', '337607', '呵呵呵', 'm36359802300862200030', '5', '2014-09-19', '2', '0', '0', 'GO005', 'xd001', null, null, null, '420100', '420199', '121584', '1', '0', '0');
INSERT INTO `sm_course` VALUES ('252', '393606', '呵呵呵', 'm36359802300862200030', '5', '2014-09-19', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('253', '393606', '呵呵呵', 'm36359802300862200030', '5', '2014-09-19', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('254', '393606', '', 'w36451978107373010061', '5', '2014-09-19', '1', '114', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('255', '393606', '', 'w36451978107373010061', '5', '2014-09-19', '2', '70', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('256', '393606', '', 'w36451978107373010061', '5', '2014-09-19', '3', '92', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('257', '393606', '', 'w36451978107373010061', '5', '2014-09-19', '5', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('258', '393606', '', 'w36451978107373010061', '1', '2014-09-22', '1', '0', '0', 'GO003', 'xd001', null, '1494', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('259', '393606', '', 'w36451978107373010061', '2', '2014-09-23', '3', '114', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('260', '393606', 'fsdafasdfsdf', 'w36451978107373010061', '3', '2014-09-24', '5', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('261', '337607', 'sdfsdfsadf', 'w36451978107373010061', '4', '2014-09-25', '7', '0', '0', 'GO005', 'xd001', null, '1454', '82,83,85,109,110,111,112,113,119', '420100', '420199', '121584', '1', '1', '0');
INSERT INTO `sm_course` VALUES ('262', '337608', '', 'w36451978107373010061', '1', '2014-09-22', '5', '0', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('263', '393606', '', 'w36451978107373010061', '1', '2014-09-22', '7', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('264', '393606', '', 'w36451978107373010061', '4', '2014-09-25', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('265', '393605', '', 'w36451978107373010061', '1', '2014-09-29', '1', '0', '0', 'GO004', 'xd001', null, '1490', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('266', '393606', 'fdfsf', 'm36359802300862200030', '2', '2014-09-23', '3', '0', '0', 'GO003', 'xd001', null, '1465', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('267', '393606', '', 'm36359802300862200030', '2', '2014-09-23', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('268', '393606', '', 'm36359802300862200030', '2', '2014-09-23', '1', '0', '0', 'GO003', 'xd001', null, '1466', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('269', '393606', '', 'm36359802300862200030', '3', '2014-09-24', '2', '0', '0', 'GO003', 'xd001', null, '1467', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('270', '393606', '', 'm36359802300862200030', '2', '2014-09-23', '5', '0', '0', 'GO003', 'xd001', null, '1468', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('271', '393606', '', 'm36359802300862200030', '2', '2014-09-23', '6', '0', '0', 'GO003', 'xd001', null, '1469', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('272', '393606', '', 'm36359802300862200030', '2', '2014-09-23', '7', '0', '0', 'GO003', 'xd001', null, '1470', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('273', '393606', '', 'm36359802300862200030', '4', '2014-09-25', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('274', '393606', '', 'm36359802300862200030', '5', '2014-09-26', '7', '0', '0', 'GO003', 'xd001', null, '1473', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('275', '393606', '', 'm36359802300862200030', '5', '2014-09-26', '1', '0', '0', 'GO003', 'xd001', null, '1471', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('276', '393606', '', 'm36359802300862200030', '5', '2014-09-26', '2', '0', '0', 'GO003', 'xd001', null, '1472', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('277', '393606', '', 'm36359802300862200030', '4', '2014-09-25', '7', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('278', '393606', '', 'm36359802300862200030', '1', '2014-09-29', '7', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('279', '393606', '', 'm36359802300862200030', '5', '2014-09-26', '6', '0', '0', 'GO003', 'xd001', null, '1474', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('280', '393606', '', 'm36359802300862200030', '5', '2014-09-26', '3', '0', '0', 'GO003', 'xd001', null, '1475', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('281', '393606', '', 'm36359802300862200030', '5', '2014-10-03', '4', '0', '0', 'GO003', 'xd001', null, '1476', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('282', '337607', '', 'w36451978107373010061', '4', '2014-09-25', '1', '0', '0', 'GO005', 'xd001', null, '1495', '86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,131', '420100', '420199', '121584', '1', '1', '0');
INSERT INTO `sm_course` VALUES ('283', '393606', '呵呵呵', 'w36451978107373010061', '5', '2014-09-26', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('284', '393605', 'hi 你好。 ', 'w36451978107373010061', '5', '2014-09-26', '5', '0', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('285', '393606', '0K', 'w36451978107373010061', '5', '2014-09-26', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('286', '393606', 'jjjj ', 'w36451978107373010061', '1', '2014-09-22', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('287', '393606', 'kkkk ', 'w36451978107373010061', '1', '2014-09-22', '6', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('288', '384990', 'uuuuu ', 'w36451978107373010061', '1', '2014-09-22', '2', '0', '0', 'GO005', 'xd001', null, '1486', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('289', '393605', 'ko ', 'w36451978107373010061', '5', '2014-09-26', '6', '0', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('290', '1', '', '1', '4', 'w364519781', '20', '261', '0', 'w4061', '热爱劳动', null, null, null, '0', '0', '0', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('291', '1', '', '1', '4', 'w364519781', '20', '261', '0', 'h4061', '热爱劳动', null, null, null, '0', '0', '0', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('292', '1', '', '2', '4', 'w364519781', '32', '261', '0', 'l4066', '活学活用', null, null, null, '0', '0', '0', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('293', '1', '', '3', '4', 'w364519781', '43', '261', '0', 'w4061', '不惧困难', null, null, null, '0', '0', '0', '0', '0', '1');
INSERT INTO `sm_course` VALUES ('294', '393606', '', 'w36451978107373010061', '5', '2014-09-26', '7', '0', '0', 'GO003', 'xd001', null, '1496', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('295', '384990', '', 'w36451978107373010061', '4', '2014-10-02', '4', '0', '0', 'GO005', 'xd001', null, '1488', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('296', '393606', '呵呵呵', 'w36451978107373010061', '2', '2014-09-30', '1', '0', '0', 'GO003', 'xd001', null, '1491', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('297', '393606', '呵呵呵', 'w36451978107373010061', '3', '2014-10-01', '1', '0', '0', 'GO003', 'xd001', null, '1485', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('298', '393606', '呵呵呵', 'w36451978107373010061', '1', '2014-09-29', '2', '0', '0', 'GO003', 'xd001', null, '1482', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('299', '393606', '呵呵呵', 'w36451978107373010061', '2', '2014-09-30', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('300', '393606', '呵呵呵', 'w36451978107373010061', '3', '2014-10-01', '2', '0', '0', 'GO003', 'xd001', null, '1484', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('301', '393606', '', 'w36451978107373010061', '1', '2014-09-29', '3', '0', '0', 'GO003', 'xd001', null, '1489', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('302', '393606', '', 'w36451978107373010061', '4', '2014-10-02', '1', '0', '0', 'GO003', 'xd001', null, '1492', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('303', '393606', '', 'w36451978107373010061', '5', '2014-10-03', '1', '0', '0', 'GO003', 'xd001', null, '1487', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('304', '393606', '', 'w36451978107373010061', '4', '2014-10-02', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('305', '393606', '', 'w36451978107373010061', '5', '2014-10-03', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('306', '393606', '呵呵呵', 'w36451978107373010061', '2', '2014-09-30', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('307', '393606', '呵呵呵', 'w36451978107373010061', '3', '2014-10-01', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('308', '393606', '呵呵呵', 'w36451978107373010061', '4', '2014-10-02', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('309', '393606', '呵呵呵', 'w36451978107373010061', '2', '2014-10-07', '1', '0', '0', 'GO003', 'xd001', null, '1497', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('310', '393606', '', 'w36451978107373010061', '4', '2014-10-16', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('311', '393606', '', 'w36451978107373010061', '2', '2014-10-21', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('312', '366431', '罗卜', 'w36451978107373010061', '3', '2014-10-22', '2', '0', '0', 'GO008', 'xd001', null, null, null, '420100', '420199', '121502', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('313', '337590', '', 'w35935506404531840025', '3', '2014-10-22', '2', '0', '0', 'GO005', 'xd001', null, null, null, '420100', '420199', '121502', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('314', '337598', '', 'w35935506404531840025', '4', '2014-10-23', '3', '0', '0', 'GO005', 'xd001', null, null, '120', '420100', '420199', '121502', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('315', '393606', '', 'm36359802300862200030', '4', '2014-10-23', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('316', '393607', '', 'w36451978107373010061', '4', '2014-10-23', '1', '0', '0', 'GO003', 'xd001', null, '1526', null, '420100', '420199', '121502', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('317', '337598', '', 'w36451978107373010061', '5', '2014-10-24', '3', '0', '0', 'GO005', 'xd001', null, '1525', '132,133', '420100', '420199', '121502', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('318', '393607', 'hhhh ', 'w36451978107373010061', '1', '2014-10-27', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121502', '0', '0', '0');
INSERT INTO `sm_course` VALUES ('319', '346089', '', 'w35935506404531840025', '3', '2014-10-29', '3', '0', '0', 'GO004', 'xd001', null, null, null, '420600', '420684', '112111', '0', '0', '0');

-- ----------------------------
-- Table structure for `sm_course_appraise`
-- ----------------------------
DROP TABLE IF EXISTS `sm_course_appraise`;
CREATE TABLE `sm_course_appraise` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '评价ID',
  `course_id` int(10) NOT NULL COMMENT '课程ID',
  `appraise_score` float(2,1) NOT NULL COMMENT '评分',
  `appraise_remark` varchar(300) DEFAULT NULL COMMENT '一百字以内的短评',
  `appraise_user_id` char(21) DEFAULT NULL COMMENT '评价人ID',
  `appraise_role` enum('3','2','1') DEFAULT NULL COMMENT '1：学；2：老师；3：家长',
  `appraise_time` int(10) DEFAULT NULL COMMENT '评价时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='生命安全课程评价';

-- ----------------------------
-- Records of sm_course_appraise
-- ----------------------------
INSERT INTO `sm_course_appraise` VALUES ('15', '217', '8.0', '给个8分吧', 'l35789637007221180030', '1', '1402023627');
INSERT INTO `sm_course_appraise` VALUES ('16', '261', '6.0', '很不错啊', 'b40611150309548770083', '1', '1411453328');
INSERT INTO `sm_course_appraise` VALUES ('17', '282', '0.0', '很好，我都听懂了', 't37880540906192790011', '1', '1411635461');
INSERT INTO `sm_course_appraise` VALUES ('18', '261', '6.0', '非常不错', 't37880540906192790011', '1', '1411635475');
INSERT INTO `sm_course_appraise` VALUES ('19', '251', '8.0', 'OK，很好', 't37880540906192790011', '1', '1411635486');

-- ----------------------------
-- Table structure for `stu_default_version`
-- ----------------------------
DROP TABLE IF EXISTS `stu_default_version`;
CREATE TABLE `stu_default_version` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sel_user_id` varchar(255) DEFAULT NULL COMMENT '用户id',
  `sel_study_version` varchar(255) DEFAULT NULL COMMENT '默认选择的版本',
  `study_type_id` varchar(255) DEFAULT NULL COMMENT 'GS0025:心理, GS0024:生命科学',
  `time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='用户默认的教材版本';

-- ----------------------------
-- Records of stu_default_version
-- ----------------------------
INSERT INTO `stu_default_version` VALUES ('16', 's35951247001862320095', 'v11', 'GS0025', '1401936054');
INSERT INTO `stu_default_version` VALUES ('17', 'q35787441502121380034', 'v01', 'GS0025', '1402012238');
INSERT INTO `stu_default_version` VALUES ('21', 't37880529802025640041', 'xd001', 'GS0024', '1402911517');
INSERT INTO `stu_default_version` VALUES ('23', 'l35789637007221180030', 'v11', 'GS0024', '1402996675');
INSERT INTO `stu_default_version` VALUES ('26', 'l35789637007221180030', 'v11', 'GS0025', '1403159011');
INSERT INTO `stu_default_version` VALUES ('27', 's35951247001862320095', 'v11', 'GS0024', '1403511913');

-- ----------------------------
-- Table structure for `study_record`
-- ----------------------------
DROP TABLE IF EXISTS `study_record`;
CREATE TABLE `study_record` (
  `study_record_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `study_record_user_id` varchar(255) DEFAULT NULL COMMENT '学习资源的用户id',
  `resource_id` int(10) DEFAULT NULL COMMENT '资源表关联id',
  `study_record_status` tinyint(2) DEFAULT NULL COMMENT '1:未学完,2:学习完',
  `study_record_time` varchar(255) DEFAULT NULL COMMENT '用户学习的时间',
  `study_resource_subject_type` varchar(24) DEFAULT NULL COMMENT 'GS0025:心理, GS0024:生命科学',
  `time` int(10) DEFAULT NULL,
  `study_score` int(10) DEFAULT NULL COMMENT '测评作业的分数(只有测评试卷需要这个字段)',
  PRIMARY KEY (`study_record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8 COMMENT='学习记录表';

-- ----------------------------
-- Records of study_record
-- ----------------------------
INSERT INTO `study_record` VALUES ('180', 's35951247001862320095', '50', '2', '0', 'GS0025', '1403762773', '0');
INSERT INTO `study_record` VALUES ('181', 's35951247001862320095', '46', '1', '0', 'GS0025', '1403762810', '0');
INSERT INTO `study_record` VALUES ('182', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403762819', '0');
INSERT INTO `study_record` VALUES ('183', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403762874', '0');
INSERT INTO `study_record` VALUES ('184', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403763363', '0');
INSERT INTO `study_record` VALUES ('185', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403763365', '0');
INSERT INTO `study_record` VALUES ('186', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403763373', '0');
INSERT INTO `study_record` VALUES ('187', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403763496', '0');
INSERT INTO `study_record` VALUES ('188', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403763526', '0');
INSERT INTO `study_record` VALUES ('189', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403763638', '0');
INSERT INTO `study_record` VALUES ('190', 's35951247001862320095', '45', '1', '0', 'GS0025', '1403763780', '0');
INSERT INTO `study_record` VALUES ('191', 'l35789637007221180030', '50', '2', '0', 'GS0025', '1403764050', '0');
INSERT INTO `study_record` VALUES ('192', 's35951247001862320095', '54', '1', '24', 'GS0024', '1403764503', '0');
INSERT INTO `study_record` VALUES ('193', 's35951247001862320095', '53', '2', '0', 'GS0025', '1408518810', '0');
INSERT INTO `study_record` VALUES ('194', 's35951247001862320095', '55', '2', '0', 'GS0024', '1408518965', '0');
INSERT INTO `study_record` VALUES ('195', 's35951247001862320095', '46', '1', '0', 'GS0025', '1409282001', '0');
INSERT INTO `study_record` VALUES ('196', 's35951247001862320095', '46', '1', '0', 'GS0025', '1409711570', '0');
INSERT INTO `study_record` VALUES ('197', 's35951247001862320095', '45', '1', '0', 'GS0025', '1409729519', '0');
INSERT INTO `study_record` VALUES ('198', 's35951247001862320095', '46', '1', '0', 'GS0025', '1409729524', '0');
INSERT INTO `study_record` VALUES ('199', 's35951247001862320095', '45', '1', '0', 'GS0025', '1409729652', '0');
INSERT INTO `study_record` VALUES ('200', 's35951247001862320095', '49', '1', '0', 'GS0025', '1409729743', '0');
INSERT INTO `study_record` VALUES ('201', 's35951247001862320095', '46', '1', '0', 'GS0025', '1409731059', '0');
INSERT INTO `study_record` VALUES ('202', 's35951247001862320095', '54', '1', '24', 'GS0024', '1409878064', '0');
INSERT INTO `study_record` VALUES ('203', 's35951247001862320095', '45', '1', '0', 'GS0025', '1415003587', '0');
INSERT INTO `study_record` VALUES ('204', 's35951247001862320095', '48', '1', '0', 'GS0025', '1415003592', '0');

-- ----------------------------
-- Table structure for `study_resource`
-- ----------------------------
DROP TABLE IF EXISTS `study_resource`;
CREATE TABLE `study_resource` (
  `resource_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `study_resource_id` int(10) DEFAULT NULL COMMENT '学习资源id',
  `study_resource_type_id` int(10) DEFAULT NULL COMMENT '学习资源类型关联id',
  `study_resource_subject_type` varchar(24) DEFAULT NULL COMMENT 'GS0025:心理, GS0024:生命科学',
  `study_resource_field` varchar(24) DEFAULT NULL COMMENT '学段',
  `study_resource_version` varchar(24) DEFAULT NULL COMMENT '学习资源的版本',
  `study_resource_grade` varchar(24) DEFAULT NULL COMMENT '学习资源对应的年级',
  `study_resource_user_id` varchar(255) DEFAULT NULL COMMENT '添加学习资源的用户id',
  `study_resource_user_name` varchar(255) DEFAULT NULL COMMENT '添加学习资源的用户名',
  `time` int(10) DEFAULT NULL COMMENT '时间',
  `study_grade_node_node` int(10) DEFAULT NULL COMMENT '知识节点',
  `study_resource_title` varchar(255) DEFAULT NULL COMMENT '资源的描述 ',
  `study_resource_size` int(10) DEFAULT NULL COMMENT '资源的大小(主要记录视频是多长时间)',
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='学习资源表';

-- ----------------------------
-- Records of study_resource
-- ----------------------------
INSERT INTO `study_resource` VALUES ('45', '163', '31', 'GS0025', 'xd001', 'v11', 'GO003', 'm36359802300862200030', 'asdf', '1402382372', '6729', 'fdas', '0');
INSERT INTO `study_resource` VALUES ('46', '162', '31', 'GS0025', 'xd001', 'v11', 'GO003', 'm36359802300862200030', 'asdf', '1402382372', '6729', '记叙文写作练习', '0');
INSERT INTO `study_resource` VALUES ('47', '105', '31', 'GS0025', 'xd001', 'v11', 'GO003', 'm36359802300862200030', 'asdf', '1402382372', '6729', '商的近似数（五）', '0');
INSERT INTO `study_resource` VALUES ('48', '121', '31', 'GS0025', 'xd001', 'v11', 'GO003', 'm36359802300862200030', 'asdf', '1402382372', '6729', '小数乘整数检测一', '0');
INSERT INTO `study_resource` VALUES ('49', '122', '31', 'GS0025', 'xd001', 'v11', 'GO003', 'm36359802300862200030', 'asdf', '1402382372', '6729', '解方程（一）', '0');
INSERT INTO `study_resource` VALUES ('50', '642', '30', 'GS0025', 'xd001', 'v11', 'GO003', 'm36359802300862200030', 'asdf', '1402382377', '6729', '无为而治的DBA生涯', '21');
INSERT INTO `study_resource` VALUES ('51', '651', '32', 'GS0025', 'xd001', 'v11', 'GO005', 'm36359802300862200030', 'asdf', '1402383658', '6791', '【电子课本】【第十五课】我是小小理财师', '4');
INSERT INTO `study_resource` VALUES ('52', '652', '34', 'GS0025', 'xd001', 'v11', 'GO007', 'm36359802300862200030', 'asdf', '1402386708', '6819', '【电子课本】第十一课', '4');
INSERT INTO `study_resource` VALUES ('53', '649', '30', 'GS0025', 'xd001', 'v11', 'GO003', 'm36359802300862200030', 'asdf', '1402445042', '6730', '青少年心理健康自助.孙显佳', '241');
INSERT INTO `study_resource` VALUES ('54', '641', '36', 'GS0024', 'xd001', 'v11', 'GO003', 'm36359802300862200030', ' 游龙', '1402565655', '6936', '梦想岛屿-没说课版.mp4', '0');
INSERT INTO `study_resource` VALUES ('55', '657', '29', 'GS0024', 'xd001', 'v11', 'GO003', 'm36359802300862200030', ' 游龙', '1402620614', '6936', 'js面向对象基础&多多社区js结构与使用', '19');
INSERT INTO `study_resource` VALUES ('56', '644', '29', 'GS0024', 'xd001', 'v11', 'GO003', 'm36359802300862200030', ' 游龙', '1402620614', '6936', '博盛游戏8899888', '2');
INSERT INTO `study_resource` VALUES ('57', '643', '29', 'GS0024', 'xd001', 'v11', 'GO003', 'm36359802300862200030', ' 游龙', '1402620614', '6936', '新数据时代的微软数据服务', '46');
INSERT INTO `study_resource` VALUES ('58', '653', '39', 'GS0025', 'xd001', 'v11', 'GO004', 'm36359802300862200030', 'asdf', '1402638415', '6761', '1年级课题目录', '2');
INSERT INTO `study_resource` VALUES ('59', '652', '39', 'GS0025', 'xd001', 'v11', 'GO004', 'm36359802300862200030', 'asdf', '1402638415', '6761', '【电子课本】第十一课', '4');
INSERT INTO `study_resource` VALUES ('60', '651', '39', 'GS0025', 'xd001', 'v11', 'GO004', 'm36359802300862200030', 'asdf', '1402638415', '6761', '【电子课本】【第十五课】我是小小理财师', '4');
INSERT INTO `study_resource` VALUES ('61', '650', '39', 'GS0025', 'xd001', 'v11', 'GO004', 'm36359802300862200030', 'asdf', '1402638415', '6761', '梦想岛屿', '4');
INSERT INTO `study_resource` VALUES ('62', '538', '39', 'GS0025', 'xd001', 'v11', 'GO004', 'm36359802300862200030', 'asdf', '1402638415', '6761', '流程图.docx', '6');
INSERT INTO `study_resource` VALUES ('63', '163', '42', 'GS0025', 'xd001', 'v11', 'GO004', 'm36359802300862200030', 'asdf', '1402875234', '6761', 'fdas', '0');

-- ----------------------------
-- Table structure for `sys_app`
-- ----------------------------
DROP TABLE IF EXISTS `sys_app`;
CREATE TABLE `sys_app` (
  `app_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '应用名称',
  `url` varchar(150) DEFAULT NULL COMMENT '应用地址',
  `role_code` tinyint(1) DEFAULT NULL COMMENT '角色',
  `target` char(10) DEFAULT NULL COMMENT '打开方式',
  `is_ok` tinyint(1) DEFAULT NULL COMMENT '状态',
  `xk` char(10) DEFAULT NULL COMMENT '学科',
  `app_order` int(10) unsigned DEFAULT NULL COMMENT '排序',
  `icon` char(50) DEFAULT NULL COMMENT '应用图标',
  `is_mobile` tinyint(1) DEFAULT '0' COMMENT '是否为移动端可用',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='教材应用表';

-- ----------------------------
-- Records of sys_app
-- ----------------------------
INSERT INTO `sys_app` VALUES ('11', '网盘', 'http://www.dodoedu.com/disk', '2', 'new', '1', 'GS0025', '11', 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('12', '多多翼学通', 'http://yxt.dodoedu.com', '3', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('13', '我的班级', 'http://www.dodoedu.com/class', '2', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('14', '我的学校', 'http://www.dodoedu.com/school', '2', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('15', '多多社区', 'http://www.dodoedu.com', '2', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('16', '多多手机APP', 'http://mobile.dodoedu.com/', '2', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('17', '教学大师', 'http://www.dodoedu.com/Application/appEntrance/appId/68', '2', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('18', '多多社区', 'http://www.dodoedu.com', '1', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('19', '兴趣小站', 'http://www.dodoedu.com/site', '3', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('20', '我的班级', 'http://www.dodoedu.com/class', '1', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('23', '我的班级', 'http://www.dodoedu.com/class', '3', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('24', '我的学校', 'http://www.dodoedu.com/school', '3', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('25', '个人空间', 'http://www.dodoedu.com/Space', '1', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('26', '多多相册', 'http://www.dodoedu.com/Album/home', '1', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('27', '多多日记', 'http://www.dodoedu.com/Blog/home', '1', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('28', '合易心理小栈', 'http://www.dodoedu.com/Application/appEntrance/appId/62', '1', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('29', '暑假作业答疑', 'http://www.dodoedu.com/Application/appEntrance/appId/1', '1', 'new', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('30', '家长加油站', 'http://jc.dodoedu.com/xl/Info/index?eid=7126', '3', 'self', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('31', '心理常识', 'http://jc.dodoedu.com/xl/Info/index?eid=7123', '3', 'self', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('32', '心理小故事', 'http://jc.dodoedu.com/xl/Info/index?eid=7122', '1', 'self', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('33', '教研动态', 'http://jc.dodoedu.com/xl/Info/index?eid=7124', '2', 'self', '1', 'GS0025', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('34', '多多社区', 'http://www.dodoedu.com', '1', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('35', '我的班级', 'http://www.dodoedu.com/class', '1', 'new', '1', 'GS0024', null, '543f25176fe7f.jpg', '0');
INSERT INTO `sys_app` VALUES ('36', '个人空间', 'http://www.dodoedu.com/Space', '1', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('37', '多多相册', 'http://www.dodoedu.com/Album/home', '1', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('38', '多多日记', 'http://www.dodoedu.com/Blog/home', '1', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('39', '合易心理小栈', 'http://www.dodoedu.com/Application/appEntrance/appId/62', '1', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('40', '寒暑假作业答疑', 'http://www.dodoedu.com/Application/appEntrance/appId/1', '1', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('41', '心理小故事', 'http://jc.dodoedu.com/xl/Info/index?eid=7122', '1', 'self', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('42', '我的班级', 'http://www.dodoedu.com/class', '2', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('43', '我的学校', 'http://www.dodoedu.com/school', '2', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('44', '多多社区', 'http://www.dodoedu.com', '2', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('45', '多多手机APP', 'http://mobile.dodoedu.com/', '2', 'new', '1', 'GS0024', null, 'resourceSp', '0');
INSERT INTO `sys_app` VALUES ('46', '教学大师', 'http://www.dodoedu.com/Application/appEntrance/appId/68', '2', 'new', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('47', '教研动态', 'http://jc.dodoedu.com/xl/Info/index?eid=7124', '2', 'self', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('48', '网盘', 'http://www.dodoedu.com/disk', '2', 'new', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('49', '多多翼学通', 'http://yxt.dodoedu.com', '3', 'new', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('50', '我的班级', 'http://www.dodoedu.com/class', '3', 'new', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('51', '我的学校', 'http://www.dodoedu.com/school', '3', 'new', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('52', '家长加油站', 'http://jc.dodoedu.com/xl/Info/index?eid=7126', '3', 'self', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('53', '心理常识', 'http://jc.dodoedu.com/xl/Info/index?eid=7123', '3', 'self', '1', 'GS0024', null, 'resourceSp', '1');
INSERT INTO `sys_app` VALUES ('55', 'aaaaa', 'http://www.163.com', '2', 'new', '1', 'GS0024', null, '543f21fb0fc05.JPG', '1');
INSERT INTO `sys_app` VALUES ('56', 'bbbbb', 'http://www.wuhan.net', '2', 'new', '1', 'GS0024', null, '543f22c8efdb8.gif', '1');
INSERT INTO `sys_app` VALUES ('58', 'acccc', 'http://wwww.163.com', '2', 'new', '1', 'GS0024', null, '543f25b471ba3.jpg', '1');

-- ----------------------------
-- Table structure for `sys_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `sys_attachment`;
CREATE TABLE `sys_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `file_path` varchar(50) NOT NULL COMMENT '文件路径',
  `file_name` varchar(50) NOT NULL COMMENT '原始文件名称',
  `user_id` char(21) NOT NULL COMMENT '所属用户',
  `file_ext` char(10) NOT NULL COMMENT '文件后缀',
  `is_image` tinyint(3) unsigned NOT NULL COMMENT '是否图片 1是',
  `create_time` int(10) NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8 COMMENT='附件资源表';

-- ----------------------------
-- Records of sys_attachment
-- ----------------------------
INSERT INTO `sys_attachment` VALUES ('2', 'test/535a0f9f29afc.txt', '3月技术交流_移动组.txt', 'm36359802300862200030', 'txt', '0', '1398411167');
INSERT INTO `sys_attachment` VALUES ('3', '/535a11a781314.png', '小站 725x300.png', 'l35789637007221180030', 'png', '1', '1398411687');
INSERT INTO `sys_attachment` VALUES ('4', '/535b131258478.jpg', 'd2.jpg', 'l35789637007221180030', 'jpg', '1', '1398477586');
INSERT INTO `sys_attachment` VALUES ('5', '/535ca0fe50d6e.GIF', 'a.GIF', 's35951247001862320095', 'gif', '1', '1398579454');
INSERT INTO `sys_attachment` VALUES ('6', 'activity/535ca57eb00fa.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398580606');
INSERT INTO `sys_attachment` VALUES ('7', '/535ca5cb083e7.jpg', '未标题-2 副本 3.jpg', 's35951247001862320095', 'jpg', '1', '1398580683');
INSERT INTO `sys_attachment` VALUES ('8', 'activity/535cab243c757.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398582052');
INSERT INTO `sys_attachment` VALUES ('9', '535cabf00339a.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398582255');
INSERT INTO `sys_attachment` VALUES ('10', '535cac5b69c46.png', '小站 725x300.png', 'l35789637007221180030', 'png', '1', '1398582363');
INSERT INTO `sys_attachment` VALUES ('11', 'activity/sm/535cadd9f36ae.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398582745');
INSERT INTO `sys_attachment` VALUES ('12', 'activity/sm/535cae305188a.png', '支付宝申请流程.png', '1', 'png', '1', '1398582832');
INSERT INTO `sys_attachment` VALUES ('13', 'activity/sm/535cae612bec5.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398582881');
INSERT INTO `sys_attachment` VALUES ('14', 'activity/sm/535caeb9da669.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398582969');
INSERT INTO `sys_attachment` VALUES ('15', 'activity/sm/535cb13c8c779.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398583612');
INSERT INTO `sys_attachment` VALUES ('16', 'activity/sm/535cb17d94d54.jpg', '未标题-2 副本 3.jpg', '1', 'jpg', '1', '1398583677');
INSERT INTO `sys_attachment` VALUES ('17', '535cc49787b60.txt', 'todolist.txt', 'l35789637007221180030', 'txt', '0', '1398588567');
INSERT INTO `sys_attachment` VALUES ('18', 'Array/535cc4c2e01bb.txt', 'todolist.txt', 'l35789637007221180030', 'txt', '0', '1398588610');
INSERT INTO `sys_attachment` VALUES ('19', '535cc5daef471.txt', 'todolist.txt', 'l35789637007221180030', 'txt', '0', '1398588890');
INSERT INTO `sys_attachment` VALUES ('20', '535cc689d7b90.txt', 'todolist.txt', 'l35789637007221180030', 'txt', '0', '1398589065');
INSERT INTO `sys_attachment` VALUES ('21', '535cc6dcc7014.txt', '多多学校的功能与特点.txt', '', 'txt', '0', '1398589148');
INSERT INTO `sys_attachment` VALUES ('22', '535cc724206c7.rar', 'html.rar', 'm36359802300862200030', 'rar', '0', '1398589220');
INSERT INTO `sys_attachment` VALUES ('23', '535cc74662b10.rar', 'ckplayer.rar', 'm36359802300862200030', 'rar', '0', '1398589254');
INSERT INTO `sys_attachment` VALUES ('24', '535cc7c081a19.txt', 'new  2.txt', 'm36359802300862200030', 'txt', '0', '1398589376');
INSERT INTO `sys_attachment` VALUES ('25', '535cc7c2e29a1.txt', 'todolist.txt', 'l35789637007221180030', 'txt', '0', '1398589378');
INSERT INTO `sys_attachment` VALUES ('26', '535cc8575a437.txt', '重复学籍识别码学生帐号处理.txt', 'l35789637007221180030', 'txt', '0', '1398589527');
INSERT INTO `sys_attachment` VALUES ('27', '_b12934f36e21c07b7ab9bc627f7d5ca4_m.jpg', '_b12934f36e21c07b7ab9bc627f7d5ca4_m.jpg', 'l35789637007221180030', 'jpg', '1', '1398591790');
INSERT INTO `sys_attachment` VALUES ('28', '_hearts.jpg', '_hearts.jpg', 'l35789637007221180030', 'jpg', '1', '1398591811');
INSERT INTO `sys_attachment` VALUES ('29', '535cd1ae36404.jpg', '_face_2_face.jpg', 'l35789637007221180030', 'jpg', '1', '1398591918');
INSERT INTO `sys_attachment` VALUES ('30', '535d9dd20c98a.png', '_截图2.png', 's35951247001862320095', 'png', '1', '1398644178');
INSERT INTO `sys_attachment` VALUES ('31', '535d9de519216.png', '_截图5.png', 's35951247001862320095', 'png', '1', '1398644197');
INSERT INTO `sys_attachment` VALUES ('32', '535da91b7bf85.png', '_截图4.png', 's35951247001862320095', 'png', '1', '1398647067');
INSERT INTO `sys_attachment` VALUES ('33', '535da9810b73b.png', '_应用截图1.png', 's35951247001862320095', 'png', '1', '1398647169');
INSERT INTO `sys_attachment` VALUES ('34', '535dae0e1876e.jpg', '_5.jpg', 's35951247001862320095', 'jpg', '1', '1398648334');
INSERT INTO `sys_attachment` VALUES ('35', '535dae83a04d0.jpg', '_4.jpg', 's35951247001862320095', 'jpg', '1', '1398648451');
INSERT INTO `sys_attachment` VALUES ('36', '535daebd105a7.jpg', '_3.jpg', 's35951247001862320095', 'jpg', '1', '1398648509');
INSERT INTO `sys_attachment` VALUES ('37', '535daf547b09a.jpg', '_1.jpg', 's35959634009790550015', 'jpg', '1', '1398648660');
INSERT INTO `sys_attachment` VALUES ('38', '535db3a5c6e66.jpg', '_1.jpg', 's35959634009790550015', 'jpg', '1', '1398649766');
INSERT INTO `sys_attachment` VALUES ('39', '535db40c78a03.jpg', '_1.jpg', 's35959634009790550015', 'jpg', '1', '1398649868');
INSERT INTO `sys_attachment` VALUES ('40', '535db4b6e7180.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398650039');
INSERT INTO `sys_attachment` VALUES ('41', '535db4ecc2bdf.jpg', '_02.jpg', 's35959634009790550015', 'jpg', '1', '1398650092');
INSERT INTO `sys_attachment` VALUES ('42', '535db5501ee10.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398650192');
INSERT INTO `sys_attachment` VALUES ('43', '535db57425dd6.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398650228');
INSERT INTO `sys_attachment` VALUES ('44', '535db86952bd5.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398650985');
INSERT INTO `sys_attachment` VALUES ('45', '535dc30fc1528.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398653711');
INSERT INTO `sys_attachment` VALUES ('46', 'activity/sm/535dc31a5afef.doc', '合易小栈文字描述.doc', 's35959634009790550015', 'doc', '0', '1398653722');
INSERT INTO `sys_attachment` VALUES ('47', 'activity/sm/535dc33089822.doc', '合易小栈文字描述.doc', 's35959634009790550015', 'doc', '0', '1398653744');
INSERT INTO `sys_attachment` VALUES ('48', '535dc33d47e7b.jpg', '_03.jpg', 's35959634009790550015', 'jpg', '1', '1398653757');
INSERT INTO `sys_attachment` VALUES ('49', '535dc3713641d.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398653809');
INSERT INTO `sys_attachment` VALUES ('50', '535dc375cc290.jpg', '_02.jpg', 's35959634009790550015', 'jpg', '1', '1398653814');
INSERT INTO `sys_attachment` VALUES ('51', 'activity/sm/535dc37bddd37.doc', '合易小栈文字描述.doc', 's35959634009790550015', 'doc', '0', '1398653819');
INSERT INTO `sys_attachment` VALUES ('52', '535df0e764c0b.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398665447');
INSERT INTO `sys_attachment` VALUES ('53', '535df104a82f2.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398665476');
INSERT INTO `sys_attachment` VALUES ('54', 'activity/sm/535df14b5f570.doc', '合易小栈文字描述.doc', 's35959634009790550015', 'doc', '0', '1398665547');
INSERT INTO `sys_attachment` VALUES ('55', '535dfadaa0660.jpg', '_06.jpg', 's35959634009790550015', 'jpg', '1', '1398667994');
INSERT INTO `sys_attachment` VALUES ('56', '535dfb061d964.jpg', '_03.jpg', 's35959634009790550015', 'jpg', '1', '1398668038');
INSERT INTO `sys_attachment` VALUES ('57', '535dfb4752136.jpg', '_01.jpg', 's35959634009790550015', 'jpg', '1', '1398668103');
INSERT INTO `sys_attachment` VALUES ('58', '535dfca1417a5.jpg', '_02.jpg', 's35959634009790550015', 'jpg', '1', '1398668449');
INSERT INTO `sys_attachment` VALUES ('59', '535dfdee3f7a7.png', '_截图2.png', 's35959634009790550015', 'png', '1', '1398668782');
INSERT INTO `sys_attachment` VALUES ('60', '535dffe400f6e.png', '_应用截图1.png', 's35959634009790550015', 'png', '1', '1398669284');
INSERT INTO `sys_attachment` VALUES ('61', '535e0239916d8.png', '_截图3.png', 's35959634009790550015', 'png', '1', '1398669881');
INSERT INTO `sys_attachment` VALUES ('62', '535e02b921b42.png', '_应用截图1.png', 's35959634009790550015', 'png', '1', '1398670009');
INSERT INTO `sys_attachment` VALUES ('63', '535e0baa5a136.png', '_应用截图1.png', 's35959634009790550015', 'png', '1', '1398672298');
INSERT INTO `sys_attachment` VALUES ('64', '535e131db84e7.png', '_截图3.png', 's35959634009790550015', 'png', '1', '1398674205');
INSERT INTO `sys_attachment` VALUES ('65', '535f185a26116.jpg', '_02.jpg', 's35959634009790550015', 'jpg', '1', '1398741082');
INSERT INTO `sys_attachment` VALUES ('66', '535f18a60163f.jpg', '_06.jpg', 's35959634009790550015', 'jpg', '1', '1398741158');
INSERT INTO `sys_attachment` VALUES ('67', '535f198b67c3d.png', '_合易小栈-应用图标.png', 's35959634009790550015', 'png', '1', '1398741387');
INSERT INTO `sys_attachment` VALUES ('68', '535f19999cbe7.jpg', '_06.jpg', 's35959634009790550015', 'jpg', '1', '1398741401');
INSERT INTO `sys_attachment` VALUES ('69', '535f19a6f34dd.jpg', '_03.jpg', 's35959634009790550015', 'jpg', '1', '1398741415');
INSERT INTO `sys_attachment` VALUES ('70', '535f488020085.jpg', '_02.jpg', 's35959634009790550015', 'jpg', '1', '1398753408');
INSERT INTO `sys_attachment` VALUES ('71', 'activity/sm/535f5f088819b.txt', 'id.txt', 's35959634009790550015', 'txt', '0', '1398759176');
INSERT INTO `sys_attachment` VALUES ('72', 'activity/sm/535f667137e36.doc', '合易小栈文字描述.doc', 's35959634009790550015', 'doc', '0', '1398761073');
INSERT INTO `sys_attachment` VALUES ('73', '5360a070b1e57.jpg', '_03.jpg', 's35951247001862320095', 'jpg', '1', '1398841456');
INSERT INTO `sys_attachment` VALUES ('74', '5360b7841d44f.jpg', '_未标题-2 副本 3.jpg', 's35959634009790550015', 'jpg', '1', '1398847364');
INSERT INTO `sys_attachment` VALUES ('75', '5360b7de05217.jpg', '_未标题-2 副本 3.jpg', 's35959634009790550015', 'jpg', '1', '1398847454');
INSERT INTO `sys_attachment` VALUES ('76', '53658d43a1778.jpg', '_未标题-2 副本 3.jpg', 's35951247001862320095', 'jpg', '1', '1399164227');
INSERT INTO `sys_attachment` VALUES ('77', '53658e2656cb5.png', '_应用截图1.png', 's35951247001862320095', 'png', '1', '1399164454');
INSERT INTO `sys_attachment` VALUES ('78', 'activity/sm/53658e900f7b9.doc', '合易小栈文字描述.doc', 's35951247001862320095', 'doc', '0', '1399164560');
INSERT INTO `sys_attachment` VALUES ('79', 'activity/sm/536606edc9e4f.txt', '新建文本文档 (2).txt', 's35951247001862320095', 'txt', '0', '1399195373');
INSERT INTO `sys_attachment` VALUES ('80', 'activity/sm/536606fba0fff.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195387');
INSERT INTO `sys_attachment` VALUES ('81', 'activity/sm/536607381cc5d.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195448');
INSERT INTO `sys_attachment` VALUES ('82', 'activity/sm/5366073d6d7d8.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195453');
INSERT INTO `sys_attachment` VALUES ('83', 'activity/sm/5366074248e9d.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195458');
INSERT INTO `sys_attachment` VALUES ('84', 'activity/sm/53660746499e2.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195462');
INSERT INTO `sys_attachment` VALUES ('85', 'activity/sm/5366074b8e6e5.txt', '新建文本文档 (2).txt', 's35951247001862320095', 'txt', '0', '1399195467');
INSERT INTO `sys_attachment` VALUES ('86', 'activity/sm/5366074fabec7.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195471');
INSERT INTO `sys_attachment` VALUES ('87', 'activity/sm/5366075966a44.txt', '新建文本文档 (2).txt', 's35951247001862320095', 'txt', '0', '1399195481');
INSERT INTO `sys_attachment` VALUES ('88', 'activity/sm/5366075f96f86.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195487');
INSERT INTO `sys_attachment` VALUES ('89', 'activity/sm/5366076531c2a.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399195493');
INSERT INTO `sys_attachment` VALUES ('90', 'activity/sm/5366076b60bea.txt', '新建文本文档 (2).txt', 's35951247001862320095', 'txt', '0', '1399195499');
INSERT INTO `sys_attachment` VALUES ('91', 'activity/sm/53675b932cf59.txt', 'teacher_test.txt', 's35951247001862320095', 'txt', '0', '1399282579');
INSERT INTO `sys_attachment` VALUES ('92', 'activity/sm/53675bda9e2a2.txt', 'teacher_test.txt', 's35951247001862320095', 'txt', '0', '1399282650');
INSERT INTO `sys_attachment` VALUES ('93', 'activity/sm/53675c3b8c54c.txt', '新建文本文档 (2).txt', 's35951247001862320095', 'txt', '0', '1399282747');
INSERT INTO `sys_attachment` VALUES ('94', 'activity/sm/53675c4aa99cd.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399282762');
INSERT INTO `sys_attachment` VALUES ('95', 'activity/sm/53675cb828c33.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399282872');
INSERT INTO `sys_attachment` VALUES ('96', 'activity/sm/53675ccade144.txt', '新建文本文档 (2).txt', 's35951247001862320095', 'txt', '0', '1399282890');
INSERT INTO `sys_attachment` VALUES ('97', 'activity/sm/53675ceaad142.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399282922');
INSERT INTO `sys_attachment` VALUES ('98', 'activity/sm/53675d18acb16.txt', '新建文本文档.txt', 's35951247001862320095', 'txt', '0', '1399282968');
INSERT INTO `sys_attachment` VALUES ('99', '53684c8ee6671.jpg', '_angry_birds.jpg', 'l35789637007221180030', 'jpg', '1', '1399344271');
INSERT INTO `sys_attachment` VALUES ('100', '5368ac9a37c7d.jpg', '_312.jpg', 's35959634009790550015', 'jpg', '1', '1399368858');
INSERT INTO `sys_attachment` VALUES ('101', 'activity/sm/5368b0b1cb668.ppt', '英语猜词游戏.ppt', 's35959634009790550015', 'ppt', '0', '1399369905');
INSERT INTO `sys_attachment` VALUES ('102', 'activity/sm/5368b1cb2e688.docx', '教师备课基本要求.docx', 's35959634009790550015', 'docx', '0', '1399370187');
INSERT INTO `sys_attachment` VALUES ('103', 'activity/sm/5368b22980656.ppt', '英语猜词游戏.ppt', 's35959634009790550015', 'ppt', '0', '1399370281');
INSERT INTO `sys_attachment` VALUES ('104', 'activity/sm/5368b23dcbf7f.docx', '11.docx', 's35959634009790550015', 'docx', '0', '1399370301');
INSERT INTO `sys_attachment` VALUES ('105', 'activity/sm/5368b25a0fa59.doc', '教材支撑平台专家交流会.doc', 's35959634009790550015', 'doc', '0', '1399370330');
INSERT INTO `sys_attachment` VALUES ('106', '536997a274c10.jpg', '_2.jpg', 's35951247001862320095', 'jpg', '1', '1399429026');
INSERT INTO `sys_attachment` VALUES ('107', 'activity/sm/5369a000581be.txt', '神州中联介绍.txt', 's35951247001862320095', 'txt', '0', '1399431168');
INSERT INTO `sys_attachment` VALUES ('108', '5369d9227a4b9.jpg', '_312.jpg', 's35959634009790550015', 'jpg', '1', '1399445794');
INSERT INTO `sys_attachment` VALUES ('109', 'activity/sm/5369dc671d9aa.ppt', '英语猜词游戏.ppt', 's35959634009790550015', 'ppt', '0', '1399446631');
INSERT INTO `sys_attachment` VALUES ('110', 'activity/sm/5369ddb55c285.pdf', '参考.pdf', 's35959634009790550015', 'pdf', '0', '1399446965');
INSERT INTO `sys_attachment` VALUES ('111', 'activity/sm/5369dec256567.docx', '多多教育社区网页标题设置V1.2.docx', 's35959634009790550015', 'docx', '0', '1399447234');
INSERT INTO `sys_attachment` VALUES ('112', 'activity/sm/5369dfa6ee76a.docx', '用户成长系统规则.docx', 's35959634009790550015', 'docx', '0', '1399447462');
INSERT INTO `sys_attachment` VALUES ('113', 'activity/sm/5369dfbcc7c3c.docx', '用户成长系统规则.docx', 's35959634009790550015', 'docx', '0', '1399447484');
INSERT INTO `sys_attachment` VALUES ('114', 'activity/sm/5369e03ab67f2.pptx', '新建 Microsoft Office PowerPoint 演示文稿.pptx', 's35959634009790550015', 'pptx', '0', '1399447610');
INSERT INTO `sys_attachment` VALUES ('115', '5369e359083cc.png', '_小站 725x300.png', 'l35789637007221180030', 'png', '1', '1399448409');
INSERT INTO `sys_attachment` VALUES ('116', '5369e37bb51ed.rar', 'up.rar', 'l35789637007221180030', 'rar', '0', '1399448443');
INSERT INTO `sys_attachment` VALUES ('117', '5369e39867a1f.rar', 'up.rar', 'l35789637007221180030', 'rar', '0', '1399448472');
INSERT INTO `sys_attachment` VALUES ('118', '5369e57ab8714.zip', '场地.zip', 'l35789637007221180030', 'zip', '0', '1399448954');
INSERT INTO `sys_attachment` VALUES ('119', '5369e9e899b71.doc', '最新简笔画图片.doc', 'l35789637007221180030', 'doc', '0', '1399450088');
INSERT INTO `sys_attachment` VALUES ('120', '5369ea71881b9.zip', 'camlistore-0.7.zip', 'l35789637007221180030', 'zip', '0', '1399450225');
INSERT INTO `sys_attachment` VALUES ('121', 'activity/sm/5369eb2d825b4.pdf', '小学样稿1.pdf', 's35951247001862320095', 'pdf', '0', '1399450413');
INSERT INTO `sys_attachment` VALUES ('122', 'activity/sm/5369f726448cd.pdf', '小学样稿1.pdf', 's35959634009790550015', 'pdf', '0', '1399453477');
INSERT INTO `sys_attachment` VALUES ('123', 'activity/sm/5369f736be998.doc', '最新简笔画图片.doc', 's35959634009790550015', 'doc', '0', '1399453494');
INSERT INTO `sys_attachment` VALUES ('124', 'activity/xl/536ae605eccdf.pdf', '小学样稿1.pdf', 't37880540906192790011', 'pdf', '0', '1399514629');
INSERT INTO `sys_attachment` VALUES ('125', 'activity/xl/536ae62c2f3cf.pdf', '小学样稿1.pdf', 't37880540906192790011', 'pdf', '0', '1399514668');
INSERT INTO `sys_attachment` VALUES ('126', '536ae63f9b9b4.jpg', '_学堂展示1.jpg', 't37880540906192790011', 'jpg', '1', '1399514687');
INSERT INTO `sys_attachment` VALUES ('127', '536ae6493a6eb.jpg', '_学堂展示2.jpg', 't37880540906192790011', 'jpg', '1', '1399514697');
INSERT INTO `sys_attachment` VALUES ('128', '53758007c4135.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400209416');
INSERT INTO `sys_attachment` VALUES ('129', '53758120cb011.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400209696');
INSERT INTO `sys_attachment` VALUES ('130', '537581581bb6b.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400209752');
INSERT INTO `sys_attachment` VALUES ('131', '537585194c587.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400210713');
INSERT INTO `sys_attachment` VALUES ('132', '537587b64b14e.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400211382');
INSERT INTO `sys_attachment` VALUES ('133', '537aa590b7272.jpg', '_725x300.jpg', 't37880655900186820006', 'jpg', '1', '1400546704');
INSERT INTO `sys_attachment` VALUES ('134', '537c4a60cff37.JPG', '广播体操.JPG', 'w36451978107373010061', 'jpg', '1', '1400654432');
INSERT INTO `sys_attachment` VALUES ('135', '537c580a4e283.jpg', '包子.jpg', 'w36451978107373010061', 'jpg', '1', '1400657930');
INSERT INTO `sys_attachment` VALUES ('136', '537c58422958d.jpg', '包子.jpg', 'w36451978107373010061', 'jpg', '1', '1400657986');
INSERT INTO `sys_attachment` VALUES ('137', '537c58b897f95.jpg', '包子.jpg', 'w36451978107373010061', 'jpg', '1', '1400658104');
INSERT INTO `sys_attachment` VALUES ('138', '537c5a231b588.png', 'QQ截图20140510115953.png', 'w36451978107373010061', 'png', '1', '1400658467');
INSERT INTO `sys_attachment` VALUES ('139', '537d556482500.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400722788');
INSERT INTO `sys_attachment` VALUES ('140', '537d5653d2bf9.txt', '重复学籍识别码学生帐号处理.txt', 'w36451978107373010061', 'txt', '0', '1400723027');
INSERT INTO `sys_attachment` VALUES ('141', '537d56d420e55.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723156');
INSERT INTO `sys_attachment` VALUES ('142', '537d56e134663.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723169');
INSERT INTO `sys_attachment` VALUES ('143', '537d5763b0df4.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723300');
INSERT INTO `sys_attachment` VALUES ('144', '537d579328398.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723347');
INSERT INTO `sys_attachment` VALUES ('145', '537d57b12242f.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723377');
INSERT INTO `sys_attachment` VALUES ('146', '537d57d4252b0.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723412');
INSERT INTO `sys_attachment` VALUES ('147', '537d57dfc286b.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723423');
INSERT INTO `sys_attachment` VALUES ('148', '537d5821ddf96.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723490');
INSERT INTO `sys_attachment` VALUES ('149', '537d58830c61d.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723587');
INSERT INTO `sys_attachment` VALUES ('150', '537d58e63e048.txt', '《Go语言编程》.txt', 'w36451978107373010061', 'txt', '0', '1400723686');
INSERT INTO `sys_attachment` VALUES ('151', '537d5921b79a8.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723746');
INSERT INTO `sys_attachment` VALUES ('152', '537d5978cbf66.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723833');
INSERT INTO `sys_attachment` VALUES ('153', '537d59a977155.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400723881');
INSERT INTO `sys_attachment` VALUES ('154', '537d5a3b7794b.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400724027');
INSERT INTO `sys_attachment` VALUES ('155', '537d5af4b2d58.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400724212');
INSERT INTO `sys_attachment` VALUES ('156', '537d5bcfe7690.jpg', '_uboatwatch.jpg', 'm36359802300862200030', 'jpg', '1', '1400724432');
INSERT INTO `sys_attachment` VALUES ('157', '537d6ccc87c17.txt', '《Go语言编程》.txt', 'w36451978107373010061', 'txt', '0', '1400728780');
INSERT INTO `sys_attachment` VALUES ('159', '537edf745fd16.jpg', '背景墙.jpg', 'w36451978107373010061', 'jpg', '1', '1400823668');
INSERT INTO `sys_attachment` VALUES ('161', '53843a3839e56.jpg', '_学堂展示1.jpg', 't37880655900186820006', 'jpg', '1', '1401174584');
INSERT INTO `sys_attachment` VALUES ('162', '538448167731a.jpg', '_学堂展示2.jpg', 't37880655900186820006', 'jpg', '1', '1401178134');
INSERT INTO `sys_attachment` VALUES ('163', '53854e16c043b.png', 'QQ截图20140510115953.png', 'w36451978107373010061', 'png', '1', '1401245206');
INSERT INTO `sys_attachment` VALUES ('164', '5386d019851b0.png', 'QQ截图20140514152055.png', 'w36451978107373010061', 'png', '1', '1401344025');
INSERT INTO `sys_attachment` VALUES ('165', '5386d0321b35d.jpg', 'Hydrangeas.jpg', 'w36451978107373010061', 'jpg', '1', '1401344050');
INSERT INTO `sys_attachment` VALUES ('166', '538834d45de86.jpg', '_学堂展示1.jpg', 'm36359802300862200030', 'jpg', '1', '1401435348');
INSERT INTO `sys_attachment` VALUES ('167', 'activity/sm/53883539dd707.docx', '多多教育社区网页标题设置V1.2.docx', 'm36359802300862200030', 'docx', '0', '1401435449');
INSERT INTO `sys_attachment` VALUES ('168', 'activity/sm/538835425c24d.docx', '多多教育社区网页标题设置V1.2.docx', 'm36359802300862200030', 'docx', '0', '1401435458');
INSERT INTO `sys_attachment` VALUES ('169', 'activity/xl/538ec8e18c495.pdf', '小学样稿1.pdf', 't37880655900186820006', 'pdf', '0', '1401866465');
INSERT INTO `sys_attachment` VALUES ('170', '53900b4bcf1fb.png', '_QQ截图20140605141631.png', '', 'png', '1', '1401949004');
INSERT INTO `sys_attachment` VALUES ('171', '53900c4fe4447.png', '_QQ截图20140605142053.png', '', 'png', '1', '1401949264');
INSERT INTO `sys_attachment` VALUES ('172', '539906ea3156f.jpg', '_spr.jpg', 'm36359802300862200030', 'jpg', '1', '1402537706');
INSERT INTO `sys_attachment` VALUES ('173', '539e52fb03da1.jpg', '_nengyuanzhiguan.jpg', 'm36359802300862200030', 'jpg', '1', '1402884859');
INSERT INTO `sys_attachment` VALUES ('174', '539e562101044.jpg', '_Jellyfish.jpg', 'm36359802300862200030', 'jpg', '1', '1402885665');
INSERT INTO `sys_attachment` VALUES ('175', '539e57c6c2f63.jpg', 'Jellyfish.jpg', 'w36451978107373010061', 'jpg', '1', '1402886086');
INSERT INTO `sys_attachment` VALUES ('176', '539e59e1495f8.jpg', '_Jellyfish.jpg', 'w36451978107373010061', 'jpg', '1', '1402886625');
INSERT INTO `sys_attachment` VALUES ('177', '539e5a8bb08e8.jpg', 'Jellyfish.jpg', 'w36451978107373010061', 'jpg', '1', '1402886795');
INSERT INTO `sys_attachment` VALUES ('178', '539e5bbc731fb.jpg', '_Desert.jpg', 'm36359802300862200030', 'jpg', '1', '1402887100');
INSERT INTO `sys_attachment` VALUES ('179', '539e5bf1b2185.jpg', '_Jellyfish.jpg', 'm36359802300862200030', 'jpg', '1', '1402887153');
INSERT INTO `sys_attachment` VALUES ('180', '539e5c6d025b0.jpg', '_八仙花八仙花八仙花八仙花八仙花八仙花八仙花八仙花八仙花.jpg', 'm36359802300862200030', 'jpg', '1', '1402887277');
INSERT INTO `sys_attachment` VALUES ('181', 'activity/xl/539e5e46486bc.jpg', 'Jellyfish.jpg', 'm36359802300862200030', 'jpg', '1', '1402887750');
INSERT INTO `sys_attachment` VALUES ('182', 'activity/xl/539e5e8db10f4.jpg', 'Jellyfish.jpg', 'm36359802300862200030', 'jpg', '1', '1402887821');
INSERT INTO `sys_attachment` VALUES ('183', '539eae1331bde.png', 'QQ截图20140510115953.png', 'w36451978107373010061', 'png', '1', '1402908179');
INSERT INTO `sys_attachment` VALUES ('184', '539f9cf0c45d8.JPG', '广播体操.JPG', 'w36451978107373010061', 'JPG', '0', '1402969328');
INSERT INTO `sys_attachment` VALUES ('185', 'activity/sm/53a0e862a9ab1.jpg', '学堂展示1.jpg', 'm36359802300862200030', 'jpg', '1', '1403054178');
INSERT INTO `sys_attachment` VALUES ('186', '53abcada5aa28.jpg', '背景墙.jpg', 'w36451978107373010061', 'jpg', '1', '1403767514');
INSERT INTO `sys_attachment` VALUES ('187', '54193fbc90c0e.png', 'logo.png', 'm36359802300862200030', 'png', '1', '1410940860');
INSERT INTO `sys_attachment` VALUES ('188', '543f1c9cdfcb8.log', 'hs_err_pid1996.log', 'm36359802300862200030', 'log', '0', '1413422236');
INSERT INTO `sys_attachment` VALUES ('189', '543f1d088a170.txt', 'id.txt', 'm36359802300862200030', 'txt', '0', '1413422344');
INSERT INTO `sys_attachment` VALUES ('190', '543f1eacecab6.gif', '250_250_QQ图片20130821153021.gif', 'm36359802300862200030', 'gif', '1', '1413422765');
INSERT INTO `sys_attachment` VALUES ('191', '543f1f16ca1f8.JPG', '_二货.JPG', 'm36359802300862200030', 'JPG', '0', '1413422871');
INSERT INTO `sys_attachment` VALUES ('192', '543f21fb0fc05.JPG', '_二货.JPG', 'm36359802300862200030', 'JPG', '0', '1413423611');
INSERT INTO `sys_attachment` VALUES ('193', '543f22c8efdb8.gif', '250_250_QQ图片20130821153021.gif', 'm36359802300862200030', 'gif', '1', '1413423817');
INSERT INTO `sys_attachment` VALUES ('194', '543f25176fe7f.jpg', '_1619575_605180949568202_461782412_n.jpg', 'm36359802300862200030', 'jpg', '1', '1413424407');
INSERT INTO `sys_attachment` VALUES ('195', '543f2568eaeb6.jpg', '_QQ图片20131106113017.jpg', 'm36359802300862200030', 'jpg', '1', '1413424489');
INSERT INTO `sys_attachment` VALUES ('196', '543f25b471ba3.jpg', '_1618509_740638092627858_277978684_n y.jpg', 'm36359802300862200030', 'jpg', '1', '1413424564');
INSERT INTO `sys_attachment` VALUES ('197', 'activity/sm/54408c951d203.JPG', '二货.JPG', 'm36359802300862200030', 'JPG', '0', '1413516436');
INSERT INTO `sys_attachment` VALUES ('198', '54586eed0dd77.gif', '124_385647_3bdcd23a23ad448.gif', 'w36451978107373010061', 'gif', '1', '1415081709');

-- ----------------------------
-- Table structure for `sys_group`
-- ----------------------------
DROP TABLE IF EXISTS `sys_group`;
CREATE TABLE `sys_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_type` int(11) NOT NULL DEFAULT '0' COMMENT '类型',
  `group_pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级用户组',
  `group_title` varchar(50) NOT NULL DEFAULT '' COMMENT '用户组名',
  `group_description` varchar(250) NOT NULL DEFAULT '' COMMENT '用户组描述',
  `group_module_list` varchar(100) NOT NULL DEFAULT '' COMMENT '模块列表',
  `group_gd` int(11) NOT NULL DEFAULT '0' COMMENT '固定',
  `group_isok` int(11) NOT NULL DEFAULT '0' COMMENT '是否有效',
  `group_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `group_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `xk` char(10) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COMMENT='系统用户组';

-- ----------------------------
-- Records of sys_group
-- ----------------------------
INSERT INTO `sys_group` VALUES ('1', '0', '0', '系统管理员', '拥有最高权限', '', '1', '1', '1', '1382948368', 'sm');
INSERT INTO `sys_group` VALUES ('2', '0', '0', '参观者', '只有查看的功能', '', '1', '1', '2', '1382589377', 'sm');
INSERT INTO `sys_group` VALUES ('3', '0', '0', '课程老师', '课题组人员', '', '1', '1', '4', '1382948359', 'sm');
INSERT INTO `sys_group` VALUES ('4', '0', '0', '学生', '只能登录前台', '', '1', '1', '3', '1392951252', 'sm');
INSERT INTO `sys_group` VALUES ('132', '0', '0', '系统管理员', '拥有最高权限', '', '1', '1', '1', '0', 'xl');

-- ----------------------------
-- Table structure for `sys_log`
-- ----------------------------
DROP TABLE IF EXISTS `sys_log`;
CREATE TABLE `sys_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `log_title` varchar(250) NOT NULL DEFAULT '' COMMENT '日志标题',
  `log_module_id` int(11) NOT NULL DEFAULT '0' COMMENT '模块ID',
  `log_module_controller` varchar(50) NOT NULL DEFAULT '' COMMENT '模块控制器',
  `log_module_action` varchar(50) NOT NULL DEFAULT '' COMMENT '模块操作',
  `log_user_uid` varchar(30) NOT NULL DEFAULT '' COMMENT '学籍ID',
  `log_isok` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  `log_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `log_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `xk` char(10) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统日志表';

-- ----------------------------
-- Records of sys_log
-- ----------------------------

-- ----------------------------
-- Table structure for `sys_module`
-- ----------------------------
DROP TABLE IF EXISTS `sys_module`;
CREATE TABLE `sys_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `module_pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级模块',
  `module_type` int(11) NOT NULL DEFAULT '0' COMMENT '模块类型',
  `module_title` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `module_controller` varchar(150) NOT NULL DEFAULT '' COMMENT '控制器',
  `module_action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作',
  `module_url` varchar(50) NOT NULL DEFAULT '' COMMENT '自定义URL地址',
  `module_node` varchar(250) NOT NULL DEFAULT '' COMMENT '权限节点',
  `module_icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标风格',
  `module_isok` int(11) NOT NULL DEFAULT '0' COMMENT '是否启用',
  `module_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `module_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `xk` char(10) DEFAULT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8 COMMENT='系统模块表';

-- ----------------------------
-- Records of sys_module
-- ----------------------------
INSERT INTO `sys_module` VALUES ('2', '3', '1', '模块管理', 'Modules_Admin_Controllers_Module', 'indexAction', '', 'add,edit,del', 'menu-module', '1', '2', '1381484219', 'sm');
INSERT INTO `sys_module` VALUES ('3', '0', '1', '系统管理', ' ', ' ', '', '', 'icon_dataStatisticsGray', '1', '11', '1377850143', 'sm');
INSERT INTO `sys_module` VALUES ('4', '3', '1', '用户管理', 'Modules_Admin_Controllers_User', 'indexAction', '', '', 'menu-user', '1', '4', '1381396521', 'sm');
INSERT INTO `sys_module` VALUES ('5', '3', '1', '用户组管理', 'Modules_Admin_Controllers_UserGroup', 'indexAction', '', 'add,edit,del', 'menu-group', '1', '7', '1381484234', 'sm');
INSERT INTO `sys_module` VALUES ('129', '0', '1', '运营管理', 'log', 'tee', '', '', '', '0', '140', '1394779171', 'sm');
INSERT INTO `sys_module` VALUES ('130', '129', '1', '日志', 'log', '', '', '', '', '1', '131', '1394779206', 'sm');
INSERT INTO `sys_module` VALUES ('131', '129', '1', '报错', 'error', '', '', '', '', '1', '130', '1394779229', 'sm');
INSERT INTO `sys_module` VALUES ('132', '129', '1', '附件', 'attach', '', '', '', '', '1', '132', '1394779243', 'sm');
INSERT INTO `sys_module` VALUES ('152', '0', '1', '研训管理', 'Activity', 'index', '', '', 'icon_infoManageGray', '1', '3', '0', 'sm');
INSERT INTO `sys_module` VALUES ('153', '152', '1', '活动列表', 'Modules_Admin_Controllers_Activity', 'activityListAction', '', '', 'icon_infoManageGray', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('156', '0', '1', '测评管理', 'Modules_Admin_Controllers_Evaluate', 'index', '', '', 'icon_infoManageGray', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('157', '156', '1', '测评列表', 'Modules_Admin_Controllers_Evaluate', 'evaluateList', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('158', '0', '1', '学习管理', 'Modules_Admin_Controllers_StudyResource', 'index', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('159', '158', '1', '增加学习资源', 'Modules_Admin_Controllers_StudyResource', 'studyResourceListAction', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('160', '152', '1', '活动申请管理', 'Modules_Admin_Controllers_Activity', 'applyListAction', '', '', 'icon_infoManageGray', '1', '156', '0', 'sm');
INSERT INTO `sys_module` VALUES ('161', '152', '1', '研训统计', 'Modules_Admin_Controllers_Activity', 'activityStatisticsAction', '', '', '', '1', '156', '0', 'sm');
INSERT INTO `sys_module` VALUES ('162', '152', '1', '研训成员统计', 'Modules_Admin_Controllers_Activity', 'activityMemberStatisticsAction', '', '', '', '1', '156', '0', 'sm');
INSERT INTO `sys_module` VALUES ('163', '152', '1', '研训分析', 'Modules_Admin_Controllers_Activity', 'activityAnalysisAction', '', '', '', '1', '156', '0', 'sm');
INSERT INTO `sys_module` VALUES ('195', '196', '1', '模块管理', 'Modules_Admin_Controllers_Module', 'indexAction', '', '', 'menu-module', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('196', '0', '1', '系统管理', ' ', ' ', '', '', 'icon_dataStatisticsGray', '1', '195', '0', 'xl');
INSERT INTO `sys_module` VALUES ('197', '196', '1', '用户管理', 'Modules_Admin_Controllers_User', 'indexAction', '', '', 'menu-user', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('198', '196', '1', '用户组管理', 'Modules_Admin_Controllers_UserGroup', 'indexAction', '', '', 'menu-group', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('203', '0', '1', '研训管理', 'Activity', 'index', '', '', 'icon_infoManageGray', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('204', '203', '1', '活动列表', 'Modules_Admin_Controllers_Activity', 'activityListAction', '', '', 'icon_infoManageGray', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('205', '0', '1', '测评管理', 'Modules_Admin_Controllers_Evaluate', 'index', '', '', 'icon_infoManageGray', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('206', '205', '1', '测评列表', 'Modules_Admin_Controllers_Evaluate', 'evaluateList', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('207', '0', '1', '学习管理', 'Modules_Admin_Controllers_StudyResource', 'index', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('208', '207', '1', '增加学习资源', 'Modules_Admin_Controllers_StudyResource', 'studyResourceListAction', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('209', '203', '1', '活动申请管理', 'Modules_Admin_Controllers_Activity', 'applyListAction', '', '', 'icon_infoManageGray', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('210', '203', '1', '研训统计', 'Modules_Admin_Controllers_Activity', 'activityStatisticsAction', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('211', '203', '1', '研训成员统计', 'Modules_Admin_Controllers_Activity', 'activityMemberStatisticsAction', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('212', '203', '1', '研训分析', 'Modules_Admin_Controllers_Activity', 'activityAnalysisAction', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('226', '203', '1', '研训小站抓取', 'Modules_Admin_Controllers_Activity', 'activitySiteListAction', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('227', '196', '1', '应用管理', 'Modules_Admin_Controllers_App', 'indexAction', '', '', 'icon-set', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('228', '0', '1', '游戏中心', 'game', 'index', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('229', '228', '1', '游戏分类', 'Modules_Admin_Controolers_GameCate', 'indexAction', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('230', '228', '1', '游戏管理', 'Modules_Admin_Controolers_Game', 'indexAction', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('231', '0', '1', '课程统计', 'Modules_Admin_Controllers_Course', 'teach', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('232', '231', '1', '开课率统计', 'Modules_Admin_Controllers_Course', 'teach', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('233', '231', '1', '各地课程情况统计', 'Modules_Admin_Controllers_Course', 'situation', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('234', '231', '1', '课程评价', 'Modules_Admin_Controllers_Course', 'evaluate', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('235', '3', '1', '应用管理', 'Modules_Admin_Controllers_App', 'indexAction', '', '', 'icon-set', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('236', '0', '1', '游戏中心', 'game', 'index', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('237', '236', '1', '游戏分类', 'Modules_Admin_Controolers_GameCate', 'indexAction', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('238', '236', '1', '游戏管理', 'Modules_Admin_Controolers_Game', 'indexAction', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('239', '0', '1', '课程统计', 'Course', 'index', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('241', '239', '1', '课程评价', 'Modules_Admin_Controllers_Course', 'evaluate', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('242', '239', '1', '各地课程情况统计', 'Modules_Admin_Controllers_Course', 'situation', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('243', '239', '1', '开课率统计', 'Modules_Admin_Controllers_Course', 'teach', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('245', '152', '1', '研训小站抓取', 'Modules_Admin_Controllers_Activity', 'activitySiteListAction', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('246', '152', '1', '研训小站抓取', 'Modules_Admin_Controllers_Activity', 'activitySiteListAction', '', '', '', '1', '0', '0', 'sm');
INSERT INTO `sys_module` VALUES ('247', '207', '1', '学习资源配置', 'Modules_Admin_Controllers_StudyType', 'studyTypeList', '', '', '', '1', '0', '0', 'xl');
INSERT INTO `sys_module` VALUES ('248', '158', '1', '学习资源配置', 'Modules_Admin_Controllers_StudyType', 'studyTypeList', '', '', '', '1', '0', '0', 'sm');

-- ----------------------------
-- Table structure for `sys_purview`
-- ----------------------------
DROP TABLE IF EXISTS `sys_purview`;
CREATE TABLE `sys_purview` (
  `purview_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `purview_group_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户组',
  `purview_type` int(11) NOT NULL DEFAULT '0' COMMENT '权限类型',
  `purview_module_id` int(11) NOT NULL DEFAULT '0' COMMENT '模块ID',
  `purview_node` varchar(100) NOT NULL DEFAULT '' COMMENT '权限节点',
  PRIMARY KEY (`purview_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1357 DEFAULT CHARSET=utf8 COMMENT='用户权限表';

-- ----------------------------
-- Records of sys_purview
-- ----------------------------
INSERT INTO `sys_purview` VALUES ('26', '3', '0', '5', 'run');
INSERT INTO `sys_purview` VALUES ('1277', '132', '0', '203', '');
INSERT INTO `sys_purview` VALUES ('1278', '132', '0', '204', '');
INSERT INTO `sys_purview` VALUES ('1279', '132', '0', '209', '');
INSERT INTO `sys_purview` VALUES ('1280', '132', '0', '210', '');
INSERT INTO `sys_purview` VALUES ('1281', '132', '0', '211', '');
INSERT INTO `sys_purview` VALUES ('1282', '132', '0', '212', '');
INSERT INTO `sys_purview` VALUES ('1283', '132', '0', '226', '');
INSERT INTO `sys_purview` VALUES ('1284', '132', '0', '205', '');
INSERT INTO `sys_purview` VALUES ('1285', '132', '0', '206', '');
INSERT INTO `sys_purview` VALUES ('1286', '132', '0', '207', '');
INSERT INTO `sys_purview` VALUES ('1287', '132', '0', '208', '');
INSERT INTO `sys_purview` VALUES ('1288', '132', '0', '247', '');
INSERT INTO `sys_purview` VALUES ('1289', '132', '0', '228', '');
INSERT INTO `sys_purview` VALUES ('1290', '132', '0', '229', '');
INSERT INTO `sys_purview` VALUES ('1291', '132', '0', '230', '');
INSERT INTO `sys_purview` VALUES ('1292', '132', '0', '239', '');
INSERT INTO `sys_purview` VALUES ('1293', '132', '0', '241', '');
INSERT INTO `sys_purview` VALUES ('1294', '132', '0', '242', '');
INSERT INTO `sys_purview` VALUES ('1295', '132', '0', '243', '');
INSERT INTO `sys_purview` VALUES ('1296', '132', '0', '196', '');
INSERT INTO `sys_purview` VALUES ('1297', '132', '0', '195', '');
INSERT INTO `sys_purview` VALUES ('1298', '132', '0', '197', '');
INSERT INTO `sys_purview` VALUES ('1299', '132', '0', '198', '');
INSERT INTO `sys_purview` VALUES ('1300', '132', '0', '227', '');
INSERT INTO `sys_purview` VALUES ('1329', '1', '0', '156', '');
INSERT INTO `sys_purview` VALUES ('1330', '1', '0', '157', '');
INSERT INTO `sys_purview` VALUES ('1331', '1', '0', '158', '');
INSERT INTO `sys_purview` VALUES ('1332', '1', '0', '159', '');
INSERT INTO `sys_purview` VALUES ('1333', '1', '0', '248', '');
INSERT INTO `sys_purview` VALUES ('1334', '1', '0', '231', '');
INSERT INTO `sys_purview` VALUES ('1335', '1', '0', '232', '');
INSERT INTO `sys_purview` VALUES ('1336', '1', '0', '233', '');
INSERT INTO `sys_purview` VALUES ('1337', '1', '0', '234', '');
INSERT INTO `sys_purview` VALUES ('1338', '1', '0', '236', '');
INSERT INTO `sys_purview` VALUES ('1339', '1', '0', '237', '');
INSERT INTO `sys_purview` VALUES ('1340', '1', '0', '238', '');
INSERT INTO `sys_purview` VALUES ('1341', '1', '0', '152', '');
INSERT INTO `sys_purview` VALUES ('1342', '1', '0', '153', '');
INSERT INTO `sys_purview` VALUES ('1343', '1', '0', '245', '');
INSERT INTO `sys_purview` VALUES ('1344', '1', '0', '160', '');
INSERT INTO `sys_purview` VALUES ('1345', '1', '0', '161', '');
INSERT INTO `sys_purview` VALUES ('1346', '1', '0', '162', '');
INSERT INTO `sys_purview` VALUES ('1347', '1', '0', '163', '');
INSERT INTO `sys_purview` VALUES ('1348', '1', '0', '3', '');
INSERT INTO `sys_purview` VALUES ('1349', '1', '0', '235', '');
INSERT INTO `sys_purview` VALUES ('1350', '1', '0', '2', '');
INSERT INTO `sys_purview` VALUES ('1351', '1', '0', '4', '');
INSERT INTO `sys_purview` VALUES ('1352', '1', '0', '5', '');
INSERT INTO `sys_purview` VALUES ('1353', '1', '0', '129', '');
INSERT INTO `sys_purview` VALUES ('1354', '1', '0', '131', '');
INSERT INTO `sys_purview` VALUES ('1355', '1', '0', '130', '');
INSERT INTO `sys_purview` VALUES ('1356', '1', '0', '132', '');

-- ----------------------------
-- Table structure for `sys_user`
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(50) NOT NULL DEFAULT '' COMMENT '用户密码',
  `user_realname` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `user_group_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户组',
  `user_value` int(11) NOT NULL DEFAULT '0' COMMENT '学分',
  `user_star` int(11) NOT NULL DEFAULT '0' COMMENT '星星',
  `user_uid` varchar(30) NOT NULL DEFAULT '' COMMENT '学籍ID',
  `user_avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '用户头象',
  `user_school_id` int(11) NOT NULL DEFAULT '0' COMMENT '学校ID',
  `user_school_name` varchar(50) NOT NULL DEFAULT '' COMMENT '学校名称',
  `user_gd` int(1) NOT NULL DEFAULT '0',
  `user_isok` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `user_order` int(11) NOT NULL DEFAULT '0',
  `user_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `xk` char(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `用户-用户组关联` (`user_group_id`),
  KEY `uid` (`user_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=449 DEFAULT CHARSET=utf8 COMMENT='系统管理用户表';

-- ----------------------------
-- Records of sys_user
-- ----------------------------
INSERT INTO `sys_user` VALUES ('1', 'admin', '123456', ' 游龙', '1', '0', '0', 'm36359802300862200030', '', '0', '', '0', '1', '0', '1378429535', 'sm');
INSERT INTO `sys_user` VALUES ('38', 'sizzflair', '123456', '何诚', '1', '0', '0', 's35951247001862320095', 'http://files.dodoedu.com/photo/20131363829169-15-2663-7768-64.jpg', '121502', '长江数字学校1', '0', '1', '0', '0', 'sm');
INSERT INTO `sys_user` VALUES ('443', 'admin', '123456', 'asdf', '132', '0', '0', 'm36359802300862200030', '', '0', 'asdfasd', '0', '1', '0', '0', 'xl');
INSERT INTO `sys_user` VALUES ('448', 'sizz', '123456', '多多乙', '132', '0', '0', 't37880655900186820006', '', '0', '长江数字学校', '0', '1', '0', '0', 'xl');

-- ----------------------------
-- Table structure for `teacher_textbook`
-- ----------------------------
DROP TABLE IF EXISTS `teacher_textbook`;
CREATE TABLE `teacher_textbook` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `teacher_textbook_user_id` varchar(50) NOT NULL COMMENT '教师的用户ID',
  `teacher_textbook_subject_code` varchar(10) NOT NULL COMMENT '''活动科目：GS0024：生命安全,GS0025：心理健康'',',
  `teacher_textbook_publisher_code` varchar(10) NOT NULL COMMENT '教材版本：v01人教版v02鄂教版v11鄂科版',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_index` (`teacher_textbook_user_id`,`teacher_textbook_subject_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='教师教材版本';

-- ----------------------------
-- Records of teacher_textbook
-- ----------------------------
INSERT INTO `teacher_textbook` VALUES ('1', 'w36451978107373010061', 'GS0025', 'v11');
INSERT INTO `teacher_textbook` VALUES ('2', 't37880655900186820006', 'GS0025', 'v11');
INSERT INTO `teacher_textbook` VALUES ('3', 'w35935506404531840025', 'GS0025', 'v01');
INSERT INTO `teacher_textbook` VALUES ('4', 'm36359802300862200030', 'GS0025', 'v11');
INSERT INTO `teacher_textbook` VALUES ('5', 'a37758282708118320005', 'GS0025', 'v11');
INSERT INTO `teacher_textbook` VALUES ('6', 'm36359802300862200030', 'GS0024', 'v11');
INSERT INTO `teacher_textbook` VALUES ('7', 't37880655900186820006', 'GS0024', 'v11');
INSERT INTO `teacher_textbook` VALUES ('8', 'w36451978107373010061', 'GS0024', 'v11');
INSERT INTO `teacher_textbook` VALUES ('9', 'h37309001009024890038', 'GS0024', 'v11');

-- ----------------------------
-- Table structure for `user_app`
-- ----------------------------
DROP TABLE IF EXISTS `user_app`;
CREATE TABLE `user_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` char(30) NOT NULL DEFAULT '' COMMENT '用户ID',
  `app_id` int(11) DEFAULT NULL COMMENT '应用ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userIdAppIdIndex` (`user_id`,`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=utf8 COMMENT='用户应用表';

-- ----------------------------
-- Records of user_app
-- ----------------------------
INSERT INTO `user_app` VALUES ('77', 'l35789637007221180030', '21');
INSERT INTO `user_app` VALUES ('1', 'm36359802300862200030', '1');
INSERT INTO `user_app` VALUES ('2', 'm36359802300862200030', '3');
INSERT INTO `user_app` VALUES ('91', 'm36359802300862200030', '11');
INSERT INTO `user_app` VALUES ('92', 's35951247001862320095', '19');
INSERT INTO `user_app` VALUES ('98', 's35951247001862320095', '22');
INSERT INTO `user_app` VALUES ('280', 's35951247001862320095', '36');
INSERT INTO `user_app` VALUES ('35', 't37880655900186820006', '11');
INSERT INTO `user_app` VALUES ('36', 't37880655900186820006', '12');
INSERT INTO `user_app` VALUES ('33', 'w35935506404531840025', '13');
INSERT INTO `user_app` VALUES ('38', 'w35935506404531840025', '14');
INSERT INTO `user_app` VALUES ('278', 'w35935506404531840025', '55');
INSERT INTO `user_app` VALUES ('277', 'w35935506404531840025', '56');
INSERT INTO `user_app` VALUES ('16', 'w36451978107373010061', '0');
INSERT INTO `user_app` VALUES ('107', 'w36451978107373010061', '11');
INSERT INTO `user_app` VALUES ('93', 'w36451978107373010061', '12');
INSERT INTO `user_app` VALUES ('108', 'w36451978107373010061', '13');
INSERT INTO `user_app` VALUES ('109', 'w36451978107373010061', '14');
INSERT INTO `user_app` VALUES ('261', 'w36451978107373010061', '42');
INSERT INTO `user_app` VALUES ('274', 'w36451978107373010061', '44');
INSERT INTO `user_app` VALUES ('275', 'w36451978107373010061', '46');
INSERT INTO `user_app` VALUES ('276', 'w36451978107373010061', '47');
INSERT INTO `user_app` VALUES ('263', 'w36451978107373010061', '55');
INSERT INTO `user_app` VALUES ('279', 'w36451978107373010061', '56');
INSERT INTO `user_app` VALUES ('273', 'w36451978107373010061', '58');

-- ----------------------------
-- Table structure for `xl_course`
-- ----------------------------
DROP TABLE IF EXISTS `xl_course`;
CREATE TABLE `xl_course` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '课程ID',
  `class_id` int(10) NOT NULL,
  `course_remark` varchar(200) DEFAULT NULL COMMENT '课程备注',
  `teacher_user_id` char(21) NOT NULL,
  `course_week` enum('7','6','5','4','3','2','1') NOT NULL COMMENT '开课星期',
  `course_date` char(10) NOT NULL COMMENT '开课日期',
  `course_sort` tinyint(2) NOT NULL COMMENT '开课的节次',
  `lesson_folder_id` int(10) DEFAULT NULL COMMENT '关联的备课夹ID',
  `course_status` tinyint(1) NOT NULL COMMENT '1:已备课；0：未备课',
  `course_grade` char(5) DEFAULT NULL COMMENT '年级',
  `course_stage` char(5) DEFAULT NULL COMMENT '学段',
  `teacher_appraise` varchar(600) DEFAULT NULL COMMENT '教师自我评价',
  `course_record` int(10) DEFAULT NULL COMMENT '课程记录',
  `course_impression` varchar(200) DEFAULT NULL COMMENT '课程产生的班级印象',
  `city_id` int(10) DEFAULT NULL COMMENT '城市ID',
  `town_id` int(10) DEFAULT NULL COMMENT '区县ID',
  `school_id` int(10) DEFAULT NULL COMMENT '学校ID',
  `course_appraise_status` tinyint(1) DEFAULT '0' COMMENT '课程的评价状态（0：无评价；1：有评价）',
  `course_question_status` tinyint(1) DEFAULT '0' COMMENT '课程问答状态（0：无问答;1:有问答）',
  `course_lesson_folder_status` tinyint(1) DEFAULT '0' COMMENT '课程备课夹状态（0:未备课；1已备课）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=276 DEFAULT CHARSET=utf8 COMMENT='心理健康课程';

-- ----------------------------
-- Records of xl_course
-- ----------------------------
INSERT INTO `xl_course` VALUES ('216', '337608', '', 'w36451978107373010061', '5', '2014-06-06', '1', '20', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '1', '1');
INSERT INTO `xl_course` VALUES ('217', '337607', '写个备注吧', 'w36451978107373010061', '5', '2014-06-06', '2', '54', '0', 'GO003', 'xd001', null, '1376', '164,165,166,163,162,158,159,160', '420100', '420199', '121584', '1', '1', '1');
INSERT INTO `xl_course` VALUES ('218', '337608', '', 'w36451978107373010061', '1', '2014-06-09', '1', '0', '0', 'GO003', 'xd001', null, '1379', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('219', '337608', '', 'w36451978107373010061', '1', '2014-06-09', '2', '0', '0', 'GO003', 'xd001', null, '1380', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('220', '337607', '', 'w36451978107373010061', '1', '2014-06-09', '3', '0', '0', 'GO004', 'xd001', null, '1381', null, '420100', '420199', '121584', '1', '0', '0');
INSERT INTO `xl_course` VALUES ('221', '337608', '', 'w36451978107373010061', '1', '2014-06-09', '4', '0', '0', 'GO003', 'xd001', null, '1382', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('222', '337608', '', 'w36451978107373010061', '2', '2014-06-10', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('223', '337608', '', 'w36451978107373010061', '3', '2014-06-11', '1', '0', '0', 'GO003', 'xd001', null, '1390', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('224', '337608', '', 'w36451978107373010061', '3', '2014-06-11', '2', '0', '0', 'GO003', 'xd001', null, '1387', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('225', '337608', '', 'w36451978107373010061', '4', '2014-06-12', '1', '0', '0', 'GO003', 'xd001', null, '1388', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('226', '337608', '', 'w36451978107373010061', '3', '2014-06-11', '3', '0', '0', 'GO003', 'xd001', null, '1389', null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('227', '337607', '', 'w36451978107373010061', '4', '2014-06-12', '2', '0', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '1', '0', '0');
INSERT INTO `xl_course` VALUES ('228', '337608', '123456456', 'w36451978107373010061', '2', '2014-06-17', '1', '108', '0', 'GO003', 'xd001', null, '1397', '173', '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('229', '337608', '', 'w36451978107373010061', '2', '2014-06-17', '2', '106', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('230', '337607', '', 'w36451978107373010061', '3', '2014-06-18', '1', '105', '0', 'GO004', 'xd001', null, '1391', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('231', '337607', '', 'w36451978107373010061', '3', '2014-06-18', '2', '111', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '1', '0', '1');
INSERT INTO `xl_course` VALUES ('232', '337607', '', 'w36451978107373010061', '3', '2014-06-18', '3', '111', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '1', '0', '1');
INSERT INTO `xl_course` VALUES ('233', '337607', '', 'w36451978107373010061', '4', '2014-06-19', '1', '111', '0', 'GO004', 'xd001', null, '1396', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('234', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '2', '112', '0', 'GO003', 'xd001', null, '1393', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('235', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('236', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '3', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('237', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('238', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('239', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '5', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('240', '337608', '', 'w36451978107373010061', '4', '2014-06-19', '5', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('241', '337608', '', 'w36451978107373010061', '5', '2014-06-20', '2', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('242', '337608', '', 'w36451978107373010061', '5', '2014-06-20', '3', '0', '0', 'GO003', 'xd001', null, null, null, '0', '0', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('243', '337607', '', 'w36451978107373010061', '5', '2014-06-20', '4', '0', '0', 'GO004', 'xd001', null, null, null, '0', '0', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('244', '337607', '', 'w36451978107373010061', '4', '2014-06-19', '8', '0', '0', 'GO004', 'xd001', null, null, null, '0', '0', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('245', '337607', '', 'w36451978107373010061', '4', '2014-06-19', '9', '111', '0', 'GO004', 'xd001', null, null, null, '0', '0', '121584', '1', '0', '1');
INSERT INTO `xl_course` VALUES ('246', '337608', '', 'w36451978107373010061', '2', '2014-06-24', '1', '113', '0', 'GO003', 'xd001', null, '1400', '174', '0', '0', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('247', '337608', '', 'w36451978107373010061', '4', '2014-07-10', '1', '113', '0', 'GO003', 'xd001', null, '1426', null, '0', '0', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('248', '337608', '', 'w36451978107373010061', '4', '2014-07-10', '2', '119', '0', 'GO003', 'xd001', null, '1427', null, '0', '0', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('249', '337608', '', 'w36451978107373010061', '4', '2014-07-10', '3', '109', '0', 'GO003', 'xd001', null, '1429', null, '0', '0', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('250', '337608', '', 'w36451978107373010061', '4', '2014-07-10', '4', '113', '0', 'GO003', 'xd001', null, '1430', null, '0', '0', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('251', '337608', '', 'w36451978107373010061', '4', '2014-07-10', '5', '113', '0', 'GO003', 'xd001', null, null, null, '0', '0', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('252', '337608', '', 'w36451978107373010061', '4', '2014-07-10', '6', '0', '0', 'GO003', 'xd001', null, null, null, '0', '0', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('253', '337608', '', 'w36451978107373010061', '4', '2014-07-10', '7', '0', '0', 'GO003', 'xd001', null, '1431', null, '0', '0', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('254', '337608', '啦啦啦啦', 'w36451978107373010061', '2', '2014-08-12', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('255', '337608', '桑德菲杰克里斯多夫健康三闾大夫进口量松岛枫进口量桑德菲杰看来是的经费克里斯丁经费克里斯丁风金坷垃是的境况发牢骚经典款发牢骚的境况发牢骚就雕刻疗法手机壳到啦分进口商懒得飞进克里斯丁风进克里斯多夫进克里斯多夫剑灵卡松岛枫进克里斯丁风进克里斯多夫就克里斯多夫就克里斯多夫进克里斯多夫就克里斯多夫进克里斯多夫就克里斯多夫就克里斯多夫进克里斯多夫就克里斯多夫就克里斯多夫就克里斯多夫就克里斯多夫就克里斯多夫进克', 'w36451978107373010061', '3', '2014-08-20', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('256', '337608', '', 'w36451978107373010061', '1', '2014-08-25', '1', '113', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('257', '393605', '', 'w36451978107373010061', '1', '2014-09-01', '1', '123', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('258', '337607', '', 'w36451978107373010061', '4', '2014-09-04', '1', '0', '0', 'GO005', 'xd001', null, null, '70,71', '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('259', '384990', '', 'w36451978107373010061', '4', '2014-09-04', '2', '0', '0', 'GO005', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('260', '337607', '', 'w36451978107373010061', '3', '2014-09-03', '2', '0', '0', 'GO005', 'xd001', null, null, '65,66,67,68', '420100', '420199', '121584', '1', '0', '0');
INSERT INTO `xl_course` VALUES ('261', '337608', '', 'w36451978107373010061', '4', '2014-09-04', '3', '123', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('262', '393606', '', 'w36451978107373010061', '4', '2014-09-04', '5', '117', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('263', '393605', '', 'w36451978107373010061', '4', '2014-09-04', '6', '123', '0', 'GO004', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('264', '337607', '', 'w36451978107373010061', '4', '2014-09-04', '7', '124', '0', 'GO005', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('265', '393606', '', 'w36451978107373010061', '4', '2014-09-04', '4', '119', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('266', '337607', '', 'w36451978107373010061', '5', '2014-09-05', '2', '124', '0', 'GO005', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('267', '384990', '', 'm36359802300862200030', '3', '2014-09-10', '1', '95', '0', 'GO005', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('268', '393606', '', 'w36451978107373010061', '1', '2014-09-15', '1', '113', '0', 'GO003', 'xd001', null, '1441', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('269', '393606', '', 'w36451978107373010061', '1', '2014-09-15', '2', '119', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('270', '393606', '', 'w36451978107373010061', '3', '2014-09-17', '4', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('271', '393606', '啊实打实的', 'w36451978107373010061', '2', '2014-09-16', '1', '113', '0', 'GO003', 'xd001', null, '1447', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('272', '337607', '', 'w36451978107373010061', '2', '2014-09-16', '2', '124', '0', 'GO005', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('273', '393606', '桑德菲杰看三闾大夫进口商懒得飞进口商懒得飞进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫金坷垃桑德菲杰看三闾大夫进口商懒得飞进口商懒得飞进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多夫金坷垃桑德菲杰看三闾大夫进口商懒得飞进口商懒得飞进克里斯多夫进克里斯多夫进克里斯多夫进克里斯多', 'w36451978107373010061', '3', '2014-09-17', '1', '0', '0', 'GO003', 'xd001', null, null, null, '420100', '420199', '121584', '0', '0', '0');
INSERT INTO `xl_course` VALUES ('274', '393606', '123123', 'w36451978107373010061', '1', '2014-09-22', '1', '119', '0', 'GO003', 'xd001', null, '1452', null, '420100', '420199', '121584', '0', '0', '1');
INSERT INTO `xl_course` VALUES ('275', '337607', '', 'w36451978107373010061', '2', '2014-09-23', '1', '124', '0', 'GO005', 'xd001', null, '1455', '77,78,79,80,81', '420100', '420199', '121584', '0', '0', '1');

-- ----------------------------
-- Table structure for `xl_course_appraise`
-- ----------------------------
DROP TABLE IF EXISTS `xl_course_appraise`;
CREATE TABLE `xl_course_appraise` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '评价ID',
  `course_id` int(10) NOT NULL COMMENT '课程ID',
  `appraise_score` int(2) NOT NULL COMMENT '评分',
  `appraise_remark` varchar(300) DEFAULT NULL COMMENT '一百字以内的短评',
  `appraise_user_id` char(21) DEFAULT NULL COMMENT '评价人ID',
  `appraise_role` enum('3','2','1') DEFAULT NULL COMMENT '1：学；2：老师；3：家长',
  `appraise_time` int(10) DEFAULT NULL COMMENT '评价时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='心理健康课程评价';

-- ----------------------------
-- Records of xl_course_appraise
-- ----------------------------
INSERT INTO `xl_course_appraise` VALUES ('15', '217', '8', '给个8分吧', 'l35789637007221180030', '1', '1402023627');
INSERT INTO `xl_course_appraise` VALUES ('16', '0', '0', 'undefined', 'l35789637007221180030', '1', '1402034809');
INSERT INTO `xl_course_appraise` VALUES ('17', '220', '10', '', 'l35789637007221180030', '1', '1402986470');
INSERT INTO `xl_course_appraise` VALUES ('18', '227', '10', '', 'l35789637007221180030', '1', '1402987080');
INSERT INTO `xl_course_appraise` VALUES ('19', '0', '0', '', 'w36549360503118470031', '3', '1403077620');
INSERT INTO `xl_course_appraise` VALUES ('20', '227', '2', '奉上满意的两分', 'w37835064708324740043', '3', '1403084740');
INSERT INTO `xl_course_appraise` VALUES ('21', '231', '10', '12312312', 'l35789637007221180030', '1', '1403164871');
INSERT INTO `xl_course_appraise` VALUES ('22', '232', '10', '', 'l35789637007221180030', '1', '1409555196');
INSERT INTO `xl_course_appraise` VALUES ('23', '245', '10', '', 's35951247001862320095', '1', '1409711742');
INSERT INTO `xl_course_appraise` VALUES ('24', '260', '10', '150字', 'w36549313309445350003', '1', '1414114632');

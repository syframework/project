SET FOREIGN_KEY_CHECKS=0;

TRUNCATE TABLE `t_user_role`;
TRUNCATE TABLE `t_user_permission`;
TRUNCATE TABLE `t_user_role_has_permission`;

-- ----------------------------
-- Records of t_user_role
-- ----------------------------
INSERT INTO `t_user_role` VALUES ('admin', 'Administrator');
INSERT INTO `t_user_role` VALUES ('blacklisted', 'Blacklisted user');
INSERT INTO `t_user_role` VALUES ('super-admin', 'Super administrator');
INSERT INTO `t_user_role` VALUES ('user', 'Simple user');

-- ----------------------------
-- Records of t_user_permission
-- ----------------------------
INSERT INTO `t_user_permission` VALUES ('page-create', 'Create a new page');
INSERT INTO `t_user_permission` VALUES ('page-delete', 'Delete a page');
INSERT INTO `t_user_permission` VALUES ('page-code', 'Update page source code');
INSERT INTO `t_user_permission` VALUES ('page-update', 'Update a page settings');
INSERT INTO `t_user_permission` VALUES ('page-update-inline', 'Update a page with inline edition (need page-update)');

-- ----------------------------
-- Records of t_user_role_has_permission
-- ----------------------------
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-create');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-code');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-delete');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-update');
INSERT INTO `t_user_role_has_permission` VALUES ('super-admin', 'page-update-inline');

SET FOREIGN_KEY_CHECKS=1;
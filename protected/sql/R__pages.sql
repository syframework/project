SET FOREIGN_KEY_CHECKS=0;

TRUNCATE TABLE `t_page`;

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

SET FOREIGN_KEY_CHECKS=1;
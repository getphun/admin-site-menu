DROP TABLE IF EXISTS `site_menu`;

DELETE FROM `user_perms_chain` WHERE `user_perms` IN (
    SELECT `id` FROM `user_perms` WHERE `group` = 'Site Menu'
);

DELETE FROM `user_perms` WHERE `group` = 'Site Menu';
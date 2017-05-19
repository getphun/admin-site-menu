INSERT IGNORE INTO `user_perms` ( `name`, `group`, `role`, `about` ) VALUES
    ( 'read_site_menu',   'Site Menu', 'Management', 'Allow user to view site menu' ),
    ( 'update_site_menu', 'Site Menu', 'Management', 'Allow user to update exists site menu' ),
    ( 'remove_site_menu', 'Site Menu', 'Management', 'Allow user to remove exists site menu' ),
    ( 'create_site_menu', 'Site Menu', 'Management', 'Allow user to create new site menu' );

CREATE TABLE IF NOT EXISTS `site_menu` (
    `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `user` INTEGER NOT NULL,
    `links` TEXT,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
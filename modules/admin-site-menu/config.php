<?php
/**
 * admin-site-menu config file
 * @package admin-site-menu
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'admin-site-menu',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/admin-site-menu',
    '__files' => [
        'modules/admin-site-menu'                   => [ 'install', 'remove', 'update' ],
        'theme/admin/component/site-menu'           => [ 'install', 'remove', 'update' ],
        'theme/admin/static/css/site-menu.css'      => [ 'install', 'remove', 'update' ],
        'theme/admin/static/js/jquery.nestable.js'  => [ 'install', 'remove', 'update' ],
        'theme/admin/static/js/site-menu.js'        => [ 'install', 'remove', 'update' ]
    ],
    '__dependencies' => [
        'admin'
    ],
    '_services' => [
        'siteMenu'  => 'AdminSiteMenu\\Service\\Menu'
    ],
    '_autoload' => [
        'classes' => [
            'AdminSiteMenu\\Controller\\MenuController'   => 'modules/admin-site-menu/controller/MenuController.php',
            'AdminSiteMenu\\Event\\MenuEvent'             => 'modules/admin-site-menu/event/MenuEvent.php',
            'AdminSiteMenu\\Model\\SiteMenu'              => 'modules/admin-site-menu/model/SiteMenu.php',
            'AdminSiteMenu\\Service\\Menu'                => 'modules/admin-site-menu/service/Menu.php'
        ],
        'files' => []
    ],
    
    '_routes' => [
        'admin' => [
            'adminSiteMenu' => [
                'rule'  => '/site-menu',
                'handler' => 'AdminSiteMenu\\Controller\\Menu::index'
            ],
            'adminSiteMenuEdit' => [
                'rule'  => '/site-menu/:id',
                'handler' => 'AdminSiteMenu\\Controller\\Menu::edit'
            ],
            'adminSiteMenuRemove' => [
                'rule'  => '/site-menu/:id/delete',
                'handler' => 'AdminSiteMenu\\Controller\\Menu::remove'
            ]
        ]
    ],
    
    'admin' => [
        'menu' => [
            'component' => [
                'label'     => 'Component',
                'order'     => 1500,
                'icon'      => 'puzzle-piece',
                'submenu'   => [
                    'site-menu' => [
                        'label'     => 'Site Menu',
                        'perms'     => 'read_site_menu',
                        'target'    => 'adminSiteMenu',
                        'order'     => 1000
                    ]
                ]
            ]
        ]
    ],
    
    'events' => [
        'site-menu:created' => [
            'site-menu' => 'AdminSiteMenu\\Event\\MenuEvent::created'
        ],
        'site-menu:updated' => [
            'site-menu' => 'AdminSiteMenu\\Event\\MenuEvent::updated'
        ],
        'site-menu:deleted' => [
            'site-menu' => 'AdminSiteMenu\\Event\\MenuEvent::deleted'
        ]
    ],
    
    'form' => [
        'admin-site-menu' => [
            'name' => [
                'type'  => 'text',
                'label' => 'Name',
                'desc'  => 'Please use only a-z, 0-9, and -',
                'rules' => [
                    'required' => true,
                    'alnumdash' => true,
                    'unique' => [
                        'model' => 'AdminSiteMenu\\Model\\SiteMenu',
                        'field' => 'name',
                        'self'  => [
                            'uri'   => 'id',
                            'field' => 'id'
                        ]
                    ]
                ]
            ],
            
            'links'  => [
                'type'    => 'hidden',
                'label'   => 'Text',
                'nolabel' => true,
                'rules'   => []
            ]
        ]
    ],
];
<?php
/**
 * site menu events
 * @package admin-site-menu
 * @version 0.0.1
 * @upgrade false
 */

namespace AdminSiteMenu\Event;

class MenuEvent{
    
    static function general($object){
        \Phun::$dispatcher->cache->remove('site-menus');
    }
    
    static function created($object){
        self::general($object);
    }
    
    static function updated($object, $old=null){
        self::general($object, $old);
    }
    
    static function deleted($object){
        self::general($object);
    }
}
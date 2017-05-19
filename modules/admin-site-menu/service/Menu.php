<?php
/**
 * Site menu service
 * @package admin-site-menu
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminSiteMenu\Service;
use AdminSiteMenu\Model\SiteMenu as SMenu;

class Menu 
{
    protected $_menus;
    
    private function activate(&$menus){
        $uri = \Phun::$req_uri;
        
        $active_found = false;
        foreach($menus as $menu){
            $menu->active = $menu->link == $uri;
            if($menu->active)
                $active_found = true;
            
            
            $menu->children_active = false;
            if(isset($menu->children)){
                $menu->children_active = $this->activate($menu->children);
                if($menu->children_active)
                    $active_found = true;
            }
        }
        
        return $active_found;
    }
    
    public function __construct(){
        $dis = \Phun::$dispatcher;
        
        $menus = $dis->cache->get('site-menus');
        if(!$menus || is_dev()){
            $menus = SMenu::get('', true);
            if(!$menus)
                $menus = [];
            else {
                $menus = prop_as_key($menus, 'name');
                foreach($menus as $index => $menu){
                    $menu['links'] = json_decode($menu['links']);
                    $menus[$index] = $menu;
                }
                
                $dis->cache->save('site-menus', $menus, (60*60*5));
            }
        }
        
        $this->_menus = $menus;
    }
    
    public function get($name){
        if(!$this->_menus || !isset($this->_menus[$name]))
            return false;
        
        $menus = $this->_menus[$name];
        $this->activate($menus->links);
        return $menus->links;
    }
}
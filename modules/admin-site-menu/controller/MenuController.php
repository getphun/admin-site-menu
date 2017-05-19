<?php
/**
 * Menu management
 * @package admin-site-menu
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminSiteMenu\Controller;
use AdminSiteMenu\Model\SiteMenu as SMenu;

class MenuController extends \AdminController
{
    private function _defaultParams(){
        return [
            'title'             => 'Site Menu',
            'nav_title'         => 'Component',
            'active_menu'       => 'component',
            'active_submenu'    => 'site-menu',
            'total'             => 0
        ];
    }
    
    public function editAction(){
        if(!$this->user->login)
            return $this->show404();
        
        $id = $this->param->id;
        if(!$id && !$this->can_i->create_site_menu)
            return $this->show404();
        elseif($id && !$this->can_i->update_site_menu)
            return $this->show404();
        
        $old = null;
        $params = $this->_defaultParams();
        $params['title'] = 'Create New Site Menu';
        $params['jses']  = ['js/jquery.nestable.js','js/site-menu.js'];
        $params['csses'] = ['css/site-menu.css'];
        
        $ref = $this->req->getQuery('ref');
        if(!$ref)
            $ref = $this->router->to('adminSiteMenu');
        $params['ref'] = $ref;
                
        if($id){
            $params['title'] = 'Edit Site Menu';
            $object = SMenu::get($id,false);
            if(!$object)
                return $this->show404();
            $old = $object;
        }else{
            $object = new \stdClass();
            $object->user = $this->user->id;
        }
        
        if(false === ($form=$this->form->validate('admin-site-menu', $object)))
            return $this->respond('component/site-menu/edit', $params);
        
        $object = object_replace($object, $form);
        
        $event = 'updated';
        
        if(!$id){
            $event = 'created';
            $object->id = SMenu::create($object);
        }else{
            SMenu::set($object, $id);
        }
        
        $this->fire('site-menu:'. $event, $object, $old);
        
        return $this->redirect($ref);
    }
    
    public function indexAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->read_site_menu)
            return $this->show404();
        
        $params = $this->_defaultParams();
        $params['menus'] = [];
        
        $menus = SMenu::get([], true, false, 'name');
        $params['menus'] = $menus;
        
        $total = SMenu::count();
        $params['total'] = $total;
        
        return $this->respond('component/site-menu/index', $params);
    }
    
    public function removeAction(){
        if(!$this->user->login)
            return $this->show404();
        if(!$this->can_i->remove_site_menu)
            return $this->show404();
        
        $id = $this->param->id;
        $object = SMenu::get($id, false);
        if(!$object)
            return $this->show404();
        
        $this->fire('site-menu:deleted', $object);
        SMenu::remove($id);
        
        $ref = $this->req->getQuery('ref');
        if($ref)
            return $this->redirect($ref);
        
        return $this->redirectUrl('adminSiteMenu');
    }
}
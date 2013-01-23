<?php
namespace ntentan\plugins\wyf\lib;

use ntentan\controllers\Controller;
use ntentan\models\Model;
use ntentan\Ntentan;
use ntentan\views\template_engines\TemplateEngine;

class WyfController extends Controller
{
    private $permissions;
    
    public function init()
    {
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/default'));               
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/menus'));               
        $this->addComponent('auth',
            array(
                'users_model' => 'system.users',
                'on_success' => 'call_function',
                'success_function' => '\ntentan\plugins\wyf\lib\WyfController::postLogin'
            )
        );
        $this->set('route_breakdown', explode('/', Ntentan::$route));
    }
    
    public static function postLogin()
    {
        $menu = array();
        
        // Get the roles the user is attached to
        $userRoles = Model::load('system.user_roles')->getAll(
            array(
                'conditions' => array(
                    'user_id' => $_SESSION['user']['id']
                )
            )
        );
                
        foreach($userRoles->toArray() as $userRole)
        {
            $role = Model::load('system.roles')->getJustFirstWithId($userRole['role_id']);
            $menu = array_merge(json_decode($role->menu_tree, true), $menu);
        }
        
        $sideMenu = array();
        
        foreach($menu as $path => $menuItem)
        {
            $sideMenu[] = array(
                'label' => $menuItem['label'],
                'path' => $path
            );
            
            if($menuItem['type'] == 'group')
            {
                $_SESSION['menu']['sub'][$path] = $menuItem['children'];
            }
        }
        
        $_SESSION['menu']['main'] = $sideMenu;
        
        echo $_SESSION['menu']['main'];
        
        Ntentan::redirect('dashboard');
    }
    
    public function getDescription() 
    {
        
    }
    
    public function addPermission($code, $description)
    {
        $this->permissions[$code] = $description;
    }
    
    public function getPermissions() 
    {
        return $this->permissions;
    }
    
    public static function getRoutes()
    {
        Ntentan::$config[Ntentan::$context]['error_handler'] = 'dashboard/error';
                
        $routes = array(
            array(
                'pattern' => '/login/',
                'route' => 'dashboard/login'
            ),
            array(
                'pattern' => '/logout/',
                'route' => 'dashboard/logout'
            )
        );
        
        if(is_array($_SESSION['menu']['sub']))
        {
            foreach($_SESSION['menu']['sub'] as $subMenu => $item)
            {
                $routes[] = array(
                    'pattern' => "/(?<path>^($subMenu|$subMenu\/)$)/",
                    'route' => 'dashboard/package/::path'
                );
            }
        }
        
        return $routes;
    }
}

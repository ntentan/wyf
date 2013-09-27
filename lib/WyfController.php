<?php
namespace ntentan\plugins\wyf\lib;

use ntentan\controllers\Controller;
use ntentan\models\Model;
use ntentan\Ntentan;
use ntentan\views\template_engines\TemplateEngine;

/**
 * Base controller for all wyf app modules you want to appear in the menu.
 */
class WyfController extends Controller
{
    private $permissions = array();
    
    /**
     * 
     * @var integer
     */
    public $weight;
    private $extraJavascripts;
    private $extraStylesheets;
    
    public function init()
    {
        $bootstrapClass = "\\" . Ntentan::$namespace . "\\lib\\WyfBootstrap";
        if(class_exists($bootstrapClass))
        {
            $bootstrapMethod = new \ReflectionMethod($bootstrapClass, "boot");
            $bootstrapMethod->invoke(null, $this);
        }
        
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
        $this->set('wyf_title', Ntentan::$appName);
        $this->set('wyf_app_name', Ntentan::$appName);
    }
    
    public function mc()
    {
        return $this->wyfModelControllerComponent;
    }
    
    public function addExtraJavascript($extraJavascript)
    {
        $this->extraJavascripts[] = $extraJavascript;
        $this->set('extra_javascripts', $this->extraJavascripts);
    }  
    
    public function addExtraStylesheet($extraStylesheet)
    {
        $this->extraStylesheets[] = $extraStylesheet;
        $this->set('extra_stylesheets', $this->extraStylesheets);
    }
    
    public function setTitle($title)
    {
        $this->set('wyf_title', Ntentan::$config['application']['name'] . " : {$title}");
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
    
    public static function notify($notification)
    {
        $_SESSION['notifications'] = $notification;
    }
    
    public static function runJs($js)
    {
        $_SESSION['js'] = $js;
    }
}

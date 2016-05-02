<?php
namespace ntentan\wyf\controllers;

use ntentan\Controller;
use ntentan\Model;
use ntentan\Ntentan;
use ntentan\honam\TemplateEngine;
use ntentan\View;
use ntentan\Router;
use ntentan\config\Config;

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
    private $package;
    private $name;
    private $path;
    
    public function __construct()
    {   
        $this->addComponent('auth',
            array(
                'users_model' => 'system.users',
                'on_success' => 'call_function',
                'login_route' => 'auth/login',
                'success_function' => AuthController::class . '::postLogin'
            )
        );
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/default'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/menus'));
        View::set('route_breakdown', explode('/', Router::getRoute()));
        View::set('wyf_title', Config::get('ntentan:app.name'));       
        
        $class = get_class($this);
        $namespace = Ntentan::getNamespace();
        if(preg_match(
            "/$namespace\\\\app\\\\(?<base>.*)\\\\controllers\\\\(?<name>.*)Controller/", 
            $class, $matches
        ))
        {
            $this->package = strtolower(str_replace("\\", ".", $matches['base']) . "." . $matches['name']);
            $this->name = str_replace(".", " ", $this->package);
            $this->path = str_replace(' ', '/', $this->name);
        }
    }
    
    protected function setTitle($title)
    {
        View::set('wyf_title', Ntentan::$appName . " : {$title}");
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
    
    protected function notify($notification)
    {
        $_SESSION['notifications'] = $notification;
    }
    
    protected function getWyfName()
    {
        return $this->name;
    }
    
    protected function getWyfPackage()
    {
        return $this->package;
    }
    
    protected function getWyfPath()
    {
        return $this->path;
    }
}

<?php

namespace ntentan\wyf\controllers;

use ntentan\Controller;
use ntentan\View;
use ntentan\wyf\Wyf;

/**
 * Base controller for all WYF application modules you want to appear in the menu.
 */
class WyfController extends Controller {

    private $permissions = array();

    /**
     * 
     * @var integer
     */
    public $weight;
    private $package;
    private $name;
    private $path;

    public function __construct() {
        /* $this->addComponent('auth',
          array(
          'users_model' => 'auth.users',
          'on_success' => 'call_function',
          'login_route' => 'auth/login',
          'success_function' => AuthController::class . '::postLogin'
          )
          );
          View::set('route_breakdown', explode('/', Ntentan::getRouter()->getRoute()));
          View::set('wyf_app_name', Wyf::getAppName());
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
          View::set('menu', $this->getMenu()); */
    }

    protected function setTitle($title) {
        View::set('wyf_title', Wyf::getAppName() . " | {$title}");
    }

    public function getDescription() {
        
    }

    public function addPermission($code, $description) {
        $this->permissions[$code] = $description;
    }

    public function getPermissions() {
        return $this->permissions;
    }

    public function getWyfName() {
        return $this->name;
    }

    public function getWyfPackage() {
        return $this->package;
    }

    /**
     * 
     * @return array
     */
    public function getMenu() {
        return [];
    }

    public function getWyfPath() {
        return $this->path;
    }

}

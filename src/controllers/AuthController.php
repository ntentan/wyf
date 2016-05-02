<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf\controllers;

use ntentan\Ntentan;

/**
 * Description of AuthController
 *
 * @author ekow
 */
class AuthController extends WyfController
{
    public function login()
    {
        $this->authComponent->login();
    }
    
    public static function postLogin()
    {
        /*$menu = array();
        
        if(Ntentan::$config['wyf.has_roles'] == 'true')
        {
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
        }
        else
        {
            require Ntentan::$appHome . Ntentan::$modulesPath . "/menu.php";
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
        
        if(self::$bootstrapClass !== false)
        {
            try{
                $bootstrapMethod = new \ReflectionMethod(self::$bootstrapClass, "login");
                $bootstrapMethod->invoke(null, $this);
            }
            catch(\ReflectionException $e)
            {
                //Do nothing
            }
        }*/
                
        return \ntentan\controllers\Redirect::path('dashboard');
    }
}

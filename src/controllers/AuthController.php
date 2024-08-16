<?php

namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\Session;
use ntentan\http\Redirect;
use ntentan\http\filters\Method;
use ntentan\mvc\Action;

/**
 * A controller through which authentication credentials can be collected.
 *
 * @author ekow
 */
class AuthController {

    #[Action]
    public function login(View $view) 
    {
        $view->setLayout('wyf_centered');
        return $view;
    }
    
    #[Action('login')]
    #[Method('post')]
    public function loginFailed(View $view)
    {
        $view->setLayout('wyf_centered');
        $view->set('error', 'Invalid username or password');
        return $view;
    }
    
    #[Action('logout')]
    public function logout(Redirect $redirect) {
        Session::reset();
        return $redirect->to('/');
    }
}

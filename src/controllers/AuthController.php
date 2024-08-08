<?php

namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\Session;
use ntentan\http\Redirect;
use ntentan\mvc\attributes\Method;
use ntentan\mvc\attributes\Action;

/**
 * Description of AuthController
 *
 * @author ekow
 */
class AuthController {

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
    
    public function logout(Redirect $redirect) {
        Session::reset();
        return $redirect->to('/');//$this->getRedirect($this->getContext()->getUrl(''));
    }
    
}

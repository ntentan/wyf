<?php

namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\Session;

/**
 * Description of AuthController
 *
 * @author ekow
 */
class AuthController {

    public function login(View $view) 
    {
        $view->setLayout('centered');
        return $view;
    }
    
    public function logout() {
        Session::reset();
        return $this->getRedirect($this->getContext()->getUrl(''));
    }
    
}

<?php

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\Session;

/**
 * Description of AuthController
 *
 * @author ekow
 */
class AuthController extends WyfController {

    public function login(View $view) {
        $view->setLayout('centered');
        return $view;
    }
    
    public function logout() {
        Session::reset();
        return $this->getRedirect($this->getContext()->getUrl(''));
    }
    
}

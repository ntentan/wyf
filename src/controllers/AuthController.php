<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
        return $view;
    }
    
    public function logout() {
        Session::reset();
        return new \ntentan\Redirect("");
    }
    
}

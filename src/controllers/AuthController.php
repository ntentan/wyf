<?php

namespace ntentan\wyf\controllers;

use ntentan\Context;
use ntentan\mvc\View;
use ntentan\Session;
use ntentan\http\Redirect;
use ntentan\http\filters\Method;
use ntentan\mvc\Action;
use ntentan\sessions\SessionStore;
use Psr\Http\Message\ResponseInterface;

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
    public function logout(ResponseInterface $redirect, SessionStore $session, Context $context)
    {
        $session->destroy();
        return $redirect->withStatus(301)->withHeader('Location', $context->getPath('/'));
    }
}

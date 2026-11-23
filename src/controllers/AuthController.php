<?php

namespace ntentan\wyf\controllers;

use ntentan\Context;
use ntentan\http\filters\Get;
use ntentan\http\filters\Post;
use ntentan\mvc\View;
use ntentan\sessions\SessionStore;
use Psr\Http\Message\ResponseInterface;

/**
 * A controller through which authentication credentials can be collected.
 *
 * @author Ekow Ainooson.
 */
class AuthController extends WyfController
{
    public array $stylesheets {
        get => ['/css/forms.css', '/css/cards.css', '/css/callouts.css'];
    }

    #[Get]
    public function login(View $view) 
    {
        $view->setLayout('wyf_centered');
        return $view;
    }
    
    #[Post('login')]
    public function loginFailed(View $view)
    {
        $view->setLayout('wyf_centered');
        $view->set('error', 'Failed to login. Invalid username or password');
        return $view;
    }
    
    #[Get]
    public function logout(ResponseInterface $redirect, SessionStore $session, Context $context)
    {
        $session->destroy();
        return $redirect->withStatus(301)->withHeader('Location', $context->getPath('/'));
    }

    public function getLabel(): string
    {
        return "auth";
    }
}

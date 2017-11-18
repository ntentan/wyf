<?php

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\Model;
use ntentan\utils\Input;
use ntentan\wyf\api\GetRequestHandler;
use ntentan\wyf\api\PostRequestHandler;

/**
 * Description of ApiController
 *
 * @author ekow
 */
class ApiController extends WyfController
{
    public function __construct(View $view)
    {
        $view->setLayout('plain');
        $view->setTemplate('api');
        $view->setContentType('application/json');
        ini_set('html_errors', 'off');
    }

    private function decodePath($path)
    {
        $split = explode("/", $path);
        $id = null;
        $end = end($split);
        if (is_numeric($end) || $end == 'validator') {
            $id = array_pop($split);
        }
        return ['model' => Model::load(implode('.', $split)), 'id' => $id];
    }


    public function index()
    {

    }

    /**
     * @ntentan.action rest
     * @ntentan.method GET
     *
     * @param GetRequestHandler $handler
     * @param View $view
     * @param $path
     * @return View
     */
    public function getRequest(GetRequestHandler $handler, View $view, $path)
    {
        $view->set('response', $handler->process($this->decodePath($path)));
        return $view;
    }

    /**
     * @ntentan.action rest
     * @ntentan.method POST
     *
     * @param PostRequestHandler $handler
     * @param View $view
     * @param $path
     * @return View
     */
    public function postRequest(PostRequestHandler $handler, View $view, $path)
    {
        $view->set('response', $handler->process($this->decodePath($path)));
        return $view;
    }


}

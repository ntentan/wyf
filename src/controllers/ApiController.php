<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\Model;

/**
 * Description of ApiController
 *
 * @author ekow
 */
class ApiController extends WyfController
{
    private function getModel($path)
    {
        return Model::load(str_replace('/', '.', $path));
    }
    
    public function rest($path)
    {
        View::setLayout('plain');
        View::setTemplate('api');
        $model = $this->getModel($path);
        View::set('response', $model->fetch()->toArray());
        //var_dump($_SERVER);
    }
    
    protected function performRequest()
    {
        //switch($_SERVER[''])
    }
}

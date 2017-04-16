<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\Model;
use ntentan\utils\Input;

/**
 * Description of ApiController
 *
 * @author ekow
 */
class ApiController extends WyfController {

    public function __construct() {
        parent::__construct();
        View::setLayout('plain');
        View::setTemplate('api');
    }
    
    private function getModel($path) {
        return Model::load(str_replace('/', '.', $path));
    }

    public function model($path) {
        $model = $this->getModel($path);
        $model->limit(Input::get('limit'));
        $model->offset((Input::get('page') - 1) * Input::get('limit'));
        header("X-Item-Count: " . $model->count());
        View::set('response', $model->fetch()->toArray());
    }
    
    public function index() {
        
    }

}

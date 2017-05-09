<?php

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\Model;
use ntentan\utils\Input;
use ntentan\Context;

/**
 * Description of ApiController
 *
 * @author ekow
 */
class ApiController extends WyfController {

    public function __construct(Context $context, View $view) {
        parent::__construct($context);
        $view->setLayout('plain');
        $view->setTemplate('api');
        $view->setContentType('application/json');
    }
    
    private function getModel($path) {
        return Model::load(str_replace('/', '.', $path));
    }

    private function get($path, $view) {
        $model = $this->getModel($path);
        $model->limit(Input::get('limit'));
        $model->fields(explode(',', Input::get('fields')));
        $model->offset((Input::get('page') - 1) * Input::get('limit'));
        header("X-Item-Count: " . $model->count());
        $view->set('response', $model->fetch()->toArray(1));
    }
    
    public function index() {
        
    }
    
    public function rest(View $view, $path) {
        $this->get($path, $view);
        return $view;
    }

}

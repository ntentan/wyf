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
    
    private function decodePath($path) {
        $split = explode("/", $path);
        if(is_numeric(end($split))) {
            $id = array_pop($split);
        }
        return ['model' => Model::load(implode('.', $split)), 'id' => $id];
    }

    private function get($path, $view) {
        $pathInfo = $this->decodePath($path);
        $model = $pathInfo['model'];
        if(Input::exists(Input::GET, 'fields')) {
            $model->fields(explode(',', Input::get('fields')));
        }
        if($pathInfo['id']){
            $primaryKey = $model->getDescription()->getPrimaryKey()[0];
            $model->filter([$primaryKey => $pathInfo['id']]);
            $view->set('response', $model->fetchFirst()->toArray(Input::get('depth')));
        } else {
            $model->limit(Input::get('limit'));
            $model->offset((Input::get('page') - 1) * Input::get('limit'));
            header("X-Item-Count: " . $model->count());
            $view->set('response', $model->fetch()->toArray(Input::get('depth')));
        }
    }
    
    public function index() {
        
    }
    
    public function rest(View $view, $path) {
        $this->get($path, $view);
        return $view;
    }

}

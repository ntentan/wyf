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
        $id = null;
        $end = end($split);
        if(is_numeric($end) || $end == 'validator') {
            $id = array_pop($split);
        }
        return ['model' => Model::load(implode('.', $split)), 'id' => $id];
    }
    
    private function post($path, $view) {
        if(Input::server('CONTENT_TYPE') != 'application/json') {
            $view->set('response', ['message' => 'Accepts only application/json content']);
            http_response_code(400);
            return $view;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $decoded = $this->decodePath($path);
        $model = $decoded['model'];
        $model->setData($data);
        
        if($decoded['id'] == "validator") {
            $validity = $model->validate();
            $isValid = $validity === true ? true : false;
            $response = ['valid' => $isValid];
            
            if($isValid) {
                $response['string'] = (string) $model;
            } else {
                http_response_code(400);
                $response['invalid_fields'] = $validity;
            }
            $view->set('response', $response);
        } else {
            if($model->save()) {
                $view->set('response', ['id' => $model->id]);
            } else {
                http_response_code(400);
                $view->set('response', [
                    'message' => 'Failed to save data', 
                    'invalid_fields' => $model->getInvalidFields()
                ]);
            }
        }
        return $view;
    }

    private function get($path, $view) {
        $pathInfo = $this->decodePath($path);
        $model = $pathInfo['model'];
        $input = Input::get();
        
        foreach($input as $key => $value) {
            if(preg_match("/fields:(?<model>[0-9a-z_.]+)/", $key, $matches)) {
                $model->with($matches['model'])->setFields(explode(',', $value));
            }
        }
        
        if($pathInfo['id']){
            $primaryKey = $model->getDescription()->getPrimaryKey()[0];
            $item = $model->fetchFirst(
                [$primaryKey => $pathInfo['id']]
            );
            if($item->count() == 0) {
                $view->set('response', ['message' => 'item not found']);
                http_response_code(404);
            } else {
                if(Input::server('CONTENT_TYPE') == "text/plain") {
                    header("Content-Type: text/plain");
                    return (string)$item;
                } else {
                    $view->set('response', $item->toArray(Input::get('depth')));
                }
            }
        } else {
            $model->limit(Input::get('limit'));
            $model->offset((Input::get('page') - 1) * Input::get('limit'));
            $model->sortBy(Input::get('sort'));
            header("X-Item-Count: " . $model->count());
            $view->set('response', $model->fetch()->toArray(Input::get('depth')));
        }
        
        return $view;
    }
    
    public function index() {
        
    }
    
    public function rest(View $view, $path) {
        switch(Input::server('REQUEST_METHOD')){
            case 'GET': 
                $response = $this->get($path, $view);
                break;
            case 'POST':
                $response = $this->post($path, $view);
        }
        return $response;
    }

}

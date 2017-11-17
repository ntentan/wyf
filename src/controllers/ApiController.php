<?php

namespace ntentan\wyf\controllers;

use ntentan\nibii\exceptions\ModelNotFoundException;
use ntentan\View;
use ntentan\Model;
use ntentan\utils\Input;
use ntentan\Context;
use ntentan\nibii\QueryParameters;
use ntentan\wyf\api\GetRequestHandler;
use ntentan\wyf\api\GetRequestProcessor;

/**
 * Description of ApiController
 *
 * @author ekow
 */
class ApiController extends WyfController
{
    private $filters = [];

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

    private function post($path, $view)
    {
        if (Input::server('CONTENT_TYPE') != 'application/json') {
            $view->set('response', ['message' => 'Accepts only application/json content']);
            http_response_code(400);
            return $view;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $decoded = $this->decodePath($path);
        $model = $decoded['model'];
        $model->setData($data);

        if ($decoded['id'] == "validator") {
            $validity = $model->validate();
            $isValid = $validity === true ? true : false;
            $response = ['valid' => $isValid];

            if ($isValid) {
                $response['string'] = (string)$model;
            } else {
                http_response_code(400);
                $response['invalid_fields'] = $validity;
            }
            $view->set('response', $response);
        } else {
            if ($model->save()) {
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


    public function index()
    {

    }

    /**
     * @ntentan.action rest
     * @ntentan.method GET
     * @param GetRequestHandler $processor
     * @param View $view
     * @param $path
     * @return
     */
    public function getRequest(GetRequestHandler $processor, View $view, $path)
    {
        $view->set('response', $processor->process($this->decodePath($path)));
        return $view;
    }

    /**
     * @ntentan.action rest
     * @ntentan.method POST
     * @param View $view
     * @param $path
     */
    public function postRequest(PostView $view, $path)
    {
        return $this->post($path, $view);
    }


}

<?php

namespace ntentan\wyf\controllers\api;


use ntentan\Model;
use ntentan\utils\Input;
use ntentan\wyf\interfaces\ApiRequestHandlerInterface;

class PostRequestHandler implements ApiRequestHandlerInterface
{

    private function runValidator(Model $model)
    {
        $validity = $model->validate();
        $isValid = $validity === true ? true : false;
        $response = ['valid' => $isValid];
        if ($isValid) {
            $response['string'] = (string)$model;
        } else {
            http_response_code(400);
            $response['invalid_fields'] = $validity;
        }
        return $response;
    }

    private function saveRecord(Model $model)
    {
        if ($model->save()) {
            $response = ['id' => $model->id];
        } else {
            http_response_code(400);
            $response = [
                'message' => 'Failed to save data',
                'invalid_fields' => $model->getInvalidFields()
            ];
        }
        return $response;
    }

    public function process($path)
    {
        if (Input::server('CONTENT_TYPE') != 'application/json') {
            http_response_code(400);
            return ['message' => 'Accepts only application/json content'];
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $model = $path['model'];
        $model->setData($data);
        if ($path['id'] == "validator") {
            return $this->runValidator($model);
        } else {
            return $this->saveRecord($model);
        }
    }
}
<?php
namespace ntentan\plugins\wyf\lib\modules\api;

use ntentan\models\Model;

class ApiController extends \ntentan\plugins\wyf\lib\WyfController
{
    public function init()
    {
        parent::init();
        ini_set('html_errors', 'Off');
    }       
    
    public function rest()
    {
        $this->view->layout = false;
        $this->view->template = false;
        
        $params = func_get_args();
        if(is_numeric(end($params)))
        {
            $id = array_pop($params);
        }
        
        $format = null;
        
        //Determine the data format
        $lastItem = explode('.', end($params));
        if(count($lastItem) == 2)
        {
            $format = end($lastItem);
            $lastItem = reset($lastItem);
            array_pop($params);
            array_push($params, $lastItem);
        }
        $modelName = implode('.', $params);
                        
        try{
            $model = Model::load($modelName);
        }
        catch(ModelException $e)
        {
            $this->error("Failed to load model $modelName");
            die();
        }
        
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET':
                if($id != '')
                {
                    $response = $model->getFirstWithId($id);
                }
                else
                {
                    $response = $model->getAll();
                }
                print json_encode($response->toArray());
                
                break;
                
            case 'PUT':
                parse_str(file_get_contents("php://input"), $data);
                $validate = $model->setData($data);
                
                try{
                    $model->update($model->getKeyField(), $id);
                    http_response_code(201);
                    print json_encode($id);
                }
                catch(ModelException $e)
                {
                    http_response_code(400);
                    $this->error($e->getMessage());
                }
                catch(Exception $e)
                {
                    http_response_code(400);
                    $this->error($e->getMessage());
                }
                break;
                
            case 'POST':
                if($format == 'json')
                {
                    $data = json_decode(file_get_contents("php://input"), true);
                }
                else 
                {
                    $data = $_POST;
                }
                
                $model->setData($data);
                $id = $model->save();
                if($id === false)
                {
                    http_response_code(400);
                    print json_encode($model->invalidFields);                    
                }
                else
                {
                    print json_encode($id);
                }                
                
                break;
        }        
    }
}
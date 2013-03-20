<?php
namespace ntentan\plugins\wyf\components\model_controller;

use ntentan\controllers\components\Component;
use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;
use ntentan\plugins\wyf\lib\WyfController;
use ntentan\models\Model;

class ModelControllerComponent extends Component
{
    public $listFields = array();
    public $keyField;
    private $operations = array();
    private $urlBase;
    private $entities;
    private $entity;
    private $linkedModels = array();
    private $linkedModelInstances = array();
    private $parent = false;
    private $formVariables = array();
    public $hasAddOperation = true;
    public $hasEditOperation = true;
    public $hasDeleteOperation = true;

    public function init()
    {
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/model_controller'));
        
        $this->entities = Ntentan::toSentence($this->model->getName());
        $this->entity = Ntentan::singular($this->entities);
        $this->set('entity', $this->entity);
        $this->set('entities', $this->entities);
        
        $this->set('model_description', $this->model->describe());        
        $this->urlBase = Ntentan::getUrl($this->route);
        $this->keyField = 'id';

        if($this->hasAddOperation)
        {
            $this->controller->addPermission(
                "can_add_{$this->entities}", 
                "Can add new $this->entities"
            );
        }
        
        if($this->hasEditOperation)
        {
            $this->controller->addPermission(
                "can_edit_{$this->entities}", 
                "Can edit existing $this->entities"
            );
        }
        
        if($this->hasDeleteOperation)
        {
            $this->controller->addPermission(
                "can_delete_{$this->entities}", 
                "Can delete existing $this->entities"
            );
        }
    }
    
    public function addOperation($label, $action = '')
    {
        $this->operations[] = array(
            'link' => $this->urlBase . '/' . ($action == '' ? strtolower($label) : $action),
            'label' => $label
        );                    
    }
    
    private function getTemplateName($base)
    {
        // @todo optimize this so it doesn't have to use the str_replace
        return str_replace('.', '_', $this->controller->model->getRoute()) . "_$base";
    }
    
    public function linkWith($model)
    {
        $modelInstance = Model::load($model);
        $name = lcfirst(Ntentan::camelize($modelInstance->getName()));
        $this->linkedModels[] = $name;
        $this->linkedModelInstances[$name] = array(
            'instance' => $modelInstance,
            'name' => $model
        );
        $name = Ntentan::deCamelize($name);
        $this->addOperation(Ntentan::toSentence($name), $name);
    }
    
    public function run()
    {
        $this->view->template = $this->getTemplateName('list_view.tpl.php');
        $this->controller->setTitle(ucfirst($this->entities));
        
        if($this->hasEditOperation) $this->addOperation('Edit');
        if($this->hasDeleteOperation) $this->addOperation('Delete');
        
        $modelDescription = $this->model->describe();
        $otherModelDescriptions = array();
        
        
        if(count($this->listFields) == 0)
        {
            foreach($modelDescription['fields'] as $field)
            {
                if($field['name'] == $this->parent['foreign_key'])
                {
                    continue;
                }
                
                if($field['primary_key'])
                {
                    $this->keyField = $field['name'];
                    continue;
                }
                
                $this->listFields[] = array(
                    'label' => Ntentan::toSentence($field['name']),
                    'name' => $field['name']
                );
            }
        }
        else 
        {
            $fields = $this->listFields;
            $this->listFields = array();
            foreach($fields as $field)
            {
                if(!isset($modelDescription['fields'][$field]))
                {
                    $found = false;
                    $belongsToModel = Model::extractModelName($field);
                    foreach($this->model->belongsTo as $belongsTo)
                    {
                        $belongsTo = Model::getBelongsTo($belongsTo);
                        if(Ntentan::singular($belongsTo) != $belongsToModel) continue;                        
                        
                        if(!isset($otherModelDescriptions[$belongsTo]))
                        {
                            $otherModelDescriptions[$belongsTo] = 
                                Model::load($belongsTo)->describe();
                        }
                        
                        @$relatedField = end(explode('.', $field));
                        if(array_key_exists($relatedField, $otherModelDescriptions[$belongsTo]['fields']))
                        {
                            $found = true;
                            $otherModelDescriptions[$belongsTo]['fields'][$relatedField]['label'] = 
                            Ntentan::singular(Ntentan::toSentence($otherModelDescriptions[$belongsTo]['name']))
                                . ' ' .
                            Ntentan::toSentence(
                                $otherModelDescriptions[$belongsTo]['fields'][$relatedField]['name']
                            );                                
                            $this->listFields[] = array(
                                'name' => $field,
                                'label' => 
                                    Ntentan::singular(Ntentan::toSentence($otherModelDescriptions[$belongsTo]['name']))
                                        . ' ' .
                                    Ntentan::toSentence($otherModelDescriptions[$belongsTo]['fields'][$relatedField]['name'])
                            );
                        }
                    }
                    
                    if(!$found)
                    {
                        throw  new \ntentan\models\exceptions\FieldNotFoundException("Model has no field $field");
                    }
                }
                else
                {
                    $this->listFields[] = array(
                        'name' => $modelDescription['fields'][$field]['name'],
                        'label' => Ntentan::toSentence(
                            $modelDescription['fields'][$field]['name']
                        )
                    );
                }
            }
        }
        
        
        
        $this->set('key_field', $this->keyField);
        $this->set('list_fields', $this->listFields);
        $this->set('wyf_add_url', "{$this->urlBase}/add");
        $this->set('wyf_import_url', "{$this->urlBase}/import");        
        $this->set('wyf_api_url', "{$this->urlBase}/api?");
        $this->set('operations', $this->operations);
        $this->set('foreign_key', $this->parent['foreign_key']);
        $this->set('foreign_key_value', $this->parent['id']);
        
        $this->set('has_add_operation', $this->hasAddOperation);
        $this->set('has_edit_operation', $this->hasEditOperation);
        $this->set('has_delete_operation', $this->hasDeleteOperation);
    }
    
    public function api()
    {
        ini_set('html_errors', 'off');
        $this->view->setContentType('application/json');
        $this->view->layout = false;
        $this->view->template = $this->getTemplateName('api.tpl.php');        
        
        $response = array();
        
        if($_GET['info'] == 'yes')
        {
            $count = $this->model->countAllItems();
            $response['count'] = $count;
        }
        
        $fields = json_decode($_GET['f'], true);
        $fields[] = 'id';
        
        $data = $this->model->get(
            $_GET['ipp'],
            array(
                'offset' => $_GET['ipp'] * ($_GET['pg'] - 1),
                'conditions' => json_decode($_GET['c'], true),
                'fields' => $fields,
                'sort' => array('id DESC'),
                'fetch_belongs_to' => true
            )
        );
        $response['data'] = $data->toArray();
        
        foreach($response['data'] as $index => $row)
        {
            foreach($row as $field => $column)
            {
                if(is_array($column))
                {
                    foreach($column as $nestedField => $nestedValue)
                    {
                        $response['data'][$index][str_replace(".", "_", "$field.$nestedField")] = $nestedValue;
                    }
                }
            }
        }
        
        if(isset($_SESSION['notifications']))
        {
            $response['notifications'] = $_SESSION['notifications'];
            unset($_SESSION['notifications']);
        }
        
        $this->set('response', $response);
    }
    
    public function add()
    {
        $this->controller->setTitle("Add new {$this->entity}");
        $this->view->template = $this->getTemplateName('add.tpl.php');
        $this->set('form_template', $this->getTemplateName('form.tpl.php'));
        $this->set('form_variables', $this->formVariables);
        
        if(is_array($this->parent)) 
        {
            $this->set('params', array(
                    'hide' => array($this->parent['foreign_key'])
                )
            );
            $_POST[$this->parent['foreign_key']] = $this->parent['id'];
            $this->set('postfix', "to " . Ntentan::toSentence($this->parent['entinty']) . " {$this->parent['item']}");            
        }
        
        $this->set('form_data', $_POST);
        
        if(isset($_POST['form-sent']))
        {
            unset($_POST['form-sent']);
            $this->model->setData($_POST);
            if($this->model->validate())
            {
                $this->model->save();
                WyfController::notify("Added a new {$this->entity} {$this->model}");
                Ntentan::redirect($this->urlBase);
            }
            else
            {
                $this->set('form_errors', $this->model->invalidFields);
            }
        }
    }
    
    public function import($param = null)
    {
        $this->view->template = $this->getTemplateName('import.tpl.php');
        
        if($param == 'template.csv')
        {
            $this->view->setContentType('text/csv');
            $this->view->layout = false;
            $this->view->template = $this->getTemplateName('import_csv.tpl.php');
            return;
        }
        
        if(isset($_FILES['data_file']))
        {
            $file = "tmp/" . uniqid();
            if(move_uploaded_file($_FILES['data_file']['tmp_name'], $file))
            {
                $file = fopen($file, 'r');
                $headers = fgetcsv($file);
                
                while(!feof($file))
                {
                    $data = fgetcsv($file);
                    $newEntry = $this->model->getNew();
                    foreach($headers as $i => $header)
                    {
                        $newEntry[$header] = $data[$i];
                    }
                    

                    if($newEntry->save() === false)
                    {
                        // Do something useful here
                    }
                }
                
                Ntentan::redirect($this->urlBase);
            }
            else
            {
                $this->set('upload_error', "Failed to upload file");
            }
        }
        
        $this->set('import_template', "{$this->urlBase}/import/template.csv");
    }
    
    public function delete($id)
    {
        $item = $this->model->getJustFirstWithId($id);
        
        if($_GET['confirm'] == 'yes')
        {
            WyfController::notify("Deleted {$this->entity} {$item}");
            $item->delete();
            Ntentan::redirect($this->urlBase);
        }
        else
        {
            $this->set('item', (string)$item);
            $this->set('delete_yes_link', "{$this->urlBase}/delete/$id?confirm=yes");
            $this->set('delete_no_link', $this->urlBase);
            $this->view->template = $this->getTemplateName('delete.tpl.php');
        }
    }
    
    public function edit($id)
    {
        $this->view->template = $this->getTemplateName('edit.tpl.php');
        $this->set('form_template', $this->getTemplateName('form.tpl.php'));
        $item = $this->model->getFirstWithId($id);        
        $this->set('item', (string)$item);
        $this->controller->setTitle("Edit {$this->entity} {$item}");    
        
        if(is_array($this->parent)) 
        {
            $this->set('params', array(
                    'hide' => array($this->parent['foreign_key'])
                )
            );
            $_POST[$this->parent['foreign_key']] = $this->parent['id'] ;
        }        
        
        if(isset($_POST['form-sent']))
        {
            $this->set('form_data', $_POST);
            $item->setData($_POST);
            if($item->validate())
            {
                $item->update();
                WyfController::notify("Edited {$this->entity} {$item}");                
                Ntentan::redirect($this->urlBase);
            }
            else
            {
                $this->set('form_errors', $item->invalidFields);
            }
        }
        else
        {
            $this->set('form_data', $item->toArray());
        }
    }
    
    public function hasMethod($method = null) 
    {
        if(array_search($method, $this->linkedModels) === false)
        {
            return parent::hasMethod($method);
        }
        else
        {
            return true;
        }
    }
    
    public function runMethod($params, $method = null) 
    {
        if(array_search($method, $this->linkedModels) !== false)
        {
            $controllerPath = str_replace('.', '/',$this->linkedModelInstances[$method]['instance']->getRoute());
            
            $parentInfo = array(
                'model' => $this->model, 
                'url_base' => $this->urlBase
            );
            
            $controller = \ntentan\controllers\Controller::load($controllerPath, true);
            $parentInfo['id'] = array_shift($params);
            $controller->mc()->setParent($parentInfo);
            $controller->method = array_shift($params);
            
            if($controller->method == '') $controller->method = 'run';
            $controller->runMethod($params);
            
            die();
        }
        else
        {
            return parent::runMethod($params, $method);
        }
    } 
    
    public function setParent($parent)
    {
        $singularModel = Ntentan::singular($parent['model']->getName());
        $parent['foreign_key'] = "{$singularModel}_id";
        $item = $parent['model']->getJustFirstWithId($parent['id']);
        $parent['item'] = (string)$item;
        $parent['entinty'] = $singularModel;
        $this->parent = $parent;
        $this->urlBase = "{$this->parent['url_base']}/{$this->model->getName()}/{$this->parent['id']}";
        $this->set('postfix', "of " . Ntentan::toSentence($singularModel) . " {$item}");
    }
    
    public function setFormVariable($variable, $value)
    {
        $this->formVariables[$variable] = $value;
    }
}

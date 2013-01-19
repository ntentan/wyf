<?php
namespace ntentan\plugins\wyf\components\model_controller;

use ntentan\controllers\components\Component;
use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

class ModelControllerComponent extends Component
{
    public $listFields = array();
    public $keyField;
    private $operations = array();
    private $urlBase;

    public function init()
    {
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/model_controller'));
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/default'));
        $this->set('entity', Ntentan::singular($this->model->getName()));
        $this->set('model_description', $this->model->describe());        
        $this->set('entities', $this->model->getName());
        $this->urlBase = Ntentan::getUrl($this->route);
        $this->keyField = 'id';
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
    
    public function run()
    {
        $this->view->template = $this->getTemplateName('list_view.tpl.php');
        
        $this->addOperation('Edit');
        $this->addOperation('Delete');
        $modelDescription = $this->model->describe();
        
        if(count($this->listFields) == 0)
        {
            foreach($modelDescription['fields'] as $field)
            {
                if($field['primary_key'])
                {
                    $this->keyField = $field['name'];
                    continue;
                }
                $field['label'] = Ntentan::toSentence($field['name']);
                $this->listFields[] = $field;
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
                    throw  new \ntentan\models\exceptions\FieldNotFoundException("Model has no field $field");
                }                
                $modelDescription['fields'][$field]['label'] = Ntentan::toSentence(
                    $modelDescription['fields'][$field]['name']
                );
                $this->listFields[] = $modelDescription['fields'][$field];
            }
        }
        
        $this->set('key_field', $this->keyField);
        $this->set('list_fields', $this->listFields);
        $this->set('wyf_add_url', "{$this->urlBase}/add");
        $this->set('wyf_api_url', "{$this->urlBase}/api");
        $this->set('wyf_import_url', "{$this->urlBase}/import");
        $this->set('operations', $this->operations);
    }
    
    public function api()
    {
        $this->view->setContentType('application/json');
        $this->view->layout = false;
        $response = array();
        if($_GET['info'] == 'yes')
        {
            $count = $this->model->countAllItems();
            $response['count'] = $count;
        }
        
        $data = $this->model->get(
            $_GET['ipp'],
            array(
                'offset' => $_GET['ipp'] * ($_GET['pg'] - 1)
            )
        );
        $response['data'] = $data->toArray();
        $this->set('response', $response);
    }
    
    public function add()
    {
        $this->view->template = $this->getTemplateName('add.tpl.php');
        $this->set('form_template', $this->getTemplateName('form.tpl.php'));
        $this->set('form_data', $_POST);
        
        if(isset($_POST['form-sent']))
        {
            unset($_POST['form-sent']);
            $this->model->setData($_POST);
            if($this->model->validate())
            {
                $this->model->save();
                Ntentan::redirect(Ntentan::getUrl($this->route));
            }
            else
            {
                $this->set('form_errors', $this->model->invalidFields);
            }
        }
    }
    
    public function import($param = null)
    {
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
                        /*var_dump($newEntry->invalidFields);
                        var_dump($newEntry->getData());*/
                    }
                }
                
                Ntentan::redirect(Ntentan::getUrl($this->route));
            }
            else
            {
                $this->set('upload_error', "Failed to upload file");
            }
        }
        
        $this->set('import_template', Ntentan::getUrl("{$this->route}/import/template.csv"));
    }
    
    public function delete($id)
    {
        $item = $this->model->getJustFirstWithId($id);
        
        if($_GET['confirm'] == 'yes')
        {
            $item->delete();
            Ntentan::redirect(Ntentan::getUrl($this->route));
        }
        else
        {
            $this->set('item', (string)$item);
            $this->set('delete_yes_link', Ntentan::getUrl("{$this->route}/delete/$id?confirm=yes"));
            $this->set('delete_no_link', Ntentan::getUrl("{$this->route}"));
            $this->view->template = $this->getTemplateName('delete.tpl.php');
        }
    }
    
    public function edit($id)
    {
        $this->view->template = $this->getTemplateName('edit.tpl.php');
        $this->set('form_template', $this->getTemplateName('form.tpl.php'));
        $item = $this->model->getFirstWithId($id);        
        $this->set('item', (string)$item);
        
        if(isset($_POST['form-sent']))
        {
            $this->set('form_data', $_POST);
            $item->setData($_POST);
            if($item->validate())
            {
                $item->update();
                Ntentan::redirect(Ntentan::getUrl($this->route));
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
}


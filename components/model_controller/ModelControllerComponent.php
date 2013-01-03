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
        
        if(count($this->listFields) == 0)
        {
            $modelDescription = $this->model->describe();
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
        
        $this->set('key_field', $this->keyField);
        $this->set('list_fields', $this->listFields);
        $this->set('wyf_add_url', "{$this->urlBase}/add");
        $this->set('wyf_api_url', "{$this->urlBase}/api");
        $this->set('operations', $this->operations);
    }
    
    public function api()
    {
        $this->view->setContentType('application/json');
        $this->view->layout = false;
        $data = $this->model->getAll();
        $this->set('data', $data);
    }
    
    public function add()
    {
        $this->view->template = $this->getTemplateName('add.tpl.php');
        $this->set('form_template', $this->getTemplateName('form.tpl.php'));
        $this->set('model_description', $this->model->describe());
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
}


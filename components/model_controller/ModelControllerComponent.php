<?php
namespace ntentan\plugins\wyf\components\model_controller;

use ntentan\controllers\components\Component;
use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

class ModelControllerComponent extends Component
{
    public function init()
    {
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/model_controller'));
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/default'));
    }
    
    private function getTemplateName($base)
    {
        // @todo optimize this so it doesn't have to use the str_replace
        return str_replace('.', '_', $this->controller->model->getRoute()) . "_$base";
    }
    
    public function run()
    {
        
        $this->view->template = $this->getTemplateName('list_view.tpl.php');
        $this->set('wyf_add_link', Ntentan::getUrl($this->route . '/add'));
    }
    
    public function api()
    {
        
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


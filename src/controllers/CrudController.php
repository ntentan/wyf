<?php

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\honam\TemplateEngine;
use ntentan\controllers\Url;
use ntentan\utils\Text;
use ntentan\Model;
use ntentan\controllers\Redirect;

/**
 * Description of CrudController
 *
 * @author ekow
 */
class CrudController extends WyfController
{   
    private $operations = [];
    
    public function __construct()
    {
        parent::__construct();
        $this->addOperation('edit');
        $this->addOperation('delete');
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/crud'));
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/forms'));
        
        View::set('entities', $this->getWyfName());
        View::set('entity', Text::singularize($this->getWyfName()));
        View::set('has_add_operation', true);
        View::set('form_template', str_replace('.', '_', $this->getWyfPackage()) . '_form');        
    }

    /**
     * 
     * @return \ntentan\Model
     */
    protected function getModel()
    {
        return Model::load($this->getWyfPackage());
    }

    public function index()
    {
        View::set('add_item_url', Url::action('add'));
        View::set('import_items_url', Url::action('import'));
        View::set('api_url', Url::path('api/' . $this->getWyfPath()));
        View::set('base_url', Url::action(''));
        
        $model = $this->getModel();
        $description = $model->getDescription();
        $fields = $description->getFields();
        $primaryKey = $description->getPrimaryKey()[0];
        
        foreach($fields as $field) {
            if($field['name'] == $primaryKey) continue;
            $listFields[] = [
                'name' => $field['name'],
                'label' => $field['name']
            ];
        }
        
        View::set('list_fields', $listFields);
        View::set('operations', $this->operations);
        View::set('primary_key_field', $primaryKey);
        View::set('foreign_key', false);
    }
    
    public function add()
    {
        View::set('model', $this->getModel()->createNew());
    }
    
    /**
     * @ntentan.action add
     * @ntentan.method POST
     * @ntentan.binder \ntentan\wyf\controllers\CrudModelBinder
     */
    public function store(Model $model)
    {
        if($model->save()) {
            return Redirect::action(null);
        }    
    }
    
    public function edit($id)
    {
        $model = $this->getModel();
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        View::set('model', $this->getModel()->fetchFirst([$primaryKey => $id]));
        View::set('primary_key_field', $primaryKey);
    }
    
    /**
     * @ntentan.action edit
     * @ntentan.method POST
     * @ntentan.binder \ntentan\wyf\controllers\CrudModelBinder
     * 
     * @param Model $model
     * @return type
     */
    public function  update(Model $model)
    {
        if($model->save()) {
            return Redirect::action(null);
        }
    }
    
    protected function addOperation($action, $label = null)
    {
        $this->operations[] = [
            'label' => $label == null ? $action : $label,
            'action' => $action
        ];
    }
}

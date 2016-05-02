<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\honam\TemplateEngine;
use ntentan\controllers\Url;
use ntentan\utils\Text;
use ntentan\Model;

/**
 * Description of CrudController
 *
 * @author ekow
 */
class CrudController extends WyfController
{   
    public function __construct()
    {
        parent::__construct();
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/crud'));
        View::set('entities', $this->getWyfName());
        View::set('entity', Text::singularize($this->getWyfName()));
        View::set('has_add_operation', true);
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
        
        $model = $this->getModel();
        $fields = $model->getDescription()->getFields();
        $listFields = [];
        
        foreach($fields as $field) {
            $listFields[] = [
                'name' => $field['name'],
                'label' => $field['name']
            ];
        }
        
        View::set('list_fields', $listFields);
        View::set('operations', []);
        View::set('foreign_key', false);
    }
    
    public function add()
    {
        
    }
}

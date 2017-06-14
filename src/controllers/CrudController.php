<?php

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\honam\TemplateEngine;
use ntentan\utils\Text;
use ntentan\Model;
use ntentan\Context;
use ntentan\utils\filesystem\UploadedFile;
use ajumamoro\Queue;
use ntentan\wyf\jobs\ImportDataJob;

class CrudController extends WyfController {

    private $operations = [];
    protected $listFields = [];
    protected $importFields = [];
    private $context;

    public function __construct(Context $context) {
        parent::__construct($context);
        $this->context = $context;
        $this->addOperation('edit', 'Edit');
        $this->addOperation('delete', 'Delete');
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/crud'));
        TemplateEngine::appendPath('views/forms');
        TemplateEngine::appendPath('views/lists');

        $wyfPath = $this->getWyfPath();
        $apiUrl = $context->getUrl('api');
        $view = $context->getContainer()->resolve(View::class);        
        $view->set([
            'entities' => $this->getWyfName(),
            'entity' => Text::singularize($this->getWyfName()),
            'has_add_operation' => true,
            'has_import_operation' => true,
            'package' => str_replace('.', '_', $this->getWyfPackage()),
            'api_url' => "$apiUrl/$wyfPath",
            'base_api_url' => $apiUrl,
            'base_url' => $context->getUrl($context->getApp()->getParameters()['controller_path'])
        ]);
    }

    /**
     * 
     * @return \ntentan\Model
     */
    protected function getModel() {
        return Model::load($this->getWyfPackage());
    }

    protected function setListFields($listFields) {
        foreach ($listFields as $label => $name) {
            $this->listFields[] = [
                'name' => $name,
                'label' => is_numeric($label) ? $name : $label
            ];
        }
    }

    public function index(View $view) {
        $model = $this->getModel();

        $description = $model->getDescription();
        $primaryKey = $description->getPrimaryKey()[0];            
        if (empty($this->listFields)) {
            $fields = $description->getFields();
            foreach ($fields as $field) {
                if ($field['name'] == $primaryKey) {
                    continue;
                }
                $this->listFields[$field['name']] = ucwords(str_replace('_', ' ', $field['name']));
            }
        }
        
        $this->setTitle(ucwords($this->getWyfName()));
        // Prevent this from repeating
        $fields = [$primaryKey];
        foreach($this->listFields as $field => $label) {
            $fields[] = is_numeric($field) ? $label : $field;
        }
        $fields = implode(',', $fields);
        $view->set([
            'add_item_url' => $this->getActionUrl('add'),
            'import_items_url' => $this->getActionUrl('import'),
            'api_parameters' => "?fields=$fields",
            'list_fields' => $this->listFields,
            'operations' => $this->operations,
            'primary_key_field' => $primaryKey,
            'foreign_key' => false
        ]);

        return $view;
    }

    public function add(View $view) {
        $view->set('model', $this->getModel()->createNew());
        $this->setTitle("Add new " . ucwords($this->getWyfName()));
        return $view;
    }

    /**
     * @ntentan.action add
     * @ntentan.method POST
     * @ntentan.binder \ntentan\wyf\controllers\CrudModelBinder
     */
    public function store(Model $model, View $view) {
        if ($model->save()) {
            return $this->getRedirect();
        }
        $view->set('model', $model);
        $this->setTitle("Add new {$this->getWyfName()}");
        return $view;
    }
    
    /**
     * 
     * @ntentan.action import
     * @ntentan.method POST
     * @ntentan.binder \ntentan\wyf\controllers\CrudModelBinder
     * 
     * @param UploadedFile $data
     * @return type
     */
    public function importData(UploadedFile $data, Model $model) {
        
        $destination = "uploads/" . basename($data->getPath());
        $data->copyTo($destination);
        $queue = $this->context->getContainer()->resolve(Queue::class);
        $job = new ImportDataJob($destination, $model);
        $job->setAttributes(['file' => $destination]);
        $jobId = $queue->add($job);
        return json_encode($jobId);
    }
    
    public function import(View $view, $id) {
        if($id == 'template') {
            $view->setLayout('plain');
            $view->setTemplate('import_csv');
            $headers = array();
            $modelDescription = $this->getModel()->getDescription();
            $fields = array_keys($modelDescription->getFields());
            $primaryKey = $modelDescription->getPrimaryKey();
            $relationships = $modelDescription->getRelationships();  
            
            foreach($this->importFields as $key => $field) {
                if(is_numeric($key)) {
                    $field = $field;
                    $label = ucwords(str_replace('_', ' ', $field));
                } else {
                    $label = $field;
                    $field = $key;
                }
                
                if(in_array($field, $primaryKey)) continue;
                if(!in_array($field, $fields)) continue;
                $headers[] = $label;
            }
            
            $view->set('headers', $headers);
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename={$this->getWyfName()}.csv");
        } else {
            $view->set('import_template_url', $this->getActionUrl('import/template'));
            $this->setTitle("Import " . ucwords($this->getWyfName()));
        }
        return $view;
    }

    public function edit(View $view, $id) {
        $model = $this->getModel();
        $view->set('model', $this->getModel()->fetchFirst($id));
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $view->set('primary_key_field', $primaryKey);
        $this->setTitle("Edit {$this->getWyfName()}");
        return $view;
    }

    /**
     * @ntentan.action edit
     * @ntentan.method POST
     * @ntentan.binder \ntentan\wyf\controllers\CrudModelBinder
     * 
     * @param Model $model
     * @return type
     */
    public function update(Model $model, View $view) {
        if ($model->save()) {
            return $this->getRedirect();
        }
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $view->set('primary_key_field', $primaryKey);
        $view->set('model', $model);
        $this->setTitle("Edit {$this->getWyfName()}");
        return $view;
    }

    public function delete(View $view, $id, $confirm = null) {
        $model = $this->getModel();
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $item = $model->fetchFirst([$primaryKey => $id]);
        if ($confirm == 'yes') {
            $item->delete();
            return $this->getRedirect();
        }
        $view->set('item', $item);
        $view->set('delete_yes_url', $this->getActionUrl("delete/$id?confirm=yes"));
        $view->set('delete_no_url', $this->getActionUrl(''));
        return $view;
    }

    protected function addOperation($action, $label = null) {
        $this->operations[] = [
            'label' => $label == null ? $action : $label,
            'action' => $action
        ];
    }

}

<?php

namespace ntentan\wyf\controllers;

use ntentan\View;
use ntentan\honam\TemplateEngine;
use ntentan\controllers\Url;
use ntentan\utils\Text;
use ntentan\Model;
use ntentan\Context;
use ntentan\Redirect;

/**
 * Description of CrudController
 * @author ekow
 */
class CrudController extends WyfController {

    private $operations = [];
    protected $listFields = [];
    private $context;

    public function __construct(Context $context) {
        parent::__construct($context);
        $this->context = $context;
        $view = $context->getContainer()->resolve(View::class);
        $this->addOperation('edit', 'Edit');
        $this->addOperation('delete', 'Delete');
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../views/crud'));
        TemplateEngine::appendPath('views/forms');

        $view->set('entities', $this->getWyfName());
        $view->set('entity', Text::singularize($this->getWyfName()));
        $view->set('has_add_operation', true);
        $view->set('form_template', str_replace('.', '_', $this->getWyfPackage()) . '_form');
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
        
        $this->setTitle($this->getWyfName());
        // Prevent this from repeating
        $fields = [$primaryKey];
        foreach($this->listFields as $field => $label) {
            $fields[] = is_numeric($field) ? $label : $field;
        }
        $fields = implode(',', $fields);
        $view->set([
            'add_item_url' => $this->getActionUrl('add'),
            'import_items_url'=> $this->getActionUrl('import'),
            'api_url' => $this->context->getUrl('api/' . $this->getWyfPath() . "?fields=$fields"),
            'base_url' => $this->getActionUrl(''),
            'list_fields' => $this->listFields,
            'operations' => $this->operations,
            'primary_key_field' => $primaryKey,
            'foreign_key' => false
        ]);

        return $view;
    }

    public function add(View $view) {
        $view->set('model', $this->getModel()->createNew());
        $this->setTitle("Add new {$this->getWyfName()}");
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

    public function edit($id) {
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
    public function update(Model $model) {
        if ($model->save()) {
            return Redirect::action(null);
        }
    }

    public function delete($id, $confirm = null) {
        $model = $this->getModel();
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $item = $model->fetchFirst([$primaryKey => $id]);
        if ($confirm == 'yes') {
            $item->delete();
            return Redirect::action('');
        } else {
            View::set('item', $item);
            View::set('delete_yes_url', Url::action("delete/$id", ['confirm' => 'yes']));
            View::set('delete_no_url', Url::action(''));
        }
    }

    protected function addOperation($action, $label = null) {
        $this->operations[] = [
            'label' => $label == null ? $action : $label,
            'action' => $action
        ];
    }

}

<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\utils\Text;
use ntentan\Model;
use ntentan\utils\Input;

/**
 * A model field generates a standard selection list populated with items from
 * a model. 
 */
class ModelField extends SelectField {
    
    private $hasAdd;
    private $model;
    

    /**
     * If a string is passed, initialize and return a Model. However if a model
     * is passed, allow it to pass through.
     * @param string|\ntentan\Model $model
     * @return \ntentan\Model
     */
    private function initialize($model) {
        if (is_string($model)) {
            $object = Model::load($model);
        } else {
            $object = $model;
        }
        return $object;
    }

    /**
     * Create a new ModelField.
     * @param string|\ntentan\Model $model A string as the model name or 
     *          an instance of a model
     */
    public function __construct($model, $formTemplate = null, $apiUrl = null) {
        $instance = $this->initialize($model);
        $name = Text::deCamelize($instance->getName());
        $label = Text::singularize(ucwords(str_replace('_', ' ', $name)));
        $this->setLabel($label);
        $this->setName(Text::singularize($name) . '_id');
        $options = $instance->fetch();
        foreach ($options as $option) {
            $this->addOption((string) $option, $option->id);
        }
        if($formTemplate && is_string($model)) {
            $this->hasAdd = true;
            $this->model = Text::singularize($model);
            if($apiUrl === null && is_string($model)) {
                $apiUrl = self::$sharedFormData['base_api_url'] . "/" . str_replace(".", "/", $model);
            }
            $this->set('has_add', true);
            $this->set('model', $instance);
            $this->set('form_template', $formTemplate);
            $this->set('entity', $name);
            $this->set('api_url', $apiUrl);
            if(count($options)) {
                $this->addOption("---", "-");
            }
            $this->addOption("Add a new {$this->getLabel()}", 'new');
            $this->setAttribute('onchange', "wyf.forms.showCreateItemForm('$name', this)")
                ->setAttribute('package', $this->model);
        }
    }
    
    public function getValue() {
        $value = parent::getValue();
        if($value == '-1') {
            $this->options = ['-1' => Input::post($this->model)] + $this->options;
            $postFields = Input::post();
            $lenght = strlen($this->model);
            $hiddenFields = [];
            foreach($postFields as $key => $postedValue) {
                if(substr($key, 0, $lenght) == $this->model) {
                    $hiddenFields[$key] = $postedValue;
                }
            }
            $this->set('hidden_fields', $hiddenFields);
            $this->set('options', $this->options);
        }
        return $value;
    }

}

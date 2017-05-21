<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\utils\Text;
use ntentan\Model;

/**
 * A model field generates a standard selection list populated with items from
 * a model. 
 */
class ModelField extends SelectField {
    

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
        if($formTemplate) {
            if($apiUrl === null && is_string($model)) {
                $apiUrl = self::$sharedFormData['base_api_url'] . "/" . str_replace(".", "/", $model);
            }
            $this->set('has_add', true);
            $this->set('model', $instance);
            $this->set('form_template', $formTemplate);
            $this->set('entity', $label);
            $this->set('package', $name);
            $this->set('api_url', $apiUrl);
            if(count($options)) {
                $this->addOption("---", "-");
            }
            $this->addOption("Add a new {$this->getLabel()}", 'new');
            $this->setAttribute('onchange', "wyf.forms.showCreateItemForm('$name', this)");
        }
    }

}

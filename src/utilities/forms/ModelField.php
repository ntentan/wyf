<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\utils\Text;
use ntentan\Model;
use ntentan\utils\Input;

/**
 * A model field generates a standard selection list populated with items from
 * a model.
 */
class ModelField extends SelectField
{

    private $hasAdd;
    private $model;

    /**
     * Create a new ModelField.
     * @param string|\ntentan\Model $model A string as the model name or
     *          an instance of a model
     */
    public function __construct($model, $formTemplate = null, $apiUrl = null)
    {
        $instance = Model::load($model);
        $name = Text::deCamelize($instance->getName());
        $label = Text::singularize(ucwords(str_replace('_', ' ', $name)));
        $this->setLabel($label);
        $this->setName(Text::singularize($name) . '_id');
        $options = $instance->fetch();

        if (($formTemplate && is_string($model)) || $this->hasAdd) {
            $this->hasAdd = true;
            $this->model = Text::singularize($model);
            if ($apiUrl === null && is_string($model)) {
                $apiUrl = self::$sharedFormData['base_api_url'] . "/" . str_replace(".", "/", $model);
            }
            $this->set('model', $instance);
            $this->set('form_template', $formTemplate);
            $this->set('entity', $name);
            $this->set('api_url', $apiUrl);
            $this->addOption("⊕ Add a new {$this->getLabel()}", 'new');
            if (count($options)) {
                $this->addOption("────────────", "-");
            }
            $this->setAttribute('onchange', "wyf.forms.showCreateItemForm(this, '{$name}_add_form')")
                ->setAttribute('package', $this->model);
        }
        $this->set('has_add', $this->hasAdd);

        foreach ($options as $option) {
            $this->addOption((string)$option, $option->id);
        }
    }

    public function getValue()
    {
        $value = parent::getValue();
        $hiddenFields = [];

        // If a new item is being added extract the details from post data and
        // setup hidden fields.
        if ($value == '-1') {
            $this->options = ['-1' => Input::post($this->model)] + $this->options;
            $postFields = Input::post();
            $lenght = strlen($this->model);
            foreach ($postFields as $key => $postedValue) {
                if (substr($key, 0, $lenght) == $this->model) {
                    $hiddenFields[$key] = $postedValue;
                }
            }
            $this->set('options', $this->options);
        }
        $this->set('hidden_fields', $hiddenFields);
        return $value;
    }

}

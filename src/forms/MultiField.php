<?php

namespace ntentan\wyf\forms;

use ntentan\Model;
use ntentan\utils\Text;

class MultiField extends SelectField
{

    public function __construct($model, $formTemplate = null, $apiUrl = null)
    {
        $instance = Model::load($model);
        $type = strtolower($instance->getName());
        $primaryKey = $instance->getDescription()->getPrimaryKey()[0];
        $label = ucwords(str_replace('_', ' ', $type));
        $this->setLabel($label);
        $this->setName($model);
        $this->setAttribute('id', $type);
        $this->set('type', $type);
        $this->set('entity', Text::singularize($label));
        $this->set('package', $model);
        $this->set('form_template', $formTemplate);
        $this->set('primary_key', $primaryKey);
        if ($apiUrl === null && is_string($model)) {
            $apiUrl = self::$sharedFormData['base_api_url'] . "/" . str_replace(".", "/", $model);
        }
        $this->set('api_url', $apiUrl);
    }

    public function getValue()
    {
        $value = parent::getValue();
        if (is_a($value, Model::class)) {
            $value = $value->toArray();
        }
        return $value;
    }

}
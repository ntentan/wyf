<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\Model;
use ntentan\utils\Text;

class ModelSearchField extends Input
{

    public function generateDefaultTempplate($fields)
    {
        return array_reduce($fields, function ($carry, $item) {
            return "$carry {{{$item}}}";
        });
    }

    public function __construct($model, array $fields, $listItemTemplate = null, $valueTemplate = null)
    {
        $modelInstance = Model::load($model);
        $entity = Text::singularize($modelInstance->getName());
        $this->setLabel($entity);
        $name = strtolower("{$entity}_id");
        $this->setName($name);
        $apiUrl = self::$sharedFormData['base_api_url'] . "/" . str_replace(".", "/", $model);
        $this->set('api_url', $apiUrl);
        $this->set('list_template', $listItemTemplate ?? trim($this->generateDefaultTempplate($fields)));
        $this->set('value_template', $valueTemplate ?? trim($this->generateDefaultTempplate($fields)));
        $fields = implode(',', $fields);
        $this->setAttribute('onkeyup', "wyf.forms.updateModelSearchField(this, event,'$apiUrl', '$fields', '$name')");
        $this->setAttribute('autocomplete', 'off');
    }


}

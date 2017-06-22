<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\Model;
use ntentan\utils\Text;

class ModelSearchField extends Input {

    public function __construct($model, $fields=[], $template = "") {
        $modelInstance = Model::load($model);
        $entity = Text::singularize($modelInstance->getName());
        $this->setLabel($entity);
        $name = strtolower("{$entity}_id");
        $this->setName($name);
        $apiUrl = self::$sharedFormData['base_api_url'] . "/" . str_replace(".", "/", $model);
        $fields = implode(',', $fields);
        $this->setAttribute('onkeyup', "wyf.forms.updateModelSearchField(this,'$apiUrl', '$fields', '$name')");
        $this->set('api_url', $apiUrl);
        $this->set('template', $template);
    }

}

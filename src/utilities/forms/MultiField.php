<?php
namespace ntentan\wyf\utilities\forms;

use ntentan\Model;
use ntentan\utils\Text;

class MultiField extends ModelField {
    
    public function __construct($model, $formTemplate = null, $apiUrl = null) {
        $type = Model::load($model)->getName(); 
        $label = ucwords(str_replace('_', ' ', $type));
        $this->setLabel($label);
        $this->setName("{$model}[]");
        $this->set('type', strtolower($type));
        $this->set('entity', Text::singularize($label));
        $this->set('package', $model);
        $this->set('form_template', $formTemplate);
    }
    
}
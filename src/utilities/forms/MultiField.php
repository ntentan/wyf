<?php
namespace ntentan\wyf\utilities\forms;

use ntentan\Model;
use ntentan\utils\Text;

class MultiField extends ModelField {
    
    public function __construct($model, $formTemplate = null, $apiUrl = null) {
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
    }
    
}
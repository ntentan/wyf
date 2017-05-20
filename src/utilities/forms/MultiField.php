<?php
namespace ntentan\wyf\utilities\forms;

class MultiField extends Input {
    
    public function __construct($name = '', $label = null, $formTemplate = null) {
        parent::__construct("{$name}[]", $label);
        $this->set('form_template', $formTemplate);
        $this->set('field_name', str_replace('.', '-', $name));
    }
    
    public function setFormTemplateData($formTemplateData) {
        $this->set('form_template_data', $formTemplateData);
        return $this;
    }
    
}
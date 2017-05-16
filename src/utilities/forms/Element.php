<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\honam\TemplateEngine;
use ntentan\utils\Text;

class Element {

    protected $label;
    protected $attributes = array();
    protected $errors;
    protected $variables = array();
    protected $renderWithType;
    protected $description;
    protected $parent;

    public function __toString() {
        $type = $this->renderWithType == '' 
            ? \ntentan\utils\Text::deCamelize($this->getType()) 
            : $this->renderWithType;
        
        return TemplateEngine::render(
            "wyf_inputs_forms_{$type}.tpl.php", $this->getTemplateVariables()
        );
    }

    public function getType() {
        $class = new \ReflectionClass($this);
        $array = explode('\\', $class->getName());
        return end($array);
    }

    public function setAttribute($attribute, $value) {
        $this->attributes[$attribute] = $value;
        return $this;
    }
    
    public function getAttribute($attribute) {
        return $this->attributes[$attribute] ?? null;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
        return $this;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function setLabel($label = false) {
        $this->label = $label;
        return $this;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setDescription($description = false) {
        $this->description = $description;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    private function renderAttributes() {
        return TemplateEngine::render(
            'wyf_inputs_forms_attributes', ['attributes' => $this->attributes]
        );
    }

    public function getTemplateVariables() {
        return $this->variables + [
            'label' => $this->label,
            'attributes' => $this->renderAttributes(),
            'extra_css_classes' => count($this->getErrors()) > 0 ? 'form-error' : ''
        ];
    }

    protected function set($key, $value) {
        $this->variables[$key] = $value;
    }

    public static function create() {
        $args = func_get_args();
        $type = array_shift($args);
        $typeClass = new \ReflectionClass(
            'ntentan\\wyf\\utilities\\forms\\' .
            Text::ucamelize($type)
        );
        return $typeClass->newInstanceArgs($args);
    }
    
    public function setParent($parent) {
        $this->parent = $parent;
    }

}

<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\utils\Text;

class Container extends Element {

    /**
     *
     * @var array<\ntentan\honam\helpers\form\Element>
     */
    protected $elements = array();
    private $data;

    public function add() {
        $elements = func_get_args();
        foreach ($elements as $element) {
            $element->setParent($this);
            $this->elements [] = $element;
        }
        return $this;
    }

    public function getTemplateVariables() {
        return [
            'elements' => $this->elements,
        ] + parent::getTemplateVariables();
    }
    
    /**
     * 
     * @param type $element
     * @return type
     */
    public function getValueFor($element) {
        // If we have data for element return else call my parent for that or 
        // return null if I have no parents
        return $this->data[$element->getName()] ?? 
            ($this->parent ? $this->parent->getValueFor($element) : null);
    }
    
    public function setData($data) {
        $this->data = $data;
    }
    
}

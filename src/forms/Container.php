<?php

namespace ntentan\wyf\forms;

class Container extends Element
{
    protected $elements = array();
    private $data;

    public function add()
    {
        $elements = func_get_args();
        foreach ($elements as $element) {
            $element->setParent($this);
            $this->elements [] = $element;
        }
        return $this;
    }

    public function getTemplateVariables()
    {
        return [
                'elements' => $this->elements,
            ] + parent::getTemplateVariables();
    }

    public function getValueFor($element)
    {
        // If we have data for element return else call my parent for that or 
        // return null if I have no parents
        return $this->data[$element->getName()] ??
            ($this->parent ? $this->parent->getValueFor($element) : null);
    }

    public function getErrorsFor($element)
    {
        return $this->errors[$element->getName()] ??
            ($this->parent ? $this->parent->getErrorsFor($element) : null);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

}

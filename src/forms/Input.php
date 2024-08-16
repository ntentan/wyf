<?php

namespace ntentan\wyf\forms;

class Input extends Element
{
    protected $name;
    protected $value;

    public function __construct($name = '', $label = null)
    {
        $this->setLabel($label == null ? ucfirst(str_replace('_', ' ', $name)) : $label);
        $this->setName($name);
    }

    public function setName($name = false)
    {
        $this->name = $name;
        if (!isset($this->attributes['id'])) {
            $this->setAttribute('id', $name);
        }
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getTemplateVariables()
    {
        return [
                'value' => $this->getValue(),
                'name' => $this->getName()
            ] + parent::getTemplateVariables();
    }
}

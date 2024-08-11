<?php
namespace ntentan\wyf\forms;


class Container extends Element
{
    private array $elements = [];

    public function add(Element ...$elements)
    {
        foreach ($elements as $element) {
            $element->setParent($this);
            $this->elements [] = $element;
        }
        return $this;
    }

    public function getTemplateVariables()
    {
        return ['elements' => $this->elements] + parent::getTemplateVariables();
    }
    
    public function getInputFor(string $name): ?Input
    {
        foreach($this->elements as $element) {
            if ($element instanceof Input && $element->getName() == $name) {
                return $element;
            } else if ($element instanceof Container && $element = $element->getElementFor($name)) {
                return $element;
            }
        }
        return null;
    }

    public function setData(array $data): Container
    {
        foreach ($data as $name => $value) {
            $element = $this->getInputFor($name);
            if ($element !== null) {
                $element->setValue($value);
            }
        }
        return $this;
    }
    
    public function setErrors(array $errors): Element 
    {
        parent::setErrors($errors);
        foreach ($errors as $name => $elementErrors) {
            $element = $this->getInputFor($name);
            if ($element !== null) {
                $element->setErrors($elementErrors);
            }
        }
        return $this;
    }
}

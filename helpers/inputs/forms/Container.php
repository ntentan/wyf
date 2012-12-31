<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

abstract class Container extends Element
{
    protected $elements = array();
    
    public function add($element)
    {
        $this->elements []= $element;
    }
    
    public function getTemplateVariables()
    {
        $variables = array(
            'elements' => $this->elements,
            'layout' => 'flowing'
        );
        
        return array_merge($variables, parent::getTemplateVariables());
    }
    
    public function value($data = false)
    {
        foreach($this->elements as $element)
        {
            $element->value($data[$element->name()]);
        }
    }
}

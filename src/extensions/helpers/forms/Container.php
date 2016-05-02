<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class Container extends Element
{
    protected $elements = array();
    
    public function add()
    {
        $elements = func_get_args();
        foreach($elements as $element)
        {
            $element->data($this->data[$element->name()]);
            $element->errors($this->errors[$element->name()]);
            $this->elements []= $element;
        }
        return $this;
    }
    
    public function getTemplateVariables()
    {
        $variables = array(
            'elements' => $this->elements,
            'layout' => 'flowing'
        );
        
        return array_merge($variables, parent::getTemplateVariables());
    }
    
    public function data($data = false)
    {
        $this->data = $data;
        foreach($this->elements as $element)
        {
            if(is_a($element, "\\ntentan\\plugins\\wyf\\helpers\\inputs\\forms\\Container"))
            {
                $element->data($data);
            }
            else
            {
                if(isset($data[$element->name()]))
                {
                    $element->data($data[$element->name()]);
                }
            }
        }
    }
    
    public function errors($errors = false)
    {
        foreach($this->elements as $element)
        {
            if(is_a($element, "\\ntentan\\plugins\\wyf\\helpers\\inputs\\forms\\Container"))
            {
                $element->errors($errors);
            }
            else
            {
                if(isset($errors[$element->name()]))
                {
                    $element->errors($errors[$element->name()]);
                }
            }
        }        
    }
}

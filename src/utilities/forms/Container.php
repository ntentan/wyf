<?php
namespace ntentan\wyf\utilities\forms;

use ntentan\utils\Text;

class Container extends Element
{
    protected $elements = array();
    
    public function add()
    {
        $elements = func_get_args();
        foreach($elements as $element)
        {
            $element->setData($this->data[$element->getName()]);
            $element->setErrors($this->errors[$element->getName()]);
            $this->elements []= $element;
        }
        return $this;
    }
    
    /*public static function create($string)
    {
        var_dump(Text::camelize($string));
    }*/
    
    public function getTemplateVariables()
    {
        $variables = array(
            'elements' => $this->elements,
            'layout' => 'flowing'
        );
        
        return array_merge($variables, parent::getTemplateVariables());
    }
    
    public function setData($data = false)
    {
        $this->data = $data;
        foreach($this->elements as $element)
        {
            if(is_a($element, "\\ntentan\\plugins\\wyf\\helpers\\inputs\\forms\\Container"))
            {
                $element->setData($data);
            }
            else
            {
                if(isset($data[$element->getName()]))
                {
                    $element->setData($data[$element->getName()]);
                }
            }
        }
    }
    
    public function setErrors($errors = false)
    {
        foreach($this->elements as $element)
        {
            if(is_a($element, "\\ntentan\\plugins\\wyf\\helpers\\inputs\\forms\\Container"))
            {
                $element->setErrors($errors);
            }
            else
            {
                if(isset($errors[$element->name()]))
                {
                    $element->setErrors($errors[$element->name()]);
                }
            }
        }        
    }
}

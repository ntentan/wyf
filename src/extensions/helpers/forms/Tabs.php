<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

class Tabs extends Container
{
    private $tabs = array();
    
    public function data($data = false)
    {
        foreach($this->tabs as $tab)
        {
            $tab->data($data);
        }
    }
    
    public function errors($errors = false)
    {
        foreach($this->tabs as $tab)
        {
            $tab->errors($errors);
        }
    }
    
    public function tab()
    {
        $arguments = func_get_args();
        $label = array_shift($arguments);
        $tab = new tabs\Tab($label);
        
        foreach($arguments as $element)
        {
            $tab->add($element);
        }
        
        $this->tabs[] = $tab;
        return $this;
    }
    
    public function id($id)
    {
        $this->set('id', $id);
        $this->attribute('id', $id);
        return $this;
    }
    
    public function __toString() 
    {
        $this->set('tabs', $this->tabs);
        return parent::__toString();
    }
}


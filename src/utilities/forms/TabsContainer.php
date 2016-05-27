<?php
namespace ntentan\extensions\wyf\helpers\forms;

class TabsContainer extends Container
{
    private $tabs = array();
    
    public function setData($data = false)
    {
        foreach($this->tabs as $tab)
        {
            $tab->setData($data);
        }
        return $this;
    }
    
    public function setErrors($errors = false)
    {
        foreach($this->tabs as $tab)
        {
            $tab->setErrors($errors);
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
        $this->setAttribute('id', $id);
        return $this;
    }
    
    public function __toString() 
    {
        $this->set('tabs', $this->tabs);
        return parent::__toString();
    }
}


<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\utils\Text;

class TabsContainer extends Container {

    private $tabs = [];

    public function setValue($data = false) {
        foreach ($this->tabs as $tab) {
            $tab->setValue($data);
        }
        return $this;
    }

    public function setErrors($errors = false) {
        foreach ($this->tabs as $tab) {
            $tab->setErrors($errors);
        }
    }

    public function add() {
        $arguments = func_get_args();
        $label = array_shift($arguments);
        $tab = new tabs\Tab($label);
        $tab->setId(Text::deCamelize($label));

        foreach ($arguments as $element) {
            $tab->add($element);
        }

        $this->tabs[] = $tab;
        return $this;
    }

    public function id($id) {
        $this->setTemplateVariable('id', $id);
        $this->setAttribute('id', $id);
        return $this;
    }

    public function __toString() {
        $this->setTemplateVariable('tabs', $this->tabs);
        return parent::__toString();
    }

}

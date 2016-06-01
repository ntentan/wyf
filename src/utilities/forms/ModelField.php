<?php
namespace ntentan\wyf\utilities\forms;

use ntentan\Ntentan;

class ModelField extends SelectField
{
    public function __construct($model, $name)
    {
        $this->renderWithType = 'select';
        //$entity = Ntentan::singular($model->getName());
        //$this->setLabel(Ntentan::toSentence($entity));
        $this->setLabel('Role');
        $this->setName($name);
        
        $options = $model->fetch();
        foreach($options as $option)
        {
            $this->option((string)$option, $option->id);
        }
    }
}

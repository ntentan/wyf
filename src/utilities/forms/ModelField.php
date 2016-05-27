<?php
namespace ntentan\extensions\wyf\helpers\forms;

use ntentan\Ntentan;

class ModelField extends Select
{
    public function __construct($model)
    {
        $this->renderWithType = 'select';
        $model = \ntentan\models\Model::load($model);
        $entity = Ntentan::singular($model->getName());
        $this->setLabel(Ntentan::toSentence($entity));
        $this->setName("{$entity}_id");
        
        $options = $model->getAll();
        foreach($options as $option)
        {
            $this->option((string)$option, $option->id);
        }
    }
}

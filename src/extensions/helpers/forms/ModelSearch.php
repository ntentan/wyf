<?php
namespace ntentan\extensions\wyf\helpers\forms;

use ntentan\Ntentan;

class ModelSearch extends Select
{
    public function __construct($model)
    {
        $this->renderWithType = 'select';
        $model = \ntentan\models\Model::load($model);
        $entity = Ntentan::singular($model->getName());
        $this->label(Ntentan::toSentence($entity));
        $this->name("{$entity}_id");
        
        $options = $model->getAll();
        foreach($options as $option)
        {
            $this->option((string)$option, $option->id);
        }
    }
}

<?php
namespace ntentan\plugins\wyf\helpers\inputs\forms;

use ntentan\Ntentan;

class Relate extends Select
{
    public function __construct($model, $submodel)
    {
        $this->renderWithType = 'select';
        $model = \ntentan\models\Model::load($model);
        $submodel = \ntentan\models\Model::load($submodel);
        $entity = Ntentan::singular($submodel->getName());
        $parentEntity = Ntentan::singular($model->getName());
        
        $this->label(Ntentan::toSentence($entity));
        $this->name("{$entity}_id");
        $parentId = "{$parentEntity}_id";
        
        
        $options = $model->getAll();
        foreach($options as $option)
        {
            $suboptions = $submodel->getAll(array('conditions' => array($parentId => $option->id)));
            foreach($suboptions as $suboption)
            {
                $this->option("{$option} / {$suboption}", $suboption->id);
            }
        }
    }
}

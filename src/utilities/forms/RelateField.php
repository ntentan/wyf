<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\Model;
use ntentan\utils\Text;


class RelateField extends Input
{

    public function __construct($model, $submodel)
    {
        $this->renderWithType = 'select';
        $model = Model::load($model);
        $submodel = Model::load($submodel);
        $entity = Text::singular($submodel->getName());
        $parentEntity = Text::singular($model->getName());

        //$this->setLabel(Ntentan::toSentence($entity));
        $this->setName("{$entity}_id");
        $parentId = "{$parentEntity}_id";


        $options = $model->getAll();
        foreach ($options as $option) {
            $suboptions = $submodel->getAll(array('conditions' => array($parentId => $option->id)));
            foreach ($suboptions as $suboption) {
                $this->option("{$option} / {$suboption}", $suboption->id);
            }
        }
    }

}

<?php

namespace ntentan\wyf\controllers\crud;


use ntentan\Model;
use ntentan\utils\Text;
use ntentan\View;

class ImportTemplateView extends View
{
    public function setModel(Model $model, array $importFields, $entity = 'item')
    {
        $this->setLayout('plain');
        $this->setTemplate('import_csv');
        $headers = array();
        $modelDescription = $model->getDescription();
        $fields = array_keys($modelDescription->getFields());
        $relationshipDetails = $modelDescription->getRelationships();
        $relationships = array_keys($relationshipDetails);

        foreach ($importFields as $key => $field) {
            if (is_numeric($key)) {
                if (is_array($field) && in_array($field[0], $relationships)) {
                    $label = $relationshipDetails[$field[0]]->getModelInstance()->getName();
                    $headers[] = Text::singularize(ucwords(str_replace('_', ' ', Text::deCamelize($label))));
                } else if (in_array($field, $fields)) {
                    $headers[] = ucwords(str_replace('_', ' ', $field));
                }
            } else {
                $headers[] = $key;
            }
        }

        $this->set('headers', $headers);
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$entity}_template.csv");
        
    }
}
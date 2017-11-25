<?php

namespace ntentan\wyf\controllers\crud;


use ntentan\Model;
use ntentan\utils\Text;
use ntentan\View;

class ImportTemplateView extends View
{
    private $fields;
    private $relationshipDetails;
    private $relationships;

    private function getLabeledField($field)
    {
        if (is_array($field) && in_array($field[0], $this->relationships)) {
            $label = $this->relationshipDetails[$field[0]]->getModelInstance()->getName();
            return Text::singularize(ucwords(str_replace('_', ' ', Text::deCamelize($label))));
        } else if (in_array($field, $this->fields)) {
            return ucwords(str_replace('_', ' ', $field));
        }
    }

    public function setModel(Model $model, array $importFields, $entity = 'item')
    {
        $this->setLayout('plain');
        $this->setTemplate('import_csv');
        $headers = array();
        $modelDescription = $model->getDescription();
        $this->fields = array_keys($modelDescription->getFields());
        $this->relationshipDetails = $modelDescription->getRelationships();
        $this->relationships = array_keys($this->relationshipDetails);

        foreach ($importFields as $key => $field) {
            if (is_numeric($key)) {
                $headers[] = $this->getLabeledField($field);
            } else {
                $headers[] = $key;
            }
        }

        $this->set('headers', $headers);
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename={$entity}_template.csv");
        
    }
}
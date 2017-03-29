<?php

namespace ntentan\wyf\utilities\forms;

use ntentan\honam\TemplateEngine;
use ntentan\Ntentan;

class Form extends Container {

    private $submitValue = 'Save';

    public function __construct() {
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-grid');
    }

    public function setSubmitValue($submitValue) {
        $this->submitValue = $submitValue;
        return $this;
    }

    public function getTemplateVariables() {
        return array_merge(
                parent::getTemplateVariables(), array(
            'submit_value' => $this->submitValue
                )
        );
    }

    public function setErrors($errors = false) {
        if ($errors === false)
            return;
        $this->errors = $errors;
    }

    public function __toString() {
        return TemplateEngine::render(
                        'wyf_forms_form', $this->getTemplateVariables()
        );
    }

    /**
     * 
     * @param \ntentan\Model $model
     * @return \ntentan\wyf\utilities\forms\Form
     */
    public function forModel($model) {
        $description = $model->getDescription();
        $relationships = $description->getRelationships();
        $fields = $description->getFields();
        $autoPrimaryKey = $description->getAutoPrimaryKey();
        $primaryKeys = $description->getPrimaryKey();

        foreach ($relationships as $relationship) {
            $model = $relationship->getModelInstance();
            $parameters = $relationship->getOptions();
            if (isset($fields[$parameters['local_key']])) {
                $fields[$parameters['local_key']]['model'] = $model;
            }
        }

        foreach ($fields as $field) {
            // Do not display primary keys on form
            if ($autoPrimaryKey && array_search($field['name'], $primaryKeys) !== false)
                continue;

            if (isset($field['model'])) {
                $this->add(new ModelField($field['model'], $field['name']));
            } else {
                $this->add($this->inputForField($field)->setValue($model[$field['name']]));
            }
        }

        return $this;
    }

    private function inputForField($field) {
        $input = null;
        switch ($field['type']) {
            case 'string':
            case 'integer':
            case 'double':
                $input = new TextField($field['name']);
                break;
            case 'date':
                $input = new DateField($field['name']);
                break;
            case 'boolean':
                $input = new Checkbox($field['name']);
                break;
            default:
                throw new \Exception("Unknown type {$field['type']}");
        }
        return $input;
    }

}

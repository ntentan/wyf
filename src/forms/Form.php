<?php
namespace ntentan\wyf\forms;

use ntentan\wyf\utilities\forms\Checkbox;
use ntentan\wyf\utilities\forms\DateField;
use ntentan\wyf\WyfException;

class Form extends Container
{
    private string $submitValue = 'Save';

    public function __construct()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-grid');
        $this->setTag('form');
    }

    public function setSubmitValue($submitValue)
    {
        $this->submitValue = $submitValue;
        if ($submitValue !== false) {
            $this->getSubmitButton()->setValue($submitValue);
        }
        return $this;
    }

    public function getTemplateVariables()
    {
        return array_merge(
            parent::getTemplateVariables(), array(
                'submit_value' => $this->submitValue,
                'submit_button' => false
            )
        );
    }

    public function setTag($tag)
    {
        $this->set('tag', $tag);
        return $this;
    }

    public function setErrors($errors = false)
    {
        if ($errors === false) {
            return;
        }
        $this->errors = $errors;
    }

    public function __toString()
    {
        $this->getTemplateEngine()->render('wyf_forms_form', $this->getTemplateVariables());
    }

    public function forModel($model)
    {
        $description = $model->getDescription();
        $relationships = $description->getRelationships();
        $fields = $description->getFields();
        $autoPrimaryKey = $description->getAutoPrimaryKey();
        $primaryKeys = $description->getPrimaryKey();

        foreach ($relationships ?? [] as $relationship) {
            $model = $relationship->getModelInstance();
            $parameters = $relationship->getOptions();
            if (isset($fields[$parameters['local_key']])) {
                $fields[$parameters['local_key']]['model'] = $model;
            }
        }

        foreach ($fields as $field) {
            // Do not display primary keys on form
            if ($autoPrimaryKey && array_search($field['name'], $primaryKeys) !== false) {
                continue;
            }
            $this->add(match($field['type']) {
                'string', 'integer', 'double' => f::create('text', $field['name']),
                'date' => $this->create(DateField::class, $field['name']),
                'boolean' => $this->create(Checkbox::class, $field['name']),
                default => throw new WyfException("Unknown form field type {$field['type']}")
            });
        }

        return $this;
    }
}

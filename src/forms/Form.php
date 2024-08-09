<?php
namespace ntentan\wyf\forms;

use ntentan\wyf\utilities\forms\Checkbox;
use ntentan\wyf\utilities\forms\DateField;
use ntentan\wyf\WyfException;

class Form extends Container
{
    private string $submitValue = 'Save';
    private SubmitButton $submitButton; 

    public function __construct(SubmitButton $submitButton)
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-grid');
        $this->submitButton = $submitButton;
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
                'submit_button' => $this->submitButton
            )
        );
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
        return $this->getTemplateEngine()->render('wyf_forms_form', $this->getTemplateVariables());
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

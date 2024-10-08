<?php
namespace ntentan\wyf\forms;

use ntentan\mvc\Model;
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

    public function setSubmitValue($submitValue): Form
    {
        $this->submitValue = $submitValue;
        if ($submitValue !== false) {
            $this->submitButton->setValue($submitValue);
        }
        return $this;
    }

    public function getTemplateVariables(): array
    {
        return array_merge(
            parent::getTemplateVariables(), array(
                'submit_value' => $this->submitValue,
                'submit_button' => $this->submitButton
            )
        );
    }

    public function __toString()
    {
        return $this->getTemplateEngine()->render('wyf_forms_form', $this->getTemplateVariables());
    }

    public function forModel(Model $model, array $filters = []): Form
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

            if (isset($filters[$field['name']])) {
                $this->add(f::create('hidden', $field['name'])->setValue($filters[$field['name']]));
            } else {
                $this->add(
                    isset($field['model']) ?
                        f::create('model', $field['model'], $field)
                        : match($field['type']) {
                        'string', 'integer', 'double' => f::create('text', $field['name']),
                        'date', 'datetime' => f::create('date', $field['name']),
                        'boolean' => f::create('checkbox', $field['name']),
                        default => throw new WyfException("Unknown form field type {$field['type']}")
                    });
            }
        }

        return $this;
    }
}

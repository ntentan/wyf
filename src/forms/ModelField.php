<?php

namespace ntentan\wyf\forms;

use ntentan\utils\Text;
use ntentan\mvc\Model;
use ntentan\utils\Input;

/**
 * A model field generates a standard selection list populated with items from
 * a model.
 */
class ModelField extends SelectField
{
    private Model $model;

    /**
     * Create a new ModelField.
     * @param string|\ntentan\Model $model A string as the model name or
     *          an instance of a model
     */
    public function __construct(Model|string $instance)
    {
        if (is_string($instance)) {
            $instance = Model::load($instance);
        }
        $name = Text::deCamelize($instance->getName());
        $label = Text::singularize(ucwords(str_replace('_', ' ', $name)));
        $this->setLabel($label);
        $this->setName(Text::singularize($name) . '_id');
        $options = $instance->fetch();

        foreach ($options as $option) {
            $this->addOption((string) $option, $option->id);
        }
    }

    public function getValue()
    {
        $value = parent::getValue();
        $hiddenFields = [];
        $this->set('hidden_fields', $hiddenFields);
        return $value;
    }

}

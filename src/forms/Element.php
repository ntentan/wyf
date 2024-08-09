<?php

namespace ntentan\wyf\forms;

use ntentan\honam\Templates;

/**
 * Base class for all form elements.
 *
 * @package ntentan\wyf\utilities\forms
 */
class Element
{

    /**
     * A label to be rendered along with this element.
     * @var string
     */
    protected string $label;

    /**
     * An associative key-value array of attributes for the element.
     * @var array
     */
    protected array $attributes = [];

    /**
     * An associative key-value array of errors.
     * @var array
     */
    protected array $errors;

    /**
     * Specific variables to be rendered in element templates.
     * @var array
     */
    protected array $variables = [];

    /**
     * Forces the element to be rendered with the template for a given type.
     * @var string
     */
    protected string $renderWithType;
    private string $description;
    private Element $parent;
    protected static $sharedFormData = [];
    
    private Templates $templates;

    public function __toString()
    {
        $type = $this->renderWithType ?? \ntentan\utils\Text::deCamelize($this->getType());
        return $this->templates->render("wyf_forms_{$type}.tpl.php", $this->getTemplateVariables());
    }

    public function getType()
    {
        $class = new \ReflectionClass($this);
        $array = explode('\\', $class->getName());
        return end($array);
    }

    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }

    public function getAttribute($attribute)
    {
        return $this->attributes[$attribute] ?? null;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setLabel($label = false)
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setDescription($description = false)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    private function renderAttributes()
    {
        return $this->templates->render('wyf_forms_attributes', ['attributes' => $this->attributes]);
    }

    public function getTemplateVariables()
    {
        $additions = ['attributes' => $this->renderAttributes()];
        if (isset($this->label)) {
            $additions['label'] = $this->label;
        }
        return $additions;
    }

    protected function set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function setTemplateEngine(Templates $templates): void
    {
        $this->templates = $templates;
    }
    
    public function getTemplateEngine(): Templates
    {
        return $this->templates;
    }

    public function setParent(Element $parent)
    {
        $this->parent = $parent;
    }
}

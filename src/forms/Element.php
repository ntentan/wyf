<?php

namespace ntentan\wyf\forms;

use ntentan\utils\Text;
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

    /**
     * A description of the form element.
     * @var string
     */
    protected string $description;
    protected Element $parent;
    protected string $classes = [];
    protected static $sharedFormData = [];
    
    private Templates $templates;
    
    public function __construct(Templates $templates)
    {
        $this->templates = $templates;
    }

    public function __toString()
    {
        $type = $this->renderWithType ?? \ntentan\utils\Text::deCamelize($this->getType());
        return $this->templates->render("wyf_inputs_forms_{$type}.tpl.php", $this->getTemplateVariables());
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
        return $this->render('wyf_inputs_forms_attributes', ['attributes' => $this->attributes]);
    }

    public function getTemplateVariables()
    {
        return $this->variables + [
            'label' => $this->label,
            'attributes' => $this->renderAttributes(),
            'extra_css_classes' => implode(' ', $this->classes) . (count($this->getErrors()) > 0 ? ' form-error' : '')
        ];
    }

    protected function set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public static function create()
    {
        $args = func_get_args();
        $type = array_shift($args);
        $typeClass = new \ReflectionClass(
            'ntentan\\wyf\\utilities\\forms\\' .
            Text::ucamelize($type)
        );
        return $typeClass->newInstanceArgs($args);
    }

    public static function setSharedFormData($key, $value)
    {
        self::$sharedFormData[$key] = $value;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function addCssClass($class)
    {
        $this->classes[] = $class;
        return $this;
    }

}

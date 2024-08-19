<?php

namespace ntentan\wyf\forms;

use ntentan\honam\Templates;

/**
 * Base class for all form elements.
 */
class Element
{

    /**
     * A label to be rendered along with this element.
     */
    protected string $label;

    /**
     * An associative key-value array of attributes for the element.
     */
    protected array $attributes = [];

    /**
     * An associative key-value array of errors.
     */
    private array $errors = [];

    /**
     * Specific variables to be rendered in element templates.
     */
    protected array $variables = [];

    /**
     * Forces the element to be rendered with the template for a given type.
     * @var string
     */
    protected string $renderWithType;
    private string|bool $description = false;
    private ?Element $parent = null;
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

    public function setAttribute(string $attribute, string $value): Element
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }

    public function getAttribute(string $attribute)
    {
        return $this->attributes[$attribute] ?? null;
    }

    public function setErrors(array $errors): Element
    {
        $this->errors = $errors;
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setLabel($label = false): Element
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setDescription($description = false): Element
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTemplateVariables()
    {
        $additions = [
            'attributes' => $this->templates->render('wyf_forms_attributes', ['attributes' => $this->attributes]),
            'errors' => $this->errors
        ];
        if (isset($this->label)) {
            $additions['label'] = $this->label;
        }
        return array_merge($this->variables, $additions);
    }

    protected function set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function setTemplateEngine(Templates $templates): Element
    {
        $this->templates = $templates;
        return $this;
    }
    
    public function getTemplateEngine(): Templates
    {
        return $this->templates;
    }

    public function setParent(Element $parent): Element
    {
        $this->parent = $parent;
        return $this;
    }
    
    public function getParent(): ?Element
    {
        return $this->parent;
    }
}

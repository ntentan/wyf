<?php
namespace ntentan\wyf\forms;

use ntentan\honam\Templates;
use ntentan\wyf\WyfException;

/**
 * A utility class for creating form elements.
 */
class f
{
    private static Templates $templates;

    public static function init(Templates $templates)
    {
        self::$templates = $templates;
    }

    public static function create(string $element, mixed ...$args): Element
    {
        $class = new \ReflectionClass(match ($element) {
            'form' => Form::class,
            'text' => TextField::class,
            'number' => TextField::class,
            'textarea' => Textarea::class,
            'submit_button' => SubmitButton::class,
            'hidden' => HiddenField::class,
            'date' => DateField::class,
            'checkbox' => Checkbox::class,
            'model' => ModelField::class,
            'file' => UploadField::class,
            default => throw new WyfException("Unknown form item type $element")
        });

        if ($element == "form") {
            array_unshift($args, f::create('submit_button', 'Save'));
        }

        /** @var Element $instance */
        $instance = $class->newInstanceArgs($args);
        $instance->setTemplateEngine(self::$templates);

        if ($element === 'number') {
            $instance->setAttribute("type", "number");
        }

        return $instance;
    }
}


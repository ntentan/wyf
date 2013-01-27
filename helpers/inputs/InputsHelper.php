<?php
namespace ntentan\plugins\wyf\helpers\inputs;

use ntentan\plugins\wyf\helpers\inputs\forms\Form;
use ntentan\views\template_engines\TemplateEngine;

class InputsHelper extends \ntentan\views\helpers\Helper
{
    public function __construct() 
    {
        TemplateEngine::appendPath(p('wyf/helpers/inputs/templates'));
        TemplateEngine::appendPath(p('wyf/helpers/inputs/templates/layouts'));
    }
    
    public function help($params = null)
    {        
        $form = new Form();
        return $form;
    }
}

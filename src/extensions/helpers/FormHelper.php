<?php
namespace ntentan\extensions\wyf\helpers;

use ntentan\extensions\wyf\helpers\forms\Form;
use ntentan\honam\TemplateEngine;

class FormHelper extends \ntentan\honam\Helper
{
    public function __construct() 
    {
        TemplateEngine::appendPath(realpath(__DIR__ . '/../../../views/forms'));
    }
    
    public function help($params = null)
    {        
        $form = new Form();
        return $form;
    }
    
    /**
     * 
     * @param \ntentan\Model $model
     */
    public function forModel($model)
    {
        $form = new Form();
        $description = $model->getDescription();
        $fields = $description->getFields();
        $autoPrimaryKey = $description->getAutoPrimaryKey();
        $primaryKeys = $description->getPrimaryKey();
        
        foreach($fields as $field) {
            if($autoPrimaryKey && array_search($field['name'], $primaryKeys) !== false) continue;
            $form->add($this->inputForField($field));
        }
        
        return $form;
    }
    
    public function inputForField($field)
    {
        $input = null;
        switch($field['type']) {
            case 'string':
            case 'integer':
                $input = new forms\Text($field['name']);
                break;
        }
        return $input;
    }
}

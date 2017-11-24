<?php
namespace ntentan\wyf\controllers\crud;

use ntentan\Context;
use ntentan\exceptions\NtentanException;
use ntentan\interfaces\RenderableInterface;
use ntentan\interfaces\ThemableInterface;
use ntentan\Model;
use ntentan\utils\Text;
use ntentan\View;

class ListViewDecorator implements ThemableInterface, RenderableInterface
{
    private $view;
    private $package;
    private $entities;
    private $operations;
    private $controllerUrl;
    private $listFields;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function setup($package, $entities, $controllerUrl)
    {
        $this->package = $package;
        $this->entities = $entities;
        $this->controllerUrl = $controllerUrl;
        $context = Context::getInstance();
        $this->view->set([
            'add_item_url' => "{$this->controllerUrl}/add",
            'import_items_url' => "{$this->controllerUrl}/import",
            'public_path' => $context->getUrl('public'),
            'add_item_label' => $this->addItemLabel ?? "Add new " . Text::singularize($this->entities)
        ]);
    }

    /**
     * Add a custom operation to the CRUD list.
     *
     * @param $action
     * @param string $label
     */
    public function addOperation($action, $label = null)
    {
        $this->operations[] = [
            'label' => $label == null ? $action : $label,
            'action' => $action
        ];
    }

    private function decodeArrayFieldInfo($fieldInfo, $field, &$listField, &$columnHeader)
    {
        if(!isset($fieldInfo[0])) {
            throw new NtentanException("Invalid array field format presented");
        }
        $parts = explode('.', $fieldInfo[0]);
        $relatedFieldName = array_pop($parts);
        $relatedFieldModel = implode('.', $parts);
        if(isset($relatedFields[$relatedFieldModel])) {
            $relatedFields[$relatedFieldModel][] = $relatedFieldName;
        } else {
            $relatedFields[$relatedFieldModel] = [$relatedFieldName];
        }
        $listField = $fieldInfo[0];
        $columnHeader = $fieldInfo['label'] ?? $field;
        return ['list_field' => $listField, 'column_header' => $columnHeader];
    }

    private function decodeFieldInfo($field, $fieldInfo, &$relatedFields)
    {
        if(is_string($fieldInfo)) {
            return ['list_field' => $field, 'column_header' => $fieldInfo];
        } else if (is_array($fieldInfo)) {
            return $this->decodeArrayFieldInfo($fieldInfo, $field, $listField, $columnHeader);
        }
    }

    private function prepareListFields()
    {
        $primaryKey = Model::load($this->package)->getDescription()->getPrimaryKey()[0];
        $fields = [$primaryKey];
        $columnHeaders = [];
        $relatedFields = [];
        $listFields = [];
        foreach ($this->listFields ?? ['__string' => ucwords($this->entities)] as $field => $fieldInfo) {
            if(is_numeric($field)) {
                $fields[] = $fieldInfo;
                $listFields[] = $fieldInfo;
                $columnHeaders[] = $fieldInfo;
            } else  {
                $fields[] = $field;
                $decodedFieldInfo = $this->decodeFieldInfo($field, $fieldInfo, $relatedFields);
                $listFields[] = $decodedFieldInfo['list_field'];
                $columnHeaders[] = $decodedFieldInfo['column_header'];
            }
        }
        return [
            'fields' => $fields, 'related_fields' => $relatedFields, 'list_fields' => $listFields,
            'column_headers' => $columnHeaders, 'primary_key' => $primaryKey
        ];
    }

    private function setupView()
    {
        $fieldDetails = $this->prepareListFields();
        $apiFields = implode(',', $fieldDetails['fields']);
        foreach($fieldDetails['related_fields'] as $model => $relatedField) {
            $apiFields .= "&fields:$model=" . implode(',', $relatedField);
        }
        if(!empty($relatedFields)) {
            $apiFields .= "&depth=1&expand_only=" . implode(',', array_keys($fieldDetails['related_fields']));
        }
        $this->view->set([
            'api_parameters' => "?fields=$apiFields",
            'list_fields' => $fieldDetails['list_fields'],
            'column_headers' => $fieldDetails['column_headers'],
            'operations' => $this->operations,
            'primary_key_field' => $fieldDetails['primary_key'],
            'foreign_key' => false
        ]);
    }

    public function setFields($listFields)
    {
        $this->listFields = $listFields;
    }

    public function getTemplate()
    {
        return $this->view->getTemplate();
    }

    public function setTemplate($template)
    {
        $this->view->setTemplate($template);
    }

    public function getLayout()
    {
        return $this->view->getLayout();
    }

    public function setLayout($layout)
    {
        $this->view->setLayout($layout);
    }

    public function __toString()
    {
        $this->setupView();
        return $this->view->__toString();
    }

    public function set($params1, $params2 = null)
    {
        $this->view->set($params1, $params2);
    }
}

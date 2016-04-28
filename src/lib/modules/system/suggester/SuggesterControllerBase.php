<?php
namespace ntentan\plugins\wyf\lib\modules\system\suggester;

use ntentan\plugins\wyf\lib\WyfController;
use ntentan\models\Model;

class SuggesterControllerBase extends WyfController
{
    public function suggest($modelName)
    {
        $this->view->template = false;
        $this->view->layout = false;
        
        $model = Model::load($modelName);
        $conditions = array();
        $fields = array();
        
        foreach(explode("/", $_GET['search_fields']) as $searchField)
        {
            $conditions[] = sprintf(
                "LOWER(%s) LIKE '%s%%'", 
                $model->dataStore->escape($searchField), 
                $model->dataStore->escape(strtolower($_GET['s']))
            );
        }
        
        foreach(explode("/", $_GET['fields']) as $field)
        {
            $fields[] = $model->dataStore->escape($field);
        }
        
        $response = $model->dataStore->query(
            sprintf(
                "SELECT %s FROM %s WHERE %s LIMIT 10",
                implode(",", $fields),
                $model->dataStore->table,
                implode(" OR ", $conditions)
            )
        );
        
        echo json_encode($response);
    }
}

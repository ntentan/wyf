<?php
namespace ntentan\wyf\controllers\api;

use ntentan\Model;
use ntentan\nibii\QueryParameters;
use ntentan\utils\Input;
use ntentan\wyf\interfaces\ApiRequestHandlerInterface;

class GetRequestHandler implements ApiRequestHandlerInterface
{
    private $outputFilterActive = false;
    private $outputFields = [];

    private function getItem($model, $view, $id)
    {
        $primaryKey = $model->getDescription()->getPrimaryKey()[0];
        $item = $model->fetchFirst([$primaryKey => $id]);
        if ($item->count() == 0) {
            $view->set('response', ['message' => 'item not found']);
            http_response_code(404);
        } else {
            if (Input::server('CONTENT_TYPE') == "text/plain") {
                header("Content-Type: text/plain");
                return (string)$item;
            } else {
                return $item;
            }
        }
    }

    private function getFields(string $key, string $model = '__default')
    {
        $queryFields = [];
        if(Input::exists(Input::GET, $key)) {
            $this->outputFields[$model] = explode(',', Input::get($key));
            foreach($this->outputFields[$model] as $field) {
                if(substr($field, 0, 2) == '__') {
                    $this->outputFilterActive = true;
                    return [];
                } else {
                    $queryFields[] = $field;
                }
            }
            return $queryFields;
        }
        return [];
    }

    private function search(Model $model, string $query) : Model
    {
        $description = $model->getDescription();
        $fields = $description->getFields();
        $textFields = [];
        $or = '';
        $query = "%" . strtolower($query) . "%";
        $searchFields = explode(',', Input::get('search_fields'));
        $modelQuery = new QueryParameters($model->getTable());
        $modelQuery->setFields($this->getFields('fields'));

        $partialFilter = '';
        foreach ($fields as $field) {
            if ($field['type'] == 'string' && (in_array($field['name'], $searchFields) || !Input::exists(Input::GET, 'search_fields'))) {
                $textFields[] = $field;
                $partialFilter .= " $or LOWER({$field['name']}) LIKE :query";
                $or = 'OR';
            }
        }

        $filter = '';
        $bindData = [];
        $terms = explode(' ', $query);
        foreach($terms as $i => $term) {
            $filter .= str_replace(":query", ":query_$i", $partialFilter);
            $bindData["query_$i"] = $term;
        }

        $modelQuery->setFilter($filter, $bindData);
        header("X-Item-Count: {$model->count($modelQuery)}");
        $modelQuery->setLimit(Input::get('limit'));
        $modelQuery->setOffset((Input::get('page') - 1) * Input::get('limit'));
        return $model->fetch($modelQuery);
    }

    private function getExpandableModels()
    {
        return Input::exists(Input::GET, 'expand_only') && Input::get('expand_only') != "" ?
            explode(',', Input::get('expand_only')) : [];
    }

    private function formatRecord($fields, $record)
    {
        $outputRecord = [];
        foreach ($fields as $field) {
            if($field == '__string') {
                $outputRecord[$field] = (string)$record;
            } else if($field == '__count') {
                $outputRecord[$field] = $record->count();
            } else {
                $outputRecord[$field] = $record[$field];
            }
        }
        return $outputRecord;
    }

    private function attachRelatedData(&$outputRecord, $depth, $record, $relationships, $expandableModels)
    {
        if($depth > 0) {
            $expandedModels = empty($expandableModels) ? $relationships : $expandableModels;
            foreach($expandedModels as $relationship) {
                $outputRecord[$relationship] = $this->prepareOutput($record[$relationship], $depth - 1, $relationship);
            }
        }
    }

    private function prepareOutput(Model $input, $depth = 0, string $model = '__default')
    {
        $expandableModels = $this->getExpandableModels();
        if($this->outputFilterActive && isset($this->outputFields[$model])) {
            $relationships = array_keys($input->getDescription()->getRelationships());
            $output = [];
            foreach($input as $key => $record) {
                if(is_numeric($key)) {
                    $outputRecord = $this->formatRecord($this->outputFields[$model], $record);
                    $this->attachRelatedData($outputRecord, $depth, $record, $relationships, $expandableModels);
                    $output[]=$outputRecord;
                } else {
                    $outputRecord = $this->formatRecord($this->outputFields[$model], $input);
                    $this->attachRelatedData($outputRecord, $depth, $input, $relationships, $expandableModels);
                    return $outputRecord;
                }
            }
            return $output;
        } else {
            return $input->toArray($depth, $expandableModels);
        }
    }

    public function process($path)
    {
        $model = $path['model'];
        $input = Input::get();

        foreach ($input as $key => $value) {
            if (preg_match("/^fields:(?<model>[0-9a-z_.]+)/", $key, $matches)) {
                $model->with($matches['model'])->setFields($this->getFields($key, $matches['model']));
            } else if (preg_match("/^fields/", $key, $matches)) {
                $model->fields($this->getFields('fields'));
            }
        }

        if ($path['id']) {
            $this->getItem($model, $path['id']);
        } else if (Input::exists(Input::GET, 'q')) {
            $responseData = $this->search($model, Input::get('q'));
        } else {
            $model->limit(Input::get('limit'));
            $model->offset((Input::get('page') - 1) * Input::get('limit'));
            $model->sortBy(Input::get('sort'), 'DESC');
            header("X-Item-Count: " . $model->count());
            $responseData = $model->fetch();
        }

        return $this->prepareOutput($responseData, Input::get('depth'));
    }
}

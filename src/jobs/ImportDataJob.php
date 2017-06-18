<?php
namespace ntentan\wyf\jobs;

use ajumamoro\Job;

class ImportDataJob extends Job
{
    private $dataFile;
    private $model;
    private $importFields;
    
    public function __construct($dataFile, $model, $importFields) {
        $this->dataFile = $dataFile;
        $this->model = (new \ReflectionClass($model))->getName();
        $this->importFields = $importFields;
    }
    
    private function mapHeadersAndFields($mapFrom, $fromLabels = true) {
        $mappedTo = [];
        foreach($this->importFields as $field => $label) {
            if(is_numeric($field)) {
                $field = $label;
                $label = ucwords(str_replace('_', ' ', $label));
            }
            $index = array_search($fromLabels ? $label : $field, $mapFrom);
            if($index !== false) {
                $mappedTo[$index] = $fromLabels ? $field : $label;
            }
        }
        return $mappedTo;
    }
    
    private function isLineEmpty($line) {
        foreach($line as $value) {
            if($value != "") return false;
        }
        return true;
    }
    
    public function go() {
        $lineNumber = 1;
        $errors = [];
        $model = $this->getContainer()->resolve($this->model);
        $file = fopen($this->dataFile, 'r');
        $fields = $this->mapHeadersAndFields(fgetcsv($file));
        $driver = $model->getAdapter()->getDriver();
        
        $driver->beginTransaction();
        $failed = false;
        
        while(!feof($file)) {
            $line = fgetcsv($file);
            if(!is_array($line) || $this->isLineEmpty($line)) {
                continue;
            }
            $record = [];
            foreach($fields as $i => $field) {
                $record[$field] = $line[$i] === "" ? null : $line[$i];
            }
            $model->setData($record);
            $validity = $model->validate();
            if($validity === true) {
                if(!$failed) {
                    $model->save();
                }
            } else {
                $erroredFields = array_keys($validity);
                $erroredHeaders = $this->mapHeadersAndFields($erroredFields, false);
                $remappedValidity = [];
                foreach($erroredFields as $i => $erroredField) {
                    $remappedValidity[$erroredHeaders[$i]] = $validity[$erroredField];
                }
                $errors[] = [
                    'line' => $lineNumber,
                    'errors' => $remappedValidity
                ];
                $failed = true;
                $driver->beginTransaction();
            }
            $lineNumber++;
        }
        
        if(!$failed) {
            $driver->commit();
        } 
        
        return json_encode($errors);
    }

}

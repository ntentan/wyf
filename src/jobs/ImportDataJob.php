<?php

namespace ntentan\wyf\jobs;

use ajumamoro\Job;
use ntentan\utils\Text;
use ntentan\utils\Filesystem;
use ntentan\Model;
use ntentan\wyf\ImportDataJobInterface;

class ImportDataJob extends Job implements ImportDataJobInterface
{
    private $dataFile;
    private $model;
    private $importFields;

    public function setParameters($dataFile, $model, $importFields)
    {
        $this->dataFile = $dataFile;
        $this->model = (new \ReflectionClass($model))->getName();
        $this->importFields = $importFields;
    }

    private function mapHeadersAndFields($headers, $relationships)
    {
        $mapping = ['headers' => [], 'fields' => []];
        foreach ($this->importFields as $label => $field) {
            $details = [];
            if (is_numeric($label) && is_string($field)) {
                $label = ucwords(str_replace('_', ' ', $field));
            }
            $fieldName = $field;
            if (is_array($field) && in_array($field[0], array_keys($relationships))) {
                $relationship = $relationships[$field[0]];
                $relationship->runSetup();
                $details['related'] = true;
                $details['field'] = $field[1];
                $details['model'] = $relationship->getModelInstance();
                $details['foreign_key'] = $relationship->getOptions()['foreign_key'];
                $fieldName = $relationship->getOptions()['local_key'];

                if (is_numeric($label)) {
                    $label = ucwords(
                        str_replace('_', ' ', Text::singularize(Text::deCamelize($details['model']->getName())))
                    );
                }
            }
            $index = array_search($label, $headers);
            if ($index !== false) {
                $details['name'] = $fieldName;
                $mapping['headers'][$index] = $details;
            }
        }
        return $mapping;
    }

    private function isLineEmpty($line)
    {
        foreach ($line as $value) {
            if ($value != "") return false;
        }
        return true;
    }

    public function go()
    {
        $lineNumber = 2;
        $response = ['errors' => [], 'count' => 0];
        Filesystem::checkReadable($this->dataFile);
        $file = fopen($this->dataFile, 'r');
        $modelClass = $this->model;
        $model = new $modelClass();
        $relationships = $model->getDescription()->getRelationships();
        $headers = fgetcsv($file);
        $mapping = $this->mapHeadersAndFields($headers, $relationships);
        $driver = $model->getAdapter()->getDriver();

        $driver->beginTransaction();
        $failed = false;

        while (!feof($file)) {
            // Read a line from CSV
            $line = fgetcsv($file);
            if (!is_array($line) || $this->isLineEmpty($line)) {
                continue;
            }
            // Build up the record and assign to model
            $record = [];
            foreach ($mapping['headers'] as $i => $field) {
                if ($field['related'] ?? false) {
                    $value = $field['model']->fields($field['foreign_key'])->fetchFirst([$field['field'] => $line[$i]]);
                    $record[$field['name']] = $value[$field['foreign_key']];
                    if ($value->count() == 0 && $line[$i] !== "") {
                        $failed = true;
                        $response['errors'][] = [
                            'line' => $lineNumber,
                            'errors' => [$headers[$i] => ["There is no {$headers[$i]} with {$field['field']} {$line[$i]}"]]
                        ];
                    }
                } else {
                    $record[$field['name']] = $line[$i] === "" ? null : $line[$i];
                }
            }

            $model->setData($record);
            $validity = $model->validate();
            if ($validity === true) {
                if (!$failed) {
                    $model->save();
                    $response['count']++;
                }
            } else {
                $erroredFields = array_keys($validity);
                $erroredHeaders = $this->mapHeadersAndFields($erroredFields, $relationships, false);
                $remappedValidity = [];
                foreach ($erroredFields as $i => $erroredField) {
                    $remappedValidity[$erroredHeaders[$i]] = $validity[$erroredField];
                }
                $response['errors'][] = [
                    'line' => $lineNumber,
                    'errors' => $remappedValidity
                ];
                $failed = true;
                $driver->beginTransaction();
            }
            $lineNumber++;
        }

        if (!$failed) {
            $driver->commit();
        }

        return json_encode($response);
    }

}

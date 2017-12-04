<?php

namespace ntentan\wyf\jobs;

use ajumamoro\Job;
use ntentan\Model;
use ntentan\utils\Text;
use ntentan\utils\Filesystem;
use ntentan\wyf\interfaces\ImportDataJobInterface;

/**
 * An ajumamoro job used for importing data into WYF models.
 * This job is responsible for performing the background tasks associated with the import operation. The import
 * operation reads records from CSV files, validates them and saves them into the database. It also reports errors
 * in cases where fields do not match or model fails to validate the data.
 *
 * @package ntentan\wyf\jobs
 * @author Ekow Abaka
 */
class ImportDataJob extends Job implements ImportDataJobInterface
{
    /**
     * A path to the data file.
     * @var string
     */
    private $dataFile;

    /**
     * An instance of the model used for validating and saving data.
     * @var Model
     */
    private $model;

    /**
     * A mapping of fields between the file and the model.
     * @var array
     */
    private $importFields;

    /**
     * Set the parameters required for the job's execution.
     *
     * @param string $dataFile A path to the datafile.
     * @param Model|string $model The name of the model for the data.
     * @param array $importFields An array of fields to use for the import.
     */
    public function setParameters(string $dataFile, Model $model, array $importFields)
    {
        $this->dataFile = $dataFile;
        $this->model = (new \ReflectionClass($model))->getName();
        $this->importFields = $importFields;
    }

    /**
     * Try to map the fields in the model to the columns in the file.
     * With the help of the importFields property, this method tries to determine the correct model field to assign
     * each column in the datafile. This method makes it possible to place the columns in the spreadsheet in any
     * arbitrary order. Apart from fields in the models, this method also performs mappings into related models.
     *
     * @param array $headers Headers read from the file
     * @param array $relationships Related models
     * @return array
     */
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

    /**
     * Checks if a supplied line is empty.
     * An empty line could be either an empty string or a row with empty cells.
     * @param string $line
     * @return bool
     */
    private function isLineEmpty($line)
    {
        foreach ($line as $value) {
            if ($value != "") return false;
        }
        return true;
    }

    /**
     * Perform the actual import.
     * @return string The response for the job caller.
     * @throws \ntentan\utils\exceptions\FileNotReadableException
     */
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
            // Build up the record using field mappings and assign to model
            $record = [];
            foreach ($mapping['headers'] as $i => $field) {
                // If we have a related field try to retrieve the related record.
                // @todo should be put in own method
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

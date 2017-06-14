<?php
namespace ntentan\wyf\jobs;

use ajumamoro\Job;

class ImportDataJob extends Job
{
    private $dataFile;
    private $model;
    
    public function __construct($dataFile, $model) {
        $this->dataFile = $dataFile;
        $this->model = (new \ReflectionClass($model))->getName();
    }
    
    public function go() {
        $model = $this->getContainer()->resolve($this->model);
    }

}

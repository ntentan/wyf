<?php

namespace ntentan\wyf\interfaces;


use ntentan\Model;

interface ImportDataJobInterface
{
    public function setParameters(string $dataFile, Model $model, array $importFields);
}
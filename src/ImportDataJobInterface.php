<?php
/**
 * Created by PhpStorm.
 * User: ekow
 * Date: 10/15/17
 * Time: 10:49 PM
 */

namespace ntentan\wyf;


interface ImportDataJobInterface
{
    public function setParameters($dataFile, $model, $importFields);
}
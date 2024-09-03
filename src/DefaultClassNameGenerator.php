<?php

namespace ntentan\wyf;

use ntentan\utils\Text;
use ntentan\wyf\WyfClassNameGenerator;

class DefaultClassNameGenerator implements WyfClassNameGenerator
{
    public function getClassName(string $path): string
    {
        return Text::ucamelize(end(explode("\\", $path)));
    }
}
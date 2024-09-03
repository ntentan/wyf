<?php

namespace ntentan\wyf;

interface WyfClassNameGenerator
{
    function getClassName(string $path): string;
}
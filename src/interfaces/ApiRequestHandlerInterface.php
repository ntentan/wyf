<?php

namespace ntentan\wyf\interfaces;


interface ApiRequestHandlerInterface
{
    public function process($path);
}
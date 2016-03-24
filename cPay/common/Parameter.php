<?php

namespace cPay\Common;

class Parameter
{
    public function __construct($config = [])
    {
        $this->init();
    }

    public function init() { }

    public function hasProperty($name, $checkVars = true)
    {
        // TO-DO
    }

    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}

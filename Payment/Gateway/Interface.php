<?php
namespace Payment\Gateway;

interface Interface
{
    public function getName();
    public function getShortName();
    public function getDefaultParameters();
    public function initialize(array $parameters = array());
    public function getParameters();
}
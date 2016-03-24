<?php

namespace cPay\Gateway;

interface GatewayInterface
{
    public function getName();
    public function getDefaultParameters();
    public function install(array $parameters = array());
    public function getParameters();
}

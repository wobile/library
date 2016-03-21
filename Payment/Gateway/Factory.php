<?php
namespace Payment\Gateway;

use Payment\Gateway\Exception\RuntimeException;

class Factory
{
    private $gateways = array();

    public function all()
    {
        return $this->gateways;
    }

    public function replace($gateways = array())
    {
        if(isset($gateways) && is_array($gateways) && count($gateways) > 0) { 
            $this->gateways = $gateways;
        }
    }

    public function register($className = NULL)
    {
        if (isset($className) && is_string($className) && !in_array($className, $this->gateways)) {
            $this->gateways[] = $className;
        } else { 
            throw new \RuntimeException('Class name required.');
        }
    }

    public function find()
    {
        foreach ($this->getSupportedGateways() as $gateway) {
            $class = Helper::getGatewayClassName($gateway);
            if (class_exists($class)) {
                $this->register($gateway);
            }
        }
        ksort($this->gateways);
        return $this->all();
    }

    public function gate($class)
    {
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return new $class($httpClient, $httpRequest);
    }

    public function getSupportedPayments()
    {
        // TO-DO : Supported Payments 
    }
}

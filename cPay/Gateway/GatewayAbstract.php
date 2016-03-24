<?php
namespace cPay\Gateway;

abstract class GatewayAbstract implements GatewayInterface
{
    protected $parameters;

    public function __construct()
    {
        $this->parameters = new cPay\Common\Parameter();
        $this->install();
    }

    public function getName() { 
        return '';
    }

    public function install(array $parameters = array()) {
        $this->parameters = new Parameter;
        return $this;
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($key)
    {
        return (isset($key) && is_string($key) && !empty($key) && !is_null($key) && isset($this->parameters[$key]) ? $this->parameters[$key] : false);
    }

    public function setParameter($key, $value)
    {
        if(isset($key) && !empty($key) && !is_null($key) && isset($value) && !empty($value) && !is_null($value)) { 
            $this->parameters[$key] = $value;
        }
        return $this;
    }

    public function setParameters($params) { 
        $parameters = $this->getDefaultParameters();
        if(isset($params) && is_array($params) && count($params)) { 
            $parameters = array_merge_recursive($params,$parameters);
        }
        foreach ($parameters as $key => $value) {
            if(isset($key) && !empty($key) && !is_null($key) && isset($value) && !empty($value) && !is_null($value)) { 
                if(method_exists($this, 'set'. ucfirst($key))) { 
                    $methodName = 'set'. ucfirst($key);
                    $this->$methodName($value);
                    return $this;
                } else { 
                    $this->parameters[$key] = is_array($value) ? reset($value) : $value;
                }
            }
        }
    }

    public function getMode()
    {
        return $this->getParameter('mode');
    }

    public function setMode($value)
    {
        return $this->setParameter('mode', $value);
    }

    public function getCurrency()
    {
        return strtoupper($this->getParameter('currency'));
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getHelp() { 
        return $this->supportedMethods();
    }

    public function supportedMethods()
    {
        $methods = [
            'auth' => method_exists($this,'auth'),
            'capture' => method_exists($this,'capture'),
            'purchase' => method_exists($this,'purchase'),
            'refund' => method_exists($this,'refund'),
            'void' => method_exists($this,'void'),
            'completeAuth' => method_exists($this,'completeAuth'),
            'completePurchase' => method_exists($this,'completePurchase'),
            'acceptNotification' => method_exists($this,'acceptNotification'),
            'createCard' => method_exists($this,'createCard'),
            'deleteCard' => method_exists($this,'deleteCard'),
            'updateCard' => method_exists($this,'updateCard')
        ];
        $supported = [];
        foreach ($methods as $key => $value) {
            if(true === $value) { 
                $supported[$key];
            }
        }
        return $supported;
    }

    public function methodExists($name) { 
        return $name && !empty($name) && !is_null($name) && true === method_exists($this,$name) ? true : false;
    }
}

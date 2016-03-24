<?php

class Paytrek extends cPay\Gateway\GatewayAbstract
{
	function __construct($params) { 
		$this->setParameters($params);
	}

    public function getDefaultParameters() {
        return array(
            'bank' => 'Denizbank',
        );
    }

	public function getName() {
        return 'Paytrek';
    }

}
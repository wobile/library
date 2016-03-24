<?php 

require_once 'Gateway/GatewayInterface.php';
require_once 'Gateway/GatewayAbstract.php';

class Gateway extends cPay\Gateway\GatewayAbstract
{

    public function getDefaultParameters() {
        return array(
            'testMode' => true
        );
    }

	public function getName() {
        return '';
    }

}
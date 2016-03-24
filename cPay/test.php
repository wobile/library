<?php 

include('cPay.php');

echo '<pre>';

$cPay = cPay::Gateway('Paytrek');

print_r($cPay);

print_r($cPay->supportedMethods());

echo '</pre>';
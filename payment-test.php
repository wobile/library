<?php 

require_once 'Payment/Payment.php';

// Payment Register 
echo '<pre> # Register<br>';
$gateways = Payment::register('Garanti');
print_r($gateways);
echo '</pre>';

// Payment Gateways 
echo '<pre> # All<br>';
$gateways = Payment::all();
print_r($gateways);
echo '</pre>';

// Payment Gateway Replace
// echo '<pre> # Replace <br>';
// $payment = Payment::replace(array('YKB'));
// print_r($payment);
// echo '</pre>';

// Payment Gateways Find
echo '<pre> # Find<br>';
$gateways = Payment::find();
print_r($gateways);
echo '</pre>';

echo '<pre>';
$payment = Payment::getFactory();
print_r($payment);
echo '</pre>';

echo '<pre>';
$payment = Payment::setFactory();
print_r($payment);
echo '</pre>';
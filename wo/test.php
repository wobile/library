<?php 

include('Wo.php');

// Supported List
$supported = Wo::getSupported();
print_r($supported);

print_r(Wo::Component('Payment'));
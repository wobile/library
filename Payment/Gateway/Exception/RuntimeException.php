<?php

namespace Payment\Gateway\Exception;

/**
 * Runtime Exception
 */
class RuntimeException extends \RuntimeException implements PaymentException
{
    public function __construct($message = "Invalid response from payment gateway", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

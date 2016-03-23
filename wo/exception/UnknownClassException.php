<?php

namespace Wo\Exception;

class UnknownClassException extends Exception
{
    public function getName() { return 'Unknown Class'; }
}
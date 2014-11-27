<?php

class Cola_Exception_Dispatch extends Cola_Exception
{

    public function __construct($message, array $variables = NULL, $code = 0)
    {
        parent::__construct($message, $variables, $code );
    }

}
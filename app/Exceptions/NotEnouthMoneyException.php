<?php

namespace App\Exceptions;

use Exception;

class NotEnouthMoneyException extends Exception
{
    private array $messages = array();

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function render()
    {
        return response()->json($this->messages);
    }

}

<?php

namespace App\Exceptions;

use Exception;

class NotEnouthSpaceException extends Exception
{
    // 基底クラスとの名前の衝突に注意
    private array $messages = array();

    public function __construct(string $messages)
    {
        //　keyをこっちで指定してしまうpattern
        $this->messages = ["result" => $messages];
    }


    public function render()
    {
        return response()->json($this->messages);
    }
}

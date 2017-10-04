<?php

/**
 * Created by PhpStorm.
 * User: federicogarateguy
 * Date: 16/6/17
 * Time: 22:55
 */
class ApiError implements JsonSerializable
{
    private $error_code,$message;

    /**
     * ApiResponse constructor.
     * @param $error_code
     * @param $message
     */
    public function __construct($error_code, $message)
    {
        $this->error_code = $error_code;
        $this->message = $message;
    }

    function jsonSerialize()
    {
        return "Error: {$this->error_code} | message: {$this->message}";
    }


}
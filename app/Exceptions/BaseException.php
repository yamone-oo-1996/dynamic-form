<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ResponseHelper;

class BaseException extends Exception
{
    protected $responseHelper = null;

    public function __construct($message)
    {
        $this->responseHelper = new ResponseHelper();
        parent::__construct($message);
    }

    public function setResponseHandler(ResponseHelper $handler)
    {
        $this->responseHelper = $handler;
    }
}

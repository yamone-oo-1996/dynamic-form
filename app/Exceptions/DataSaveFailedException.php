<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Log;

class DataSaveFailedException extends BaseException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function getResponse()
    {
        Log::critical("Data save failed exception occur. Message >>> " . $this->getMessage());
        return $this->responseHelper?->failed($this->getMessage(), Response::HTTP_NOT_ACCEPTABLE);
    }
}

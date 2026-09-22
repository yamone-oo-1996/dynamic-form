<?php

namespace App\Contracts\Repositories;

interface ServiceDataRepository
{
    public function getByRef($refId, $serviceRefId, $serviceType);
    public function createServiceData($refId, $serviceRefId, $serviceType, $data);
}

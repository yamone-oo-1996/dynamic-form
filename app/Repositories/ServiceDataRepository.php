<?php

namespace App\Repositories;

use App\Contracts\Repositories\ServiceDataRepository as ServiceDataRepositoryContract;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\DataSaveFailedException;
use App\Models\ServiceData;

class ServiceDataRepository implements ServiceDataRepositoryContract
{
    public function __construct(
        private ServiceData $serviceData,
    ) {
    }

    public function getByRef($refId, $serviceRefId, $serviceType)
    {
        $result = $this->serviceData->where('reference_id', $refId)
            ->where('service_reference_id', $serviceRefId)
            ->where('service_type', $serviceType)
            ->first();
        if (empty($result)) {
            throw new DataNotFoundException("Service data not found!");
        }
        return $result;
    }

    public function createServiceData($refId, $serviceRefId, $serviceType, $data)
    {
        $result = $this->serviceData->create([
            'reference_id' => $refId,
            'service_reference_id' => $serviceRefId,
            'service_type' => $serviceType,
            'data' => json_encode($data)
        ]);
        if (empty($result)) {
            throw new DataSaveFailedException("Service data creation failed!");
        }
        return $result;
    }
}

<?php

namespace App\Services;

use App\Contracts\Repositories\ServiceDataRepository;
use App\Contracts\Services\SalesOrderService as SalesOrderServiceContract;
use App\Enums\ServiceDataType;
use App\Exceptions\DataNotFoundException;
use Exception;

class SalesOrderService implements SalesOrderServiceContract
{
    public function __construct(
        private ServiceDataRepository $serviceDataRepository
    ) {
    }

    public function getSalesOrderById($refId, $refType)
    {
        try {
            # Get Service Ref Id from refType
            $serviceRefId = $this->getServiceRefId($refType);

            $serviceData = $this->serviceDataRepository->getByRef($refId, $serviceRefId, ServiceDataType::SalesOrder);
            return json_decode($serviceData->data ?? null);
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
    }

    public function createSalesOrder($refId, $serviceRefId, $data)
    {
        try {
            $serviceData = $this->serviceDataRepository->createServiceData($refId, $serviceRefId, ServiceDataType::SalesOrder, $data);
            return $serviceData;
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
    }

    protected function getServiceRefId($refType)
    {
        $constantName = "App\\Enums\\ServiceReference::{$refType}";
        if (!defined($constantName)) {
            throw new DataNotFoundException("Invalid reference type: {$refType}");
        }
        $serviceRefId = constant($constantName);
        return $serviceRefId;
    }
}

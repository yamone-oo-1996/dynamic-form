<?php

namespace App\Repositories;

use Exception;
use App\Enums\RecordStatus;
use App\Models\DataTransferTracking;
use App\Exceptions\DataAlreadyExistsException;
use App\Exceptions\DataSaveFailedException;
use App\Contracts\Repositories\DataTransferTrackingRepository as DataTransferTrackingRepositoryContract;
use App\Enums\ServiceReference;

class DataTransferTrackingRepository implements DataTransferTrackingRepositoryContract
{
    public function __construct(
        private DataTransferTracking $dataTransferTracking,
    ) {
    }

    protected $failedListFilter = [
        'id',
        'reference_id',
        'service_reference_id',
        'is_processed'
    ];

    public function create($data, $serviceId)
    {
        $result = [];
        try {
            $refId = $serviceId === ServiceReference::LAN_VOUCHER->value ? $data['voucher_id'] : $data['ref_id'];

            $isReferenceExists = $this->dataTransferTracking
                ->where('reference_id', $refId)
                ->exists();

            if ($isReferenceExists) {
                return null;
            }

            $preparedData = [
                'reference_id' => $refId,
                'service_reference_id' => $serviceId,
                'data' => json_encode($data),
            ];
            $result = $this->dataTransferTracking->updateOrCreate(
                [
                    'reference_id' => $refId,
                    'service_reference_id' => $serviceId,
                ],
                $preparedData
            );
        } catch (Exception $e) {
            throw new DataSaveFailedException("Create Data Transfer Tracking Failed! Message >>> " . $e->getMessage());
        }
        return $result;
    }

    public function getFailedListByServiceRef($systemRefId = null, $filterFields = true)
    {
        $result = [];
        try {
            $query = $this->dataTransferTracking->where('is_processed', RecordStatus::ACTIVE);
            if (!empty($systemRefId)) {
                $query->where('service_reference_id', $systemRefId);
            }

            if ($filterFields) {
                $query->select($this->failedListFilter);
            }
            $result = $query->get();

            if ($filterFields) {
                $result = $result->map(function ($item) {
                    $item->ref_type = ServiceReference::tryFrom((int) $item->service_reference_id)?->name;
                    return $item;
                });
            }
        } catch (Exception $e) {
            throw new DataSaveFailedException("Get Data Transfer Tracking Failed! Message >>> " . $e->getMessage());
        }
        return $result;
    }

    public function getByRef($refId, $systemRefId)
    {
        $result = [];
        try {
            $result = $this->dataTransferTracking->where('reference_id', $refId)
                ->where('service_reference_id', $systemRefId)
                ->first();
        } catch (Exception $e) {
            throw new DataSaveFailedException("Get Data Transfer Tracking Failed! Message >>> " . $e->getMessage());
        }
        return $result;
    }

    public function updateProcessStatus($id, $status)
    {
        $result = [];
        try {
            $result = $this->dataTransferTracking->where('id', $id)->update(['is_processed' => $status]);
        } catch (Exception $e) {
            throw new DataSaveFailedException("Update Data Transfer Process Status Failed! Message >>> " . $e->getMessage());
        }
        return $result;
    }
}

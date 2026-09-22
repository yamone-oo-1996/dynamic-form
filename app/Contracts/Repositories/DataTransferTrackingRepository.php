<?php

namespace App\Contracts\Repositories;

interface DataTransferTrackingRepository
{
    public function create($data, $serviceId);
    public function updateProcessStatus($id, $status);
    public function getFailedListByServiceRef($systemRefId);
    public function getByRef($refId, $systemRefId);
}

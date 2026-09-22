<?php

namespace App\Contracts\Services;

interface SalesOrderService
{
    public function getSalesOrderById($refId, $refType);
    public function createSalesOrder($refId, $serviceRefId, $data);
}

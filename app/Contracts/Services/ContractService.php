<?php

namespace App\Contracts\Services;

interface ContractService
{
    public function processDigitalContract($formTypeId, $data);
    public function getFailedContracts($refType);
    public function recreateContract($refId, $refType);
    public function recreateContractProcess($refId, $refType);
    public function updateContractProcess($refId, $refType);
}

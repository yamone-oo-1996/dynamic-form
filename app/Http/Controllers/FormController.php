<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Contracts\Services\FormService;
use App\Exceptions\DataNotFoundException;
use App\Contracts\Services\ContractService;

class FormController extends Controller
{
    public function __construct(
        private FormService $service,
        private ContractService $contractService,
        private ResponseHelper $responseHelper
    ) {
    }

    public function getForm($formTypeId)
    {
        try {
            $result = $this->service?->getForm($formTypeId);
            $response = $this->responseHelper->success($result);
        } catch (DataNotFoundException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }

    public function getFormDiff($formTypeId, $diffFormTypeId)
    {
        try {
            $result = $this->service?->getFormDiff($formTypeId, $diffFormTypeId);
            $response = $this->responseHelper->success($result);
        } catch (DataNotFoundException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }
}

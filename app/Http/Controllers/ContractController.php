<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidAccessException;
use App\Contracts\Services\ContractService;
use App\Exceptions\DataSaveFailedException;
use App\Exceptions\DataAlreadyCreatedException;

class ContractController extends Controller
{
    public function __construct(
        private ContractService $contractService,
        private ResponseHelper $responseHelper
    ) {
    }

    public function createDigitalContract($formTypeId, Request $request)
    {
        Log::debug("Request Data >>> " . json_encode($request->all(), true));
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'digital_sign' => 'required',
                    'ref_id' => 'required',
                    'full_name' => 'required'
                ]
            );
            if ($validator->passes()) {
                $this->contractService?->processDigitalContract($formTypeId, $request->all());
            } else {
                throw new InvalidAccessException('Missing parameters');
            }
            $response = $this->responseHelper->success('Success');
        } catch (DataNotFoundException | DataAlreadyCreatedException | DataSaveFailedException | InvalidAccessException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }

    public function getFailedContracts(Request $request)
    {
        try {
            $refType = $request->input('ref_type');
            $result = $this->contractService?->getFailedContracts($refType);
            $response = $this->responseHelper->success($result);
        } catch (DataNotFoundException | InvalidAccessException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }

    public function recreateContract(Request $request)
    {
        Log::debug("Recreate Contract Process Request Data >>> " . json_encode($request->all(), true));
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'ref_id' => 'required',
                    'ref_type' => 'required'
                ]
            );
            if ($validator->passes()) {
                $refId = $request->input('ref_id');
                $refType = $request->input('ref_type');
                $this->contractService?->recreateContract($refId, $refType);
            } else {
                throw new InvalidAccessException('Missing parameters');
            }
            $response = $this->responseHelper->success('Success');
        } catch (DataNotFoundException | InvalidAccessException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }

    public function updateContractProcess(Request $request)
    {
        Log::debug("Update Contract Process Request Data >>> " . json_encode($request->all(), true));
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'ref_id' => 'required',
                    'ref_type' => 'required'
                ]
            );
            if ($validator->passes()) {
                $refId = $request->input('ref_id');
                $refType = $request->input('ref_type');
                $this->contractService?->updateContractProcess($refId, $refType);
            } else {
                throw new InvalidAccessException('Missing parameters');
            }
            $response = $this->responseHelper->success('Success');
        } catch (DataNotFoundException | InvalidAccessException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }
}

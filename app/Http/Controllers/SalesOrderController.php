<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidAccessException;
use App\Contracts\Services\SalesOrderService;

class SalesOrderController extends Controller
{
    public function __construct(
        private SalesOrderService $salesOrderService,
        private ResponseHelper $responseHelper
    ) {
    }

    public function getSalesOrderById(Request $request)
    {
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
                $result = $this->salesOrderService->getSalesOrderById($refId, $refType);
            } else {
                throw new InvalidAccessException('Missing parameters');
            }
            $response = $this->responseHelper->success($result);
        } catch (DataNotFoundException | InvalidAccessException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }
}

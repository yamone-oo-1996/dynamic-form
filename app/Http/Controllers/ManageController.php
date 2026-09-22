<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Contracts\Services\FormService;
use App\Exceptions\DataNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidAccessException;

class ManageController extends Controller
{
    public function __construct(
        private FormService $service,
        private ResponseHelper $responseHelper
    ) {
    }

    public function createFromElement(Request $request, $formTypeId, $fromGroupId)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'form_element_mm_name' => 'required',
                    'form_element_en_name' => 'required',
                    'form_element_attribute' => 'required',
                    'element_type_id' => 'required',
                ]
            );
            if ($validator->passes()) {
                $data = $request->all();
                $result = $this->service->createFormElement($formTypeId, $fromGroupId, $data);
                $response = $this->responseHelper->success($result);
            } else {
                throw new InvalidAccessException('Missing parameters');
            }
        } catch (DataNotFoundException $e) {
            $response = $e->getResponse();
        }
        return response()->json($response, $response['status']);
    }
}

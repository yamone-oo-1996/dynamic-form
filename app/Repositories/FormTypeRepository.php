<?php

namespace App\Repositories;

use Exception;
use App\Models\FormType;
use App\Enums\RecordStatus;
use App\Exceptions\DataNotFoundException;
use App\Contracts\Repositories\FormTypeRepository as FormTypeRepositoryContract;

class FormTypeRepository implements FormTypeRepositoryContract
{
    public function __construct(
        private FormType $formType,
    ) {
    }

    protected $formTypeFilter = [
        'form_type.*',
        'service_references.id as service_reference_id',
        'system.name as system_name'
    ];

    public function getFormTypeDetail($formTypeId)
    {
        try {
            $result = $this->formType
                ->where('form_type.id', $formTypeId)
                ->where('form_type.status', RecordStatus::ACTIVE)
                ->join('service_references', 'service_references.id', '=', 'form_type.service_reference_id')
                ->join('system', 'system.id', '=', 'service_references.system_id')
                ->select($this->formTypeFilter)
                ->first();
            if (!$result) {
                throw new DataNotFoundException("Form Type don't exist.");
            }
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }
}

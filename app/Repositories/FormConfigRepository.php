<?php

namespace App\Repositories;

use Exception;
use App\Models\FormConfig;
use App\Enums\RecordStatus;
use App\Exceptions\DataNotFoundException;
use App\Contracts\Repositories\FormConfigRepository as FormConfigRepositoryContract;

class FormConfigRepository implements FormConfigRepositoryContract
{
    public function __construct(
        private FormConfig $formConfig,
    ) {
    }

    public function getFormConfigs($formTypeId)
    {
        try {
            $result = $this->formConfig
                ->where('form_type_id', $formTypeId)
                ->where('status', RecordStatus::ACTIVE)
                ->get();
            return $result;
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
    }
}

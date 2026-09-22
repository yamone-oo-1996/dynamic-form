<?php

namespace App\Repositories;

use Exception;
use App\Models\FormGroup;
use App\Enums\RecordStatus;
use App\Models\FormGroupRule;
use App\Exceptions\DataNotFoundException;
use App\Contracts\Repositories\FormGroupRepository as FormGroupRepositoryContract;

class FormGroupRepository implements FormGroupRepositoryContract
{
    public function __construct(
        private FormGroup $formGroup,
        private FormGroupRule $formGroupRule,
    ) {
    }

    protected $formRuleFormat = [
        'rule_type.name as rule_name',
        'form_group_rule.rule_value',
    ];

    public function getFormGroups($formTypeId)
    {
        try {
            $result = $this->formGroup
                ->where('form_type_id', $formTypeId)
                ->where('status', RecordStatus::ACTIVE)
                ->get();
            if ($result->isEmpty()) {
                throw new DataNotFoundException("Form Groups don't exist.");
            }
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    public function getGroupRules($formGroupId)
    {
        try {
            $result = $this->formGroupRule
                ->where('form_group_id', $formGroupId)
                ->where('form_group_rule.status', RecordStatus::ACTIVE)
                ->join('rule_type', function ($join) {
                    $join->on('rule_type.id', 'form_group_rule.rule_type_id')
                        ->where('rule_type.status', RecordStatus::ACTIVE);
                })
                ->select($this->formRuleFormat)
                ->get();
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }
}

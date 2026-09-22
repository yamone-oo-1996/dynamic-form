<?php

namespace App\Repositories;

use Exception;
use App\Models\RuleType;
use App\Enums\RecordStatus;
use App\Models\FormElement;
use App\Models\PredefinedValue;
use App\Models\FormElementAttribute;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\DataSaveFailedException;
use App\Contracts\Repositories\FormElementRepository as FormElementRepositoryContract;

class FormElementRepository implements FormElementRepositoryContract
{
    public function __construct(
        private FormElement $formElement,
        private FormElementAttribute $formAttribute,
        private PredefinedValue $predefinedValue,
        private RuleType $ruleType,
    ) {
    }

    protected $formElementFormat = [
        'form_element.*',
        'form_element.id as form_element_id',
        'form_element.en_name as form_element_en_name',
        'form_element.mm_name as form_element_mm_name',
        'element_type.*',
        'element_type.name as element_name',
    ];

    protected $formAttributeFormat = [
        'rule_type.name as rule_name',
        'form_element_attribute.rule_value',
    ];

    protected $predifinedValueFormat = [
        'predefined_value.en_name as en_label',
        'predefined_value.mm_name as mm_label',
        'predefined_value.value',
        'predefined_value.order',
    ];

    public function getFormElements($formGroupId)
    {
        try {
            $result = $this->formElement
                ->where('form_group_id', $formGroupId)
                ->where('form_element.status', RecordStatus::ACTIVE)
                ->join('element_type', function ($join) {
                    $join->on('element_type.id', 'form_element.element_type_id')
                        ->where('element_type.status', RecordStatus::ACTIVE);
                })
                ->select($this->formElementFormat)
                ->get();
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    public function getFormAttributes($formElementId)
    {
        try {
            $result = $this->formAttribute
                ->where('form_element_id', $formElementId)
                ->where('form_element_attribute.status', RecordStatus::ACTIVE)
                ->join('rule_type', function ($join) {
                    $join->on('rule_type.id', 'form_element_attribute.rule_type_id')
                        ->where('rule_type.status', RecordStatus::ACTIVE);
                })
                ->select($this->formAttributeFormat)
                ->get();
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    public function getPredifinedValues($formElementId)
    {
        try {
            $result = $this->predefinedValue
                ->where('form_element_id', $formElementId)
                ->where('status', RecordStatus::ACTIVE)
                ->select($this->predifinedValueFormat)
                ->get();
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    public function getFormElementsByFormTypeId($formTypeId)
    {
        try {
            $result = $this->formElement
                ->where('form_element.form_type_id', $formTypeId)
                ->where('form_element.status', RecordStatus::ACTIVE)
                ->join('element_type', function ($join) {
                    $join->on('element_type.id', 'form_element.element_type_id')
                        ->where('element_type.status', RecordStatus::ACTIVE);
                })
                ->select($this->formElementFormat)
                ->get();
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    public function getRules()
    {
        try {
            $result = $this->ruleType->where('status', RecordStatus::ACTIVE)->get();
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }
    public function createFormElement($data)
    {
        try {
            $result = $this->formElement->create($data);
            if (!$result) {
                throw new DataSaveFailedException("Create customer form data failed !");
            }
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    public function createFormAttributes($data)
    {
        try {
            $result = $this->formAttribute->insert($data);
            if (!$result) {
                throw new DataSaveFailedException("Create customer form data failed !");
            }
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    public function createPredefinedValues($data)
    {
        try {
            $result = $this->predefinedValue->insert($data);
            if (!$result) {
                throw new DataSaveFailedException("Create customer form data failed !");
            }
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }
}

<?php

namespace App\Services;

use Exception;
use App\Helpers\RequestHelper;
use App\Formatters\FormFormatter;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\DataSaveFailedException;
use App\Contracts\Repositories\FormTypeRepository;
use App\Contracts\Repositories\FormGroupRepository;
use App\Contracts\Repositories\FormConfigRepository;
use App\Contracts\Repositories\FormElementRepository;
use App\Contracts\Services\FormService as FormServiceContract;

class FormService implements FormServiceContract
{
    protected $lang = 'en';
    protected $catchExpirtion = null;
    protected $formElements = [];

    public function __construct(
        private FormElementRepository $formElementRepository,
        private FormGroupRepository $formGroupRepository,
        private FormConfigRepository $formConfigRepository,
        private FormTypeRepository $formTypeRepository,
        private FormFormatter $formatter,
    ) {
        $this->lang = RequestHelper::getLanguage();
        $this->catchExpirtion = config('cache.default_expiration');
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function getForm($formTypeId)
    {
        // Incorporate the language into the cache key to differentiate cached data by language
        $cacheKey = "form_data_{$formTypeId}_{$this->lang}";

        return Cache::remember($cacheKey, $this->catchExpirtion, function () use ($formTypeId) {
            $result = [];
            try {
                // Fetch form groups and form elements
                $formGroups = $this->formGroupRepository->getFormGroups($formTypeId);
                $this->formElements = $this->formElementRepository
                    ->getFormElementsByFormTypeId($formTypeId)
                    ->groupBy('form_group_id');

                // Build the form tree
                $result = $this->buildFormTree($formGroups->toArray());
                usort($result, function ($a, $b) {
                    return $a['form_group_rules']['row'] <=> $b['form_group_rules']['row'];
                });

                if (empty($result)) {
                    throw new DataNotFoundException("Form not found!");
                }
            } catch (Exception $e) {
                throw new DataNotFoundException($e->getMessage());
            }

            return $result;
        });
    }

    public function getFormDiff($formTypeId, $diffFormTypeId)
    {
        $result = [];
        try {
            #Retrieve form groups
            $formGroups = $this->formGroupRepository->getFormGroups($formTypeId);
            #Get form elements to diff
            $formElement = $this->formElementRepository->getFormElementsByFormTypeId($formTypeId);
            $diffFormElement = $this->formElementRepository->getFormElementsByFormTypeId($diffFormTypeId);
            #Diff form elements
            $this->formElements = $formElement->diffUsing($diffFormElement, function ($item1, $item2) {
                return $item1['attribute_name'] <=> $item2['attribute_name'];
            })->groupBy('form_group_id');

            #Build Form
            $formResult = $this->buildFormTree($formGroups->toArray());
            if (empty($formResult)) {
                throw new DataNotFoundException("Form not found !");
            }
            #Remove empty form groups
            $result = array_values(array_filter($formResult, function ($item) {
                return !empty($item['form_elements']);
            }));
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    protected function buildFormTree(array $elements, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            $element =  $this->formatter->formatFormGroup($element, $this->lang);
            $formGroupRules = $this->formGroupRepository->getGroupRules($element['id'])->pluck('rule_value', 'rule_name');
            if ($formGroupRules->isNotEmpty()) {
                $element['form_group_rules'] = $formGroupRules;
            }
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildFormTree($elements, $element['id']);
                if ($children) {
                    $element['form_groups'] = $children;
                } else {
                    $element['form_elements'] = $this->getFormElements($element['id']);
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    protected function getFormElements($formGroupId)
    {
        $result = [];
        $formElements = $this->formElements[$formGroupId] ?? [];
        foreach ($formElements as $formElement) {
            $formElement['form_attributes'] = $this->formElementRepository->getFormAttributes($formElement['form_element_id'])->pluck('rule_value', 'rule_name');
            $formElement['predefined_values'] = $this->formElementRepository->getPredifinedValues($formElement['form_element_id']);
            $result[] = $this->formatter->formatFormElement($formElement, $this->lang);
        }
        // Sort the result array by 'row' in 'form_attributes'
        usort($result, function ($a, $b) {
            return $a['form_attributes']['row'] <=> $b['form_attributes']['row'];
        });
        return $result;
    }

    public function getFormTypeDetail($formTypeId)
    {
        return $this->formTypeRepository->getFormTypeDetail($formTypeId);
    }

    public function getFormConfigs($formTypeId)
    {
        return $this->formConfigRepository->getFormConfigs($formTypeId);
    }

    public function createFormElement($formTypeId, $formGroupId, $data)
    {
        $result = [];
        try {
            $formElementData = [
                'en_name' =>  $data['form_element_en_name'],
                'mm_name' =>  $data['form_element_mm_name'],
                'attribute_name' =>  $data['form_element_attribute'],
                'element_type_id' => $data['element_type_id'],
                'form_type_id' => $formTypeId,
                'form_group_id' => $formGroupId,
            ];
            $formElement = $this->formElementRepository->createFormElement($formElementData);
            if (empty($formElement)) {
                throw new DataSaveFailedException("Form element create failed !");
            }

            //create form attributes
            if (!empty($data['form_attributes'])) {
                $this->createFormAttributes($data['form_attributes'], $formElement['id']);
            }

            //create predefined values
            if (!empty($data['predefined_values'])) {
                $this->createPredefinedValues($data['predefined_values'], $formElement['id']);
            }
        } catch (Exception $e) {
            throw new DataNotFoundException($e->getMessage());
        }
        return $result;
    }

    protected function createFormAttributes($data, $formElementId)
    {
        $rules = $this->formElementRepository->getRules()->pluck('id', 'name');
        $formAttributes = [];
        foreach ($data as $key => $value) {
            $formAttributes[] = [
                'form_element_id' => $formElementId,
                'rule_type_id' => $rules[$key] ?? 0,
                'rule_value' => $value
            ];
        }
        $this->formElementRepository->createFormAttributes($formAttributes);
    }

    protected function createPredefinedValues($data, $formElementId)
    {
        $predefinedValues = [];
        foreach ($data as $item) {
            $predefinedValues[] = [
                'form_element_id' => $formElementId,
                'en_name' => $item['en_name'] ?? '',
                'mm_name' => $item['mm_name'] ?? '',
                'value' => $item['value'] ?? '',
            ];
        }
        $this->formElementRepository->createPredefinedValues($predefinedValues);
    }
}

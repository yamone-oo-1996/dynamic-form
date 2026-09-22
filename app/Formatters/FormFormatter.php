<?php

namespace App\Formatters;

class FormFormatter
{
    public function formatFormGroup($data, $lang)
    {
        $result = [
            'id' => $data['id'] ?? '',
            'label' => ($lang == 'en') ? $data['en_name'] : $data['mm_name'],
            'parent_id' => $data['parent_id'] ?? '',
        ];
        return $result;
    }

    public function formatFormElement($data, $lang)
    {
        $result = [
            'label' => ($lang == 'en') ? $data['form_element_en_name'] : $data['form_element_mm_name'],
            'attribute_name' => $data['attribute_name'] ?? '',
            'element_type' => $data['element_name'] ?? '',
        ];
        if ($data['form_attributes']->isNotEmpty()) {
            $result['form_attributes'] = $data['form_attributes'];
        }
        if ($data['predefined_values']->isNotEmpty()) {
            $label = ($lang == 'en') ? 'en_label' : 'mm_label';
            $predefinedValues = $data['predefined_values']->map(function ($item) use ($label) {
                $formatted['label'] = $item[$label];
                $formatted['value'] = $item['value'];
                $formatted['order'] = $item['order'];
                return $formatted;
            });
            $result['predefined_values'] = $predefinedValues;
        }
        return $result;
    }
}

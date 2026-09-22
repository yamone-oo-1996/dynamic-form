<?php

namespace App\Contracts\Services;

interface FormService
{
    public function getForm($formTypeId);
    public function getFormDiff($formTypeId, $diffFormTypeId);
    public function getFormTypeDetail($formTypeId);
    public function getFormConfigs($formTypeId);
    public function createFormElement($formTypeId, $formGroupId, $data);
}

<?php

namespace App\Contracts\Repositories;

interface FormElementRepository
{
    public function getFormElements($formGroupId);
    public function getFormAttributes($formElementId);
    public function getPredifinedValues($formElementId);
    public function getFormElementsByFormTypeId($formTypeId);
    public function createFormElement($data);
    public function createFormAttributes($data);
    public function createPredefinedValues($data);
    public function getRules();
}

<?php

namespace App\Contracts\Repositories;

interface FormGroupRepository
{
    public function getFormGroups($formTypeId);
    public function getGroupRules($formGroupId);
}

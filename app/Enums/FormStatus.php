<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FormStatus extends Enum
{
    public const NEW = 0;
    public const PENDING = 1;
    public const CANCEL = 2;
    public const COMPLETE = 3;
}

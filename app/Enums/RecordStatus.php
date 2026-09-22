<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RecordStatus extends Enum
{
    public const ACTIVE = 0;
    public const DEACTIVATE = 1;
    public const SOFT_DELETED = 2;
}

<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ApplicationStatus extends Enum
{
    public const NEW = 0;
    public const IN_PROGRESS = 1;
    public const ACTIVE = 2;
    public const CANCEL = 3;
    public const EXPIRED = 4;
}

<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Enums\Traits\GetsAttributes;

enum PermissionStatus: int
{
    use GetsAttributes;

    #[Description('禁用')]
    case Disabled = 0;

    #[Description('启用')]
    case Enabled = 1;
}

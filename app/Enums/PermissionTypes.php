<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Enums\Traits\GetsAttributes;

enum PermissionTypes: int
{
    use GetsAttributes;

    #[Description('菜单')]
    case View = 0;

    #[Description('权限')]
    case Permission = 1;
}

<?php

namespace App\Enums;

use App\Enums\Attributes\Description;
use App\Enums\Traits\GetsAttributes;

enum DesignType: int
{
    use GetsAttributes;

    #[Description('列表')]
    case List = 'list';

    #[Description('表单')]
    case Form = 'form';
}

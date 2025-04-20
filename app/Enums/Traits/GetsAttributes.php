<?php

namespace App\Enums\Traits;

use App\Enums\Attributes\Description;

trait GetsAttributes
{
    public function description(): string
    {
        return once(function () {
            try {
                $reflection = new \ReflectionEnum($this);
                $attributes = $reflection->getCase($this->name)->getAttributes(Description::class);
            } catch (\ReflectionException) {
                return '';
            }
            return $attributes[0]->newInstance()->description;
        });
    }
}
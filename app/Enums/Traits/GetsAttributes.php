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

    public static function options(): array
    {
        return once(function () {
            try {
                $reflection = new \ReflectionEnum(self::class);
                $attributes = $reflection->getCases();
            } catch (\ReflectionException) {
                return '';
            }
            return array_map(function ($v) {
                return [
                    'label' => $v->getAttributes(Description::class)[0]->newInstance()->description,
                    'value' => $v->getValue(),
                ];
            }, $attributes);
        });
    }

    public static function fromName(string $name)
    {
        foreach (self::cases() as $status) {
            if ($name === $status->value) {
                return $status->description();
            }
        }
    }
}

<?php

namespace App\Http\Requests\Admin\Design;

use App\Enums\DesignType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DesignEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'type' => '类型'
        ];
    }

    public function rules(): array
    {
        return [
            'id' => ['required'],
            'name' => ['required', 'string', "max:20"],
            'type' => ["required", Rule::in(array_map(fn ($v) => $v->value,DesignType::cases()))],
        ];
    }
}

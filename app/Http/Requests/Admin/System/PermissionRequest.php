<?php

namespace App\Http\Requests\Admin\System;

use App\Enums\PermissionTypes;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
            'display_name' => '显示名称',
            'icon' => '图标',
            'pid' => '父级',
            'type' => '权限类型',
            'name' => '标识',
            'status' => '状态',
        ];
    }

    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string', "max:20"],
            'icon' => ["max:60"],
            'pid' => [
                'required',
                'integer',
            ],
            'type' => ['required', Rule::enum(PermissionTypes::class)],
            'name' => ['required', 'string', "max:100", Rule::unique(Permission::class)->where('guard_name', 'admin')->ignore($this->id)],
            'status' => ['required', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('pid', [
            Rule::exists(Permission::class, 'id')
        ], fn($input) => isset($input->pid) && $input->pid > 0);
    }
}

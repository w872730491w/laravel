<?php

namespace App\Http\Requests\Admin\System;

use App\Enums\PermissionTypes;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionEditRequest extends FormRequest
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
            'display_name' => '显示名称',
            'route' => '路由',
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
            'id' => ['required', 'integer'],
            'display_name' => ['required', 'string', "max:20"],
            'icon' => ["max:60"],
            'pid' => [
                'required',
                'integer',
                Rule::when(isset($this->pid) && $this->pid > 0, [Rule::exists(Permission::class, 'id')])
            ],
            'type' => ['required', Rule::enum(PermissionTypes::class)],
            'route' => [Rule::requiredIf(isset($this->type) && $this->type == PermissionTypes::View->value), "max:200"],
            'name' => ['required', 'string', "max:100", Rule::unique(Permission::class)->where('guard_name', 'admin')->ignore($this->id)],
            'status' => ['required', 'boolean'],
        ];
    }
}

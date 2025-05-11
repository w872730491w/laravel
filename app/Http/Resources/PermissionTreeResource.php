<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionTreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $permissionsArray = PermissionResource::collection($this->resource)->toArray($request);

        return $this->buildPermissionTree($permissionsArray);
    }

    /**
     * 构建权限树形结构
     * 
     * @param array $permissions 权限列表
     * @param int $pid 父级ID
     * @return array
     */
    private function buildPermissionTree(array $permissions, int $pid = 0): array
    {
        $tree = [];
        foreach ($permissions as $permission) {
            if ($permission['pid'] == $pid) {
                $children = $this->buildPermissionTree($permissions, $permission['id']);
                if (!empty($children)) {
                    $permission['children'] = $children;
                }
                $tree[] = $permission;
            }
        }
        return $tree;
    }
}
<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionTreeResource;
use App\Models\Permission as ModelsPermission;
use Illuminate\Http\Request;

class Permission extends Controller
{
    public function index()
    {
        $data = ModelsPermission::where('guard_name', 'admin')->get();

        return inertia('system/permission/Permission', [
            'data' => $data->toResourceCollection()->resource
        ]);
    }

    public function lazyData()
    {
        $data = ModelsPermission::where('guard_name', 'admin')->get();
        
        return $this->success(response: new PermissionTreeResource($data));
    }
}

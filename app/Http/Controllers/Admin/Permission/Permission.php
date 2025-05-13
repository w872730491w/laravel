<?php

namespace App\Http\Controllers\Admin\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionTreeResource;
use App\Models\Permission as ModelsPermission;
use App\Http\Requests\Admin\System\PermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class Permission extends Controller
{
    /**
     * 列表页
     * @return \Inertia\Response
     */
    public function index()
    {
        return inertia('system/permission/Permission');
    }

    public function list()
    {
        $data = ModelsPermission::where('guard_name', 'admin')->get();
        return $this->response(response: new PermissionTreeResource($data));
    }

    /**
     * 添加
     * @param PermissionRequest $request 
     * @return JsonResponse 
     * @throws ValidationException 
     * @throws PermissionAlreadyExists 
     * @throws BindingResolutionException 
     */
    public function create(PermissionRequest $request)
    {
        $post = $request->validated();
        $post['guard_name'] = 'admin';
        $post['icon'] ??= '';
        $post['pid'] ??= 0;
        ModelsPermission::create($post);
        return $this->response(message: '添加成功');
    }

    /**
     * 修改
     * @param PermissionRequest $request 
     * @return JsonResponse 
     * @throws ValidationException 
     * @throws PermissionAlreadyExists 
     * @throws BindingResolutionException 
     */
    public function edit(PermissionRequest $request)
    {
        $post = $request->validated();
        $permission = ModelsPermission::find($post['id']);
        if ($permission) {
            $permission->save($post);
        }
        return $this->response(message: '修改成功');
    }

    /**
     * 详情
     * @param Request $request 
     * @return JsonResponse 
     * @throws BadRequestException 
     * @throws BindingResolutionException 
     */
    public function detail(Request $request)
    {
        $permission = ModelsPermission::find($request->get('id'));
        return $this->response(response: $permission);
    }

    /**
     * 懒加载数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function lazyData()
    {
        $data = ModelsPermission::where('guard_name', 'admin')->get();

        return $this->response(response: [
            'permissions' => new PermissionTreeResource($data)
        ]);
    }
}

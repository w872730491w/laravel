<?php

namespace App\Http\Controllers\Admin\Config;

use App\Enums\DesignType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Design as DesignModel;
use App\Http\Requests\Admin\Design\DesignEditRequest;
use App\Http\Requests\Admin\Design\DesignCreateRequest;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\InvalidCastException;

class Design extends Controller
{
    public function index()
    {
        return inertia('system/design/index');
    }

    /**
     * 列表
     * @param Request $request 
     * @return mixed 
     */
    public function list(Request $request)
    {
        $data = DesignModel::paginate($request->post('limit', 10))->toResourceCollection();

        return $this->response(response: [
            'total' => $data->total(),
            'rows' => $data->items(),
        ]);
    }

    /**
     * 添加
     * @param DesignCreateRequest $request 
     * @return mixed 
     * @throws ValidationException 
     * @throws InvalidArgumentException 
     */
    public function create(DesignCreateRequest $request)
    {
        $data = $request->validated();
        DesignModel::create($data);

        return $this->response(message: '添加成功');
    }

    /**
     * 修改
     * @param DesignEditRequest $request 
     * @return mixed 
     * @throws ValidationException 
     * @throws InvalidArgumentException 
     * @throws MassAssignmentException 
     * @throws InvalidCastException 
     */
    public function edit(DesignEditRequest $request)
    {
        $data = $request->validated();

        $design = DesignModel::find($data['id']);

        $design->fill($data)->save();

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
        $design = DesignModel::find($request->get('id'));
        return $this->response(response: $design);
    }

    /**
     * 懒加载数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function lazyData()
    {
        return $this->response(response: [
            'types' => DesignType::options()
        ]);
    }
}

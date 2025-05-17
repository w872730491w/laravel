<?php

namespace App\Http\Controllers\Admin\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin as AdminModel;

class Admin extends Controller
{
    /**
     * 列表页
     * @return \Inertia\Response
     */
    public function index()
    {
        return inertia('Render');
    }

    public function list(Request $request)
    {
        $post = $request->post();
        $data = AdminModel::where('guard_name', 'admin')->paginate($post['limit'] ?? 10);

        return $this->response(response: [
            'total' => $data->total(),
            'rows' => $data->items(),
        ]);
    }
}

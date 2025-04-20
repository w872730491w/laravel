<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = config('permission.table_names');
        Schema::table($tableName['permissions'], function (Blueprint $table) {
            $table->bigInteger('pid')->unsigned()->after('id')->comment('父级id');
            $table->tinyInteger('type')->after('pid')->comment('类型 0菜单 1权限');
            $table->string('display_name', 30)->after('name')->comment('显示名称');
            $table->string('icon', 50)->after('display_name')->comment('图标');
            $table->string('route')->after('type')->default('')->comment('路由');
            $table->bigInteger('sort')->unsigned()->default(0)->after('route')->comment('排序');
            $table->tinyInteger('status')->unsigned()->default(1)->after('sort')->comment('状态 0禁用 1启用');
            $table->comment('权限表');
        });
        Schema::table($tableName['roles'], function (Blueprint $table) {
            $table->string('display_name')->after('name')->comment('显示名称');
            $table->comment('角色表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('permission.table_names');
        Schema::table($tableName['permissions'], function (Blueprint $table) {
            $table->dropColumn([
                'pid',
                'type',
                'display_name',
                'icon',
                'route',
                'sort',
                'status'
            ]);
        });
        Schema::table($tableName['roles'], function (Blueprint $table) {
            $table->dropColumn([
                'display_name'
            ]);
        });
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name 名称
 * @property string $type 类型 列表:list 表单:form
 * @property string $data 数据
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Design whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Design extends Model
{
    protected $fillable = [
        'name', 'type'
    ];
}

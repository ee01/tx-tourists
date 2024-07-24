<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tourist extends Model
{
    // 指定表名，如果模型名的复数形式与表名相同，这一行不是必须的
    protected $table = 'tourists';

    // 指定可以批量赋值的字段
    protected $fillable = ['xing', 'ming', 'raw'];

    // 如果你不想让Eloquent自动管理created_at和updated_at列，设置public $timestamps = false;

    // 可以在这里定义更多的方法，比如与其他模型的关系等
}
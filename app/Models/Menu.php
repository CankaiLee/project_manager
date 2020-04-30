<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'menus';
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable(false)->default(0)->comment('父级菜单id');
            $table->string('title')->nullable()->comment('菜单标题');
            $table->tinyInteger('type')->nullable(false)->default(1)->comment('类型。1：菜单；2：权限');
            $table->string('uri')->nullable(false)->default('/')->comment('菜单路径');
            $table->string('icon')->nullable()->comment('图标');
            $table->tinyInteger('status')->nullable(false)->default(0)->comment('状态。0：未启用； 1：启用');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('url')->comment('资源地址');
            $table->string('path')->comment('本地存储路径');
            $table->tinyInteger('type')->nullable(false)->default(1)->comment('类型。1：图片；2：文件；3：音频；4：视频');
            $table->text('info')->comment('资源信息');
            $table->tinyInteger('in_local')->nullable(false)->default(1)->comment('是否存储在本地。1：是；0：否');
            $table->integer('size')->nullable(false)->default(0)->comment('文件大小');
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
        Schema::dropIfExists('uploads');
    }
}

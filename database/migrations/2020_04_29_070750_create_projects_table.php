<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->char('code', 8)->nullable(false)->default(0)->unique('idx_unique_code')->comment('项目编号');
            $table->string('title')->comment('项目标题');
            $table->string('sub_title')->comment('项目子标题');
            $table->string('intro')->comment('项目简介');
            $table->integer('logo_upload_id')->nullable(false)->default(0)->index('idx_logo_upload_id')->comment('项目logo');
            $table->mediumText('content')->comment('项目内容');
            $table->tinyInteger('level')->nullable(false)->default(3)->comment('任务等级。1：最高；2：较高；3：普通；4：较低');
            $table->tinyInteger('status')->nullable(false)->default(0)->comment('项目状态。0：未启用；1：进行中；2：已完成；3：暂缓处理；4：已取消；5：已逾期。');
            $table->timestamp('date_completed')->nullable()->comment('任务计划完成时间');
            $table->timestamp('date_committed')->nullable()->comment('任务实际完成时间');
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
        Schema::dropIfExists('projects');
    }
}

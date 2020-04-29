<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->char('code', 8)->nullable(false)->default(0)->unique('idx_uni_code')->comment('任务代号');
            $table->char('project_code', 10)->nullable(false)->default(0)->index('idx_project_code')->comment('项目代号');
            $table->integer('parent_id')->nullable(false)->default(0)->index('idx_parent_id')->comment('父级任务id');
            $table->string('name')->comment('任务名称');
            $table->tinyInteger('level')->nullable(false)->default(3)->comment('任务等级。1：最高；2：较高；3：普通；4：较低');
            $table->tinyInteger('status')->nullable(false)->default(0)->comment('状态。0：未完成；1：已完成；2：已逾期');
            $table->text('content')->comment('任务描述');
            $table->timestamp('date_committed')->nullable()->comment('任务实际完成时间');
            $table->timestamp('date_completed')->nullable()->comment('任务计划完成时间');
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
        Schema::dropIfExists('tasks');
    }
}

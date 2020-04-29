<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMysqlLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mysql_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query')->comment('sql 语句');
            $table->tinyInteger('type')->nullable(false)->default(1)->comment('DML类型：1:select;2:insert;3:update;4:delete;');
            $table->bigInteger('begin')->nullable(false)->default(0)->comment('执行开始时间');
            $table->bigInteger('end')->nullable(false)->default(0)->comment('执行结束时间');
            $table->integer('spent')->nullable(false)->default(0)->comment('执行耗费时间');
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
        Schema::dropIfExists('mysql_logs');
    }
}

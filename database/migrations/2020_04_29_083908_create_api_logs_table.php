<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('url')->comment('请求地址');
            $table->text('data')->comment('数据');
            $table->string('message')->comment('提示信息');
            $table->ipAddress('ip_address');
            $table->text('user_agent');
            $table->string('browser');
            $table->string('browser_version');
            $table->string('mobile');
            $table->string('platform');
            $table->string('ip_country');
            $table->string('ip_province');
            $table->string('ip_city');
            $table->year('year');
            $table->tinyInteger('month');
            $table->tinyInteger('day');
            $table->tinyInteger('hour');
            $table->tinyInteger('minute');
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
        Schema::dropIfExists('api_logs');
    }
}

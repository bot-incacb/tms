<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('openapi_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('名称');
            $table->string('username')->unique()->comment('应用key');
            $table->string('password', 500)->comment('应用密钥');
            $table->boolean('is_enabled')->default(true)->comment('是否已启用');
            $table->timestamps();
            $table->softDeletes();
        });
        table_comment('openapi_apps', 'openapi应用表');
    }

    public function down()
    {
        Schema::dropIfExists('openapi_apps');
    }
};

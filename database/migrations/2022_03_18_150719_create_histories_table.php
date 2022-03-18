<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entry_id', false, true)->index()->comment('词条id');
            $table->text('data')->comment('改动数据');
            $table->timestamps();
            $table->softDeletes();
        });
        table_comment('histories', '发布历史表');
    }

    public function down()
    {
        Schema::dropIfExists('histories');
    }
};

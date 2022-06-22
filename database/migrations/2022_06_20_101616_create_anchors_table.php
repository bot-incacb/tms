<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('anchors', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('唯一key');
            $table->string('url', 500)->comment('链接');
            $table->string('remark')->default('')->comment('备注');
            $table->timestamps();
            $table->softDeletes();
        });
        table_comment('anchors', '锚点表');

        Schema::create('anchor_entry', function (Blueprint $table) {
            $table->bigInteger('anchor_id', false, true)->comment('锚点ID')->index();
            $table->bigInteger('entry_id', false, true)->comment('词条ID')->index();
        });
        table_comment('anchor_entry', '锚点词条关联表');
    }

    public function down()
    {
        Schema::dropIfExists('anchors');
    }
};

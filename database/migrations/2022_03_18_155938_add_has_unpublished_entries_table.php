<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('has_unpublished')->default(true)->after('key')->comment('是否有未发布内容');
        });
    }

    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('has_unpublished');
        });
    }
};

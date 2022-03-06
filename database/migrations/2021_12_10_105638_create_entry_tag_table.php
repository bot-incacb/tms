<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_tag', function (Blueprint $table) {
            $table->bigInteger('entry_id', false, true)->index()->comment('词条ID');
            $table->bigInteger('tag_id', false, true)->index()->comment('标签ID');
        });
        table_comment('entry_tag', '词条标签关联表');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry_tag');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entry_id', false, true)->comment('词条表ID')->index();
            $table->string('lang', 20)->comment('语言代码')->index();
            $table->string('unpublished_content', 500)->default('')->comment('未发布内容');
            $table->string('published_content', 500)->default('')->comment('已发布内容');
            $table->tinyInteger('quality')->default(1)->comment('翻译质量，1.机翻，2.人工，3.已校准');
            $table->boolean('is_original')->default(false)->comment('源文本');
            $table->timestamps();
            $table->softDeletes();
        });
        table_comment('translates', '翻译表');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translates');
    }
}

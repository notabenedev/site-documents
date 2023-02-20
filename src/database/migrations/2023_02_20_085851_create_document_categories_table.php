<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');

            $table->string('slug')
                ->unique();

            $table->tinyText('short')
                ->nullable()
                ->comment("Краткое описание");

            $table->text("description")
                ->nullable()
                ->comment("Описание");

            $table->text("info")
                ->nullable()
                ->comment('Дополнительная информация');

            $table->dateTime("published_at")
                ->nullable()
                ->comment('Дата публикации');

            $table->unsignedBigInteger("parent_id")
                ->nullable()
                ->comment("Родительская категория");


            $table->boolean("nested")
                ->nullable()
                ->comment('Раскрыть вложенные категории');

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
        Schema::dropIfExists('document_categories');
    }
}

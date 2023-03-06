<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->string("title")
                ->comment("Имя файла");

            $table->string("slug")
                ->comment("Служебное имя");

            $table->text("description")
                ->nullable();

            $table->string("path")
                ->comment("Путь к файлу");

            $table->nullableMorphs("documentable");

            $table->unsignedBigInteger("priority")
                ->default(1)
                ->comment("Приоритет");

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
        Schema::dropIfExists('documents');
    }
}

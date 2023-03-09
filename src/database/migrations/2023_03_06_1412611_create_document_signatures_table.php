<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("document_id")->comment("Документ");
            $table->string("title")->comment("Заголовок файла");
            $table->string("path")->comment("Путь к файлу");
            $table->string("person")->comment("Подписант")->nullable();
            $table->string("position")->comment("Должность")->nullable();
            $table->string("organization")->comment("Организация")->nullable();
            $table->string("date")->comment("Дата прдписания")->nullable();
            $table->string("certificate")->comment("Сертификат")->nullable();
            $table->text("issued")->comment("Кем выдан")->nullable();
            $table->string("period")->comment("Период действия сертификата")->nullable();
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
        Schema::dropIfExists('document_signatures');
    }
}

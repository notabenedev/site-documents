<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityToDocumentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropColumn("published_at");
            $table->unsignedBigInteger("priority")
                ->default(0)
                ->comment("Приоритет")
                ->after("nested");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dateTime("published_at")
                ->nullable()
                ->comment('Дата публикации')
                ->after("info");
            $table->dropColumn('priority');
        });
    }
}

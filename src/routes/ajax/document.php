<?php

use Illuminate\Support\Facades\Route;
use Notabenedev\SiteDocuments\Http\Controllers\Admin\DocumentController;

// Админка.
Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'management'],
], function () {
    Route::prefix("vue")->group(function () {
        // Документы.
        Route::group([
            "prefix" => "vue/".config("site-documents.documentUrlName")."/{model}/{id}",
            "as" => "admin.vue.documents.",
        ], function () {
            // Загрузка документа.
            Route::post("/", [DocumentController::class, "store"])
                ->name("store");
            // Порядок изображений.
            Route::put("/", [DocumentController::class, "updateOrder"])
                ->name("order");
            // Список документов.
            Route::get("/", [DocumentController::class, "list"])
                ->name("list");
            // Удалить файл.
            Route::delete("/{document}", [DocumentController::class, "destroy"])
                ->name("delete");
            // Обновить
            Route::put("/{document}", [DocumentController::class, "update"])
                ->name("update");
        });
    });
});

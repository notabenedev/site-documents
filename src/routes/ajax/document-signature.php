<?php

use Illuminate\Support\Facades\Route;
use Notabenedev\SiteDocuments\Http\Controllers\Admin\DocumentSignatureController;

// Админка.
Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'management'],
], function () {
    Route::prefix("vue")->group(function () {
        // Документы.
        Route::group([
            "prefix" => "vue/".config("site-documents.documentUrlName")."-signatures/{id}",
            "as" => "admin.vue.document-signatures.",
        ], function () {
            // Загрузка подписи
            Route::post("/", [DocumentSignatureController::class, "store"])
                ->name("store");
            // Список подписей
            Route::get("/", [DocumentSignatureController::class, "show"])
                ->name("show");
            // Удалить
            Route::delete("/{signature}", [DocumentSignatureController::class, "destroy"])
                ->name("delete");
            // Обновить
            Route::put("/{signature}", [DocumentSignatureController::class, "update"])
                ->name("update");
        });
    });
});

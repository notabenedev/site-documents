<?php
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\Vendor\SiteDocuments\Admin\DocumentCategoryController;

Route::group([
    "middleware" => ["web", "management"],
    "as" => "admin.",
    "prefix" => "admin",
], function () {
    Route::group([
        "prefix" => config("site-documents.documentCategoryUrlName"),
        "as" => "document-categories.",
    ],function (){
        Route::get("/", [DocumentCategoryController::class, "index"])->name("index");
        Route::get("/create", [DocumentCategoryController::class, "create"])->name("create");
        Route::post("", [DocumentCategoryController::class, "store"])->name("store");
        Route::get("/{category}", [DocumentCategoryController::class, "show"])->name("show");
        Route::get("/{category}/edit", [DocumentCategoryController::class, "edit"])->name("edit");
        Route::put("/{category}", [DocumentCategoryController::class, "update"])->name("update");
        Route::delete("/{category}", [DocumentCategoryController::class, "destroy"])->name("destroy");
    });

    // Изменить вес у категорий.
    Route::put(config("site-documents.documentCategoryUrlName")."/tree/priority", [DocumentCategoryController::class,"changeItemsPriority"])
        ->name("document-categories.item-priority");

    Route::group([
        "prefix" => config("site-documents.documentCategoryUrlName")."/{category}",
        "as" => "document-categories.",
    ], function () {
        // Добавить подкатегорию.
        Route::get("create-child", [DocumentCategoryController::class,"create"])
            ->name("create-child");
        // Сохранить подкатегорию.
        Route::post("store-child", [DocumentCategoryController::class,"store"])
            ->name("store-child");
        // Meta.
        Route::get("metas", [DocumentCategoryController::class,"metas"])
            ->name("metas");
    });
}
);

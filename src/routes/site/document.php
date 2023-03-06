<?php

use Notabenedev\SiteDocuments\Http\Controllers\Site\DocumentController;
use Illuminate\Support\Facades\Route;

// Document download
Route::group([
    "middleware" => ["web"],
    "as" => "site.",
    "prefix" => config("site-documents.documentUrlName"),
    ], function () {
    Route::get("/{document}", [DocumentController::class, "show"])->name("documents.show");
});
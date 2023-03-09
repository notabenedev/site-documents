<?php

use Notabenedev\SiteDocuments\Http\Controllers\Site\DocumentSignatureController;
use Illuminate\Support\Facades\Route;

// Document download
Route::group([
    "middleware" => ["web"],
    "as" => "site.",
    "prefix" => config("site-documents.documentUrlName")."-signature",
    ], function () {
    Route::get("/{sig}", [DocumentSignatureController::class, "show"])->name("documents.sig.show");
});
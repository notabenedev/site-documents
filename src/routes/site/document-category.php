<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\SiteDocuments\Site",
    "middleware" => ["web"],
    "as" => "site.document-categories.",
    "prefix" => config("site-documents.documentCategoryUrlName"),
], function () {
    Route::get("/", "DocumentCategoryController@index")->name("index");
    Route::get("/{category}", "DocumentCategoryController@show")->name("show");
});
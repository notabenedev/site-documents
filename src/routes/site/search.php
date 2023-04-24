<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\SiteDocuments\Site",
    "middleware" => ["web"],
    "as" => "site.document-search.",
    "prefix" => config("site-documents.documentCategoryUrlName").'-search',
], function () {
    Route::get("/", "DocumentSearchController@index")->name("index");
});
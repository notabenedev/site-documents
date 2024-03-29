@extends('layouts.boot')

@section('page-title', config("site-documents.sitePackageName","Документы").' - ')

@section('header-title')
       {{ config("site-documents.sitePackageName","Документы ") }}
@endsection

@section('content')
    <div class="col-12{{ config("site-documents.onePage",true) && config("site-documents.onePageSidebar",true) ? " col-lg-8": "" }}">
        @foreach($categories as $item)
            <div class="row documents-category">
                <div class="col-12">
                    @include("site-documents::site.document-categories.includes.item", ["item" => $item, "first" => true, "level" => 1])
                </div>
            </div>
        @endforeach
    </div>
    @if (config("site-documents.onePage",true) && config("site-documents.onePageSidebar",false))
        <div class="col-12 col-lg-4 order-first order-lg-last documents-sidebar">
            @include('site-documents::site.document-categories.includes.sidebar-one-page')
        </div>
    @endif
@endsection


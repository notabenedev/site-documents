@extends('layouts.boot')

@section('page-title', config("site-documents.sitePackageName","Документы").' - ')

@section('header-title')
       {{ config("site-documents.sitePackageName","Документы ") }}
@endsection

@section('content')
    <div class="col-12 col-lg-8">
        @foreach($categories as $item)
            <div class="row documents__category">
                <div class="col-12">
                    @include("site-documents::site.document-categories.includes.item", ["item" => $item, "first" => true, "level" => 1])
                </div>
            </div>
        @endforeach
    </div>
    @if (config("site-documents.onePage",true))
        <div class="col-12 col-lg-4 documents__sidebar">
            @include('site-documents::site.document-categories.includes.sidebar-one-page')
        </div>
    @endif
@endsection


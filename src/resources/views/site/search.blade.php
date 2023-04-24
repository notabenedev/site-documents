@extends('layouts.boot')

@section('page-title', 'Поиск по разделу '.config("site-documents.sitePackageName").' - ')

@section('header-title')
    Поиск по разделу {{ config("site-documents.sitePackageName") }}
@endsection

@section('content')
    @if (config("site-documents.siteShowSearchForm"))
        <div class="col-12 my-3">
            @include("site-documents::site.includes.search-form")
        </div>
    @endif
    @if( $request->get("search") )
            <div class="col-12 col-lg-8">
                <h3 class="h4 mt-3">По Вашему запросу найдено:</h3>
            </div>
            <div class="col-12">
                <h2 class="h3 mt-3">Категорий:  {{ $findCategories->count() }}</h2>
            </div>
            @foreach ($findCategories as $item)
                <div class="col-12">
                    <a href="{{ route("site.document-categories.show",["category" => $item]) }}">{{ $item->title }}</a>
                </div>
            @endforeach

            <div class="col-12">
                <h2 class="h3 mt-3">Документов: {{ $findDocuments->total() }}</h2>
            </div>


            @foreach ($findDocuments as $item)
                <div class="col-12">
                    {!! $item->getTeaser() !!}
                </div>
            @endforeach

        @if ($findDocuments->lastPage() > 1)
                <div class="col-12">
                    {{ $findDocuments->links() }}
                </div>
        @endif
    @endif

@endsection

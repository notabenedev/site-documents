@extends('layouts.boot')

@section('page-title', $category->title.' - '.config("site-documents.sitePackageName","Документы ")." - ")

@section('content')

    @if (! config("site-documents.onePage",false))
        <div class="col-12 col-lg-4 order-lg-first documents-sidebar">
            @include('site-documents::site.document-categories.includes.sidebar', ["category" => $category])
        </div>
    @endif

    <div class="col-12 col-lg-8">
        <div class="row documents-category">
            <div class="col-12">
                <h1 class="documents-category__title">
                    {{  $category->title }}
                </h1>
                @isset($category->short)
                    <p class="documents-category__short">
                        {{ $category->short }}
                    </p>
                @endisset
            </div>
            @isset($category->description)
                <div class="col-12">
                    <div class="documents-category__description">
                        {!! $category->description  !!}
                    </div>
                </div>
            @endisset

            @foreach(\Notabenedev\SiteDocuments\Facades\DocumentActions::getModelDocumentsIds("App\DocumentCategory",$category->id) as $id => $document)
                @if ($loop->first)
                    <div class="col-12">
                @endif
                    {!! $document->getTeaser() !!}
                @if ($loop->last)
                    </div>
                @endif
            @endforeach
        </div>
        @if($category->nested)
            @foreach($childrenTree as $id => $tree )
                <div class="accordion" id="accordion{{ $id}}">
                    @foreach($tree["children"] as $item)
                        <button class="btn btn-outline-primary btn-block text-start mb-3"
                                type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $item["slug"]}}"
                                aria-expanded="true"
                                aria-controls="{{ $item["slug"] }}">
                            {{ $item["title"] }}
                        </button>
                        <div id="collapse{{ $item["slug"] }}"
                             class="collapse"
                             aria-labelledby="heading{{ $item["slug"] }}"
                             data-bs-parent="#accordion{{ $id }}">
                            <div class="row documents-category">
                                <div class="col-12">
                                    @include("site-documents::site.document-categories.includes.item", ["item" => $item, "first" => true, "level" => 1])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
            <div class="row documents-category">
                <div class="col-12">
                @foreach($category->children as $item )
                    <a class="btn btn-outline-primary d-block documents-category__child"
                       href="{{ route("site.document-categories.show", ["category" => $item]) }}">
                            {{ $item->title }}
                    </a>
                @endforeach
                </div>
            </div>
        @endif

        <div class="row documents-category">
            <div class="col-12">
                @isset($category->info)
                    <div class="documents-category__info">
                        {!! $category->info !!}
                    </div>
                @endisset
            </div>
        </div>
    </div>

@endsection


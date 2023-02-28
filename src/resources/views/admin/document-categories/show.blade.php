@extends("admin.layout")

@section("page-title", "{$category->title} - ")

@section('header-title', "{$category->title}")

@section('admin')
    @include("site-documents::admin.document-categories.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    @if ($category->short)
                        <dt class="col-sm-3">Краткое описание</dt>
                        <dd class="col-sm-9">{{ $category->short }}</dd>
                    @endif
                    @if ($category->description)
                        <dt class="col-sm-3">Описание</dt>
                        <dd class="col-sm-9">{!! $category->description !!}</dd>
                    @endif
                    @if ($category->info)
                        <dt class="col-sm-3">Информация</dt>
                        <dd class="col-sm-9">{!! $category->info !!}</dd>
                    @endif
                        @if(! config("site-documents.onePage", false))
                            @if ($category->nested)
                            <dt class="col-sm-3">Раскрыть все вложенные группы</dt>
                            <dd class="col-sm-9">Да</dd>
                            @endif
                    @endif
                    @if ($category->parent)
                        <dt class="col-sm-3">Родитель</dt>
                        <dd class="col-sm-9">
                            <a href="{{ route("admin.document-categories.show", ["category" => $category->parent]) }}">
                                {{ $category->parent->title }}
                            </a>
                        </dd>
                    @endif
                    @if ($childrenCount)
                        <dt class="col-sm-3">Дочерние</dt>
                        <dd class="col-sm-9">{{ $childrenCount }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    @if ($childrenCount)
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Подкатегории</h5>
                </div>
                <div class="card-body">
                    @include("site-documents::admin.document-categories.includes.table-list", ["categories" => $children])
                </div>
            </div>
        </div>
    @endif
@endsection
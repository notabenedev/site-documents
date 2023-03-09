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

    @can("viewAny", \App\Document::class)
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Документы</h5>
                </div>
            </div>
        </div>
        @canany(["update", "create", "delete"], \App\Document::class)
            <div class="col-12">
                <documents-loader :list-url="'{{ route("admin.vue.documents.list", ["model" => "documentCategory", "id" => $category->id]) }}'"
                                  :store-url="'{{ route("admin.vue.documents.store", ["model" => "documentCategory", "id" => $category->id]) }}'"
                                  @can('delete', \App\Document::class)
                                  can-delete
                                  @endcan
                                  @can('create', \App\Document::class)
                                  can-create
                                  @endcan
                                  @can('update', \App\Document::class)
                                  can-update
                                  @endcan
                                  @can('update', \App\DocumentSignature::class)
                                  signatures-can-update
                                  @endcan
                                  @can('delete', \App\DocumentSignature::class)
                                  signatures-can-delete
                                  @endcan
                                  @can('create', \App\DocumentSignature::class)
                                  signatures-can-create
                                  @endcan
                                  @can('viewAny', \App\DocumentSignature::class)
                                  signatures-can-view-any
                                  @endcan
                                  @can('view', \App\DocumentSignature::class)
                                  signatures-can-view
                                  @endcan
                >
                </documents-loader>
            </div>
        @else
            <ul>
                @foreach ($category->documents as $document)
                    <li>
                        @can("view", \App\Document::class)
                            <a href="{{ route('site.documents.show', ['document' => $document]) }}"
                               class="btn btn-link">
                                {{ $document->title }}
                            </a>
                        @else
                            <span>{{ $document->title }}</span>
                        @endcan
                    </li>
                @endforeach
            </ul>
        @endcanany
    @endcan
@endsection
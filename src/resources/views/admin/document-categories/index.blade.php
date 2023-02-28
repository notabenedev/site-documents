@extends("admin.layout")

@section("page-title", config("site-documents.sitePackageName"))

@section('header-title', config("site-documents.sitePackageName"))

@section('admin')
    @include("site-documents::admin.document-categories.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if ($isTree)
                    @include("site-documents::admin.document-categories.includes.tree", ["categories" => $categories])
                @else
                    @include("site-documents::admin.document-categories.includes.table-list", ["categories" => $categories])
                @endif
            </div>
        </div>
    </div>
@endsection
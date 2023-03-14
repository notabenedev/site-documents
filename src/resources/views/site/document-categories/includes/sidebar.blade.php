<div class="list-group list-group-flush documents-menu">
    @php($route =  "site.document-categories.show")
    @foreach ($categoriesTree as $item)
        @include('site-documents::site.document-categories.includes.item-sidebar', ['item' => $item])
    @endforeach
</div>
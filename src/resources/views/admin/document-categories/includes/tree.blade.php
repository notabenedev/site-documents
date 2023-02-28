@can("update", \App\DocumentCategory::class)
    <admin-document-category-list :structure="{{ json_encode($categories) }}"
                         :nesting="{{ config("site-documents.documentCategoriesNest") }}"
                         :update-url="'{{ route("admin.document-categories.item-priority") }}'">
    </admin-document-category-list>
@else
    <ul>
        @foreach ($categories as $category)
            <li>
                @can("view", \App\DocumentCategory::class)
                    <a href="{{ route('admin.document-categories.show', ['category' => $category["slug"]]) }}"
                       class="btn btn-link">
                        {{ $category["title"] }}
                    </a>
                @else
                    <span>{{ $category['title'] }}</span>
                @endcan
                <span class="badge badge-secondary">{{ count($category["children"]) }}</span>
                @if (count($category["children"]))
                    @include("site-documents::admin.document-categories.includes.tree", ['$categories' => $category["children"]])
                @endif
            </li>
        @endforeach
    </ul>
@endcan

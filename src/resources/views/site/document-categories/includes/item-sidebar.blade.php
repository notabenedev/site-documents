    <div class="list-group-item documents-sidebar__item">
        <div>
            @if ($item->children->count() && !$item->nested)
                <a href="{{ route($route, ["category" => $item]) }}"
                   class="documents-sidebar__link documents-sidebar__link_drop
                        {{ $item->id === $category->id || $item->id === $category->parent_id
                        || (isset($category->parent->parent_id) && $item->id === $category->parent->parent_id)
                        || (isset($category->parent->parent) && $item->id === $category->parent->parent->parent_id)
                        || (isset($category->parent->parent->parent) && $item->id === $category->parent->parent->parent->parent_id)
                        || (isset($category->parent->parent->parent->parent) && $item->id === $category->parent->parent->parent->parent->parent_id) ? " documents-sidebar__link_active" : "" }}
                           ">
                    {{ $item->title }}
                    <i class="fas fa-caret-down"></i>
                </a>
            @else
                <a href="{{ route($route, ["category" => $item]) }}"
                   class="documents-sidebar__link{{ $item->id === $category->id ? " documents-sidebar__link_active" : "" }}">
                    {{ $item->title }}
                </a>
            @endif
        </div>
        @if ($item->children->count()
        && ($item->id === $category->id || $item->id === $category->parent_id
            || (isset($category->parent->parent_id) && $item->id === $category->parent->parent_id)
            || (isset($category->parent->parent) && $item->id === $category->parent->parent->parent_id)
            || (isset($category->parent->parent->parent) && $item->id === $category->parent->parent->parent->parent_id)
            || (isset($category->parent->parent->parent->parent) && $item->id === $category->parent->parent->parent->parent->parent_id)
            )
        && !$item->nested)
            <div class="documents-sidebar__item_children" id="collapse-{{ $item->id }}">
                    @foreach ($item->children as $child)
                            @include('site-documents::site.document-categories.includes.item-sidebar', ['item' => $child])
                 @endforeach
            </div>
        @endif
    </div>

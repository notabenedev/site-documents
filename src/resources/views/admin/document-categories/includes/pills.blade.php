@if (! empty($category))
    @include("site-documents::admin.document-categories.includes.breadcrumb")
@endif
<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                @can("viewAny", \App\DocumentCategory::class)
                    <li class="nav-item">
                        <a href="{{ route("admin.document-categories.index") }}"
                           class="nav-link{{ isset($isTree) && !$isTree ? " active" : "" }}">
                            {{ config("site-documents.sitePackageName") }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.document-categories.index') }}?view=tree"
                           class="nav-link{{ isset($isTree) && $isTree ? " active" : "" }}">
                            Приоритет
                        </a>
                    </li>
                @endcan

                @empty($category)
                    @can("create", \App\DocumentCategory::class)
                        <li class="nav-item">
                            <a href="{{ route("admin.document-categories.create") }}"
                               class="nav-link{{ $currentRoute === "admin.document-categories.create" ? " active" : "" }}">
                                Добавить
                            </a>
                        </li>
                    @endcan
                @else
                    @can("create", \App\DocumentCategory::class)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $currentRoute == 'admin.document-categories.create-child' ? " active" : "" }}"
                               data-toggle="dropdown"
                               href="#"
                               role="button"
                               aria-haspopup="true"
                               aria-expanded="false">
                                Добавить
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('admin.document-categories.create') }}">
                                    Основную
                                </a>
                                @if ($category->nesting < config("site-documents.documentCategoriesNest"))
                                    <a class="dropdown-item"
                                       href="{{ route('admin.document-categories.create-child', ['category' => $category]) }}">
                                        Подкатегорию
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endcan

                    @can("view", $category)
                        <li class="nav-item">
                            <a href="{{ route("admin.document-categories.show", ["category" => $category]) }}"
                               class="nav-link{{ $currentRoute === "admin.document-categories.show" ? " active" : "" }}">
                                Просмотр
                            </a>
                        </li>
                    @endcan

                    @can("update", $category)
                        <li class="nav-item">
                            <a href="{{ route("admin.document-categories.edit", ["category" => $category]) }}"
                               class="nav-link{{ $currentRoute === "admin.document-categories.edit" ? " active" : "" }}">
                                Редактировать
                            </a>
                        </li>
                    @endcan

                    @can("viewAny", \App\Meta::class)
                        <li class="nav-item">
                            <a href="{{ route("admin.document-categories.metas", ["category" => $category]) }}"
                               class="nav-link{{ $currentRoute === "admin.document-categories.metas" ? " active" : "" }}">
                                Метатеги
                            </a>
                        </li>
                    @endcan

{{--                    @can("viewAny", \App\Document::class)--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route("admin.document-categories.prices.index", ["category" => $category]) }}"--}}
{{--                               class="nav-link{{ strstr($currentRoute, "prices.") !== false ? " active" : "" }}">--}}
{{--                                {{ config("site-documents.sitePricesName") }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endcan--}}

                    @can("delete", $category)
                        <li class="nav-item">
                            <button type="button" class="btn btn-link nav-link"
                                    data-confirm="{{ "delete-form-group-{$category->id}" }}">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </button>
                            <confirm-form :id="'{{ "delete-form-group-{$category->id}" }}'">
                                <template>
                                    <form action="{{ route('admin.document-categories.destroy', ['category' => $category]) }}"
                                          id="delete-form-group-{{ $category->id }}"
                                          class="btn-group"
                                          method="post">
                                        @csrf
                                        @method("delete")
                                    </form>
                                </template>
                            </confirm-form>
                        </li>
                    @endcan
                @endif
            </ul>
        </div>
    </div>
</div>
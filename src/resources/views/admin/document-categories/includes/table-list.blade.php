<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Заголовок</th>
            <th>Адресная строка</th>
            <th>Дочерние</th>
            @if(! config("site-documents.onePage", false))
                <th>Раскрыть</th>
            @endif
            @canany(["edit", "view", "delete"], \App\DocumentCategory::class)
                <th>Действия</th>
            @endcanany
        </tr>
        </thead>
        <tbody>
        @foreach ($categories as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->slug }}</td>
                <td>{{ $item->children->count() }}</td>
                @if(! config("site-documents.onePage", false))
                    <td>{{ $item->nested ? "Да" : "Нет" }}</td>
                @endif
                @canany(["edit", "view", "delete"], \App\DocumentCategory::class)
                    <td>
                        <div role="toolbar" class="btn-toolbar">
                            <div class="btn-group mr-1">
                                @can("update", \App\DocumentCategory::class)
                                    <a href="{{ route("admin.document-categories.edit", ["category" => $item]) }}" class="btn btn-primary">
                                        <i class="far fa-edit"></i>
                                    </a>
                                @endcan
                                @can("view", \App\DocumentCategory::class)
                                    <a href="{{ route('admin.document-categories.show', ["category" => $item]) }}" class="btn btn-dark">
                                        <i class="far fa-eye"></i>
                                    </a>
                                @endcan
                                @can("delete", \App\DocumentCategory::class)
                                    <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$item->id}" }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>

                        @can("delete", \App\DocumentCategory::class)
                            <confirm-form :id="'{{ "delete-form-{$item->id}" }}'">
                                <template>
                                    <form action="{{ route('admin.document-categories.destroy', ["category" => $item]) }}"
                                          id="delete-form-{{ $item->id }}"
                                          class="btn-group"
                                          method="post">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </template>
                            </confirm-form>
                        @endcan
                    </td>
                @endcanany
            </tr>
        @endforeach
        <tr class="text-center">
            @canany(["edit", "view", "delete"], \App\DocumentCategory::class)
                <td colspan="4">
            @else
                <td colspan="3">
            @endcanany

                </td>
        </tr>
        </tbody>
    </table>
</div>
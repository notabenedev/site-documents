<form action="{{ route("site.document-search.index") }}" method="get" class="d-flex flex-row my-auto">
    <input class="form-control"
           type="search"
           placeholder="Ввеедите для поиска"
           name="search"
           value="{{ $request->get("search") }}"
           aria-label="Поиск">
    <button type="submit" class="btn btn-outline-primary">
        <i class="fas fa-search " aria-hidden="true"></i>
    </button>
</form>
@extends("admin.layout")

@section("page-title", "{$category->title} - ")

@section('header-title', "{$category->title}")

@section('admin')
    @include("site-documents::admin.document-categories.includes.pills")
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.document-categories.update", ["category" => $category]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method("put")

                    <div class="form-group">
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               name="title"
                               required
                               value="{{ old("title", $category->title) }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Адресная строка</label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old("slug", $category->slug) }}"
                               class="form-control @error("slug") is-invalid @enderror">
                        @error("slug")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="short">Краткое описание</label>
                        <input type="text"
                               id="short"
                               name="short"
                               value="{{ old("short", $category->short) }}"
                               class="form-control @error("short") is-invalid @enderror">
                        @error("short")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea class="form-control tiny @error("description") is-invalid @enderror"
                                  name="description"
                                  id="description"
                                  rows="3">{{ old('description', $category->description) }}</textarea>
                        @error("description")
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="info">Информация</label>
                        <textarea class="form-control tiny @error("info") is-invalid @enderror"
                                  name="info"
                                  id="info"
                                  rows="3">{{ old('info', $category->info) }}</textarea>
                        @error("info")
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    @if(! config("site-documents.onePage", false))
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input"
                                       type="checkbox"
                                       {{ ( old('nested', $category->nested)) ? "checked" : "" }}
                                       value="true"
                                       id="nested"
                                       name="nested">
                                <label class="custom-control-label" for="nested">
                                    Раскрыть все вложенные группы
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<div class="documents-category__item documents-category__item_level-{{ $level }}"  id="{{ $item["slug"]}}">
    <h{{$level+1}} class="h{{$level+1}} documents-category__title documents-category__title_level-{{ $level }}">{{ $item["title"] }}</h{{$level+1}}>
    @isset($item['short'])
        <div class="documents-category__short">
            {!! $item['short']  !!}
        </div>
    @endisset
    <ul class="list-unstyled">
        @foreach(\Notabenedev\SiteDocuments\Facades\DocumentActions::getModelDocumentsIds("App\DocumentCategory",$item["id"]) as $id => $document)
            <li>
                {!! $document->getTeaser() !!}
            </li>
        @endforeach
    </ul>
    @isset($item['description'])
        <div class="documents-category__description">
            {!! $item['description']  !!}
        </div>
    @endisset

    @isset($item['info'])
        <div class="documents-category__info">
            {!! $item['info'] !!}
        </div>
    @endisset

    @if (! empty($item["children"]))
        @foreach ($item["children"] as $child)
            @include("site-documents::site.document-categories.includes.item", ["item" => $child, "first" => false, "level" => $level + 1])
        @endforeach
    @endif
</div>




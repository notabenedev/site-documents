<div class="documents-menu">
    <ul class="list-unstyled">
        @foreach($rootCategories as $key => $item)
            <li class="documents-menu__item">
                <a class="documents-menu__item-link" href="#{{ $item["slug"] }}">{{ $item["title"] }}</a>
            </li>
        @endforeach
    </ul>
</div>


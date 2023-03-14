 <ul class="list-unstyled documents-menu documents-menu_onepage">
     @foreach($rootCategories as $key => $item)
         <li class="documents-menu__item">
             <a class="documents-menu__item-link" href="#{{ $item["slug"] }}">{{ $item["title"] }}</a>
         </li>
     @endforeach
</ul>



@php
    $active = (strstr($currentRoute, "admin.document-categories.") !== false) ||
              (strstr($currentRoute, "admin.douments.") !== false);
@endphp

@if ($theme == "sb-admin")
    <li class="nav-item {{ $active ? " active" : "" }}">
        @can("viewAny", \App\DocumentCategory::class)
            <a href="{{ route("admin.document-categories.index") }}"
               class="nav-link{{ strstr($currentRoute, ".document-categories.") !== false ? " active" : "" }}">
                <i class="fas fa-file"></i>
                <span>{{ config("site-documents.sitePackageName") }}</span>
            </a>
        @endcan
    </li>
@else
    <li class="nav-item dropdown">
        @can("viewAny", \App\DocumentCategory::class)
            <a href="{{ route("admin.document-categories.index") }}"
               class="nav-link">
                {{ config("site-documents.sitePackageName") }}
            </a>
        @endcan
    </li>
@endif

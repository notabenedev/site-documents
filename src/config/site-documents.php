<?php

return [
    "sitePackageName" => "Документы",
    "siteDocumentCategoriesName" => "Категории документов",
    "siteDocumentsName" => "Файлы",
    "documentCategoryUrlName" => "documents",
    "documentUrlName" => "document",

    "siteBreadcrumbs" => true,
    "siteShowSearchForm" => true,
    "onePage" => false,
    "onePageSidebar" => false,
    "documentCategoriesNest" => 3,

    "documentCategoryAdminRoutes" => true,
    "documentCategorySiteRoutes" => true,
    "documentAjaxRoutes" => true,
    "documentSignatureAjaxRoutes" => true,
    "documentSiteRoutes" => true,
    "documentSignatureSiteRoutes" => true,
    "documentSearchRoutes" => true,
    "documentCategoryFacade" => \Notabenedev\SiteDocuments\Helpers\DocumentCategoryActionsManager::class,
    "documentFacade" => \Notabenedev\SiteDocuments\Helpers\DocumentActionsManager::class,
    "documentModels" => array(
        "documentCategory" => \App\DocumentCategory::class,
    )
];
<?php
return [
    "sitePackageName" => "Документы",
    "siteDocumentCategoriesName" => "Категории документов",
    "siteDocumentsName" => "Файлы",
    "documentCategoryUrlName" => "documents",
    "documentUrlName" => "file",

    "siteBreadcrumbs" => true,
    "onePage" => false,
    "documentCategoriesNest" => 3,

    "documentCategoryAdminRoutes" => true,
    "documentCategorySiteRoutes" => true,
    "documentCategoryFacade" => \Notabenedev\SiteDocuments\Helpers\DocumentCategoryActionsManager::class,
];
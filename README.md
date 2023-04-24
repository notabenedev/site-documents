## Site Documents an DocumentCategories

## Конфиг
    php artisan vendor:publish --provider="Notabenedev\SiteDocuments\SiteDocumentsServiceProvider" --tag=config

## Install
    php artisan migrate
    php artisan vendor:publish --provider="Notabenedev\SiteDocuments\SiteDocumentsServiceProvider" --tag=public --force
    php artisan make:documents
        {--all : Run all}
        {--models : Export models}
        {--policies : Export and create rules}
        {--only-default : Create default rules}
        {--controllers : Create only default rules}
        {--observers : Export observers}
        {--vue : Export vue}
        {--scss : Export scss}
        {--menu : create admin menu}

## Log
    v0.0.8 search:
            - php artisan make:documents --controllers
    v0.0.7 document-categories show: nestet btns - blocks
    v0.0.6 document-categories show: description first
    v0.0.5 document-categories show: accordion to nested
    v0.0.4 document-categories index: onePageSidebar config
    v0.0.3 Styles
            - php artisan vendor:publish --provider="Notabenedev\SiteDocuments\SiteDocumentsServiceProvider" --tag=public --force
    v0.0.2 add onePageSidebar config:
            - php artisan vendor:publish --provider="Notabenedev\SiteDocuments\SiteDocumentsServiceProvider" --tag=config
## Description
    

## Config
     
    "sitePackageName" => Название пакета,
    "siteDocumentCategoriesName" =>  Название категорий документов
    "siteDocumentsName" => Название документов
    "documentCategoryUrlName" => url категорий документов
    "documentUrlName" => url документов
    "siteBreadcrumbs" => хлебыные крошки (true |false)
    "documentCategoriesNest" => вложенность категорий документов (число),
    "onePage" => true, false - Ракрытие всех документов на одной странице:
        перед изменением настройки:
      - php artisan down
      - открыть url главной страницы раздела
      - изменить настройку
      - php artisan up
      - открыть url главной страницы раздела
    
    "documentModels" - модели для привязки документов - array("documentCategory" => \App\DocumentCategory::class, ..)
   

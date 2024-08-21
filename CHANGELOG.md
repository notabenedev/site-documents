## Log
    v1.0.0 base-settings 5 (bootstrap 5)
    - Обновлен компонент DocumentComponent
    - Обновлены стили
    - Обновлены шаблоны: admin.document-categories.includes.pills, site.document-categories.show, site.documents.teaser

    Обновление

         php artisan vendor:publish --provider="Notabenedev\SiteDocuments\SiteDocumentsServiceProvider" --tag=public --force

    v0.1.3 fix order documents on model page
        - Проверить переопределение Admin\DocumentController -> updateOrder
    v0.1.2 item-sidebar.blade.php (раскрытие родителя) & documents/teaser.blade.php (доп иконки для документов в виде картинок)
    v0.1.1 Base 4.0
    v0.1.0 SiteDocumentsServiceProvider & sidebar.blade.php fix
    v0.0.9 DocumentCategory meta fix: 
            - проверить переопределение Site/DocumentCategoryController.php
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
   

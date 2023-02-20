## Site Documents an DocumentCategories

## Конфиг
    php artisan vendor:publish --provider="Notabenedev\SiteDocuments\SiteDocumentsServiceProvider" --tag=config

## Install
    php artisan migrate
    php artisan vendor:publish --provider="Notabenedev\SiteGroupPrice\SiteGroupPriceProvider" --tag=public --force
    php artisan make:documents
          {--all : Run all}
          {--models : Export models}
          {--policies : Export and create rules}
          {--only-default : Create default rules}

## Description
    

## Config
     
    "sitePackageName" => Название пакета,
    "siteDocumentCategoriesName" =>  Название категорий документов
    "siteDocumentsName" => Название документов
    "documentCategoryUrlName" => url категорий документов
    "documentUrlName" => url документов

    "documentCategoriesNest" => число (вложенность категорий документов),
    "onePage" => true, false - Ракрытие всех документов на одной странице:
     (перед изменением настройки:
      - php artisan down
      - открыть url главной страницы раздела
      - изменить настройку
      - php artisan up
      - открыть url главной страницы раздела
     
   

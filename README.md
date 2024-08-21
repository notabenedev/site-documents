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

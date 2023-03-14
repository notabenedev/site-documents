<?php

namespace Notabenedev\SiteDocuments;

use App\Document;
use App\DocumentCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Notabenedev\SiteDocuments\Console\Commands\SiteDocumentsMakeCommand;
use Notabenedev\SiteDocuments\Events\DocumentCategoryChangePosition;
use Notabenedev\SiteDocuments\Listeners\DocumentCategoryIdsInfoClearCache;
use Notabenedev\SiteDocuments\Observers\DocumentCategoryObserver;
use Notabenedev\SiteDocuments\Observers\DocumentObserver;

class SiteDocumentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Публикация конфигурации
        $this->publishes([__DIR__.'/config/site-documents.php' => config_path('site-documents.php'),
        ], 'config');

        // Перенос миграций
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        //Console
        if ($this->app->runningInConsole()){
            $this->commands([
                SiteDocumentsMakeCommand::class,
            ]);
        }

        //Подключаем роуты
        if (config("site-documents.documentCategoryAdminRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/admin/document-category.php");
        }
        if (config("site-documents.documentSignatureAjaxRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/ajax/document-signature.php");
        }
        if (config("site-documents.documentAjaxRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/ajax/document.php");
        }
        if (config("site-documents.documentCategorySiteRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/site/document-category.php");
        }
        if (config("site-documents.documentSignatureSiteRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/site/document-signature.php");
        }
        if (config("site-documents.documentSiteRoutes")) {
            $this->loadRoutesFrom(__DIR__."/routes/site/document.php");
        }

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'site-documents');
        if (! config("site-group-price.onePage", false)) {
            view()->composer("site-documents::site.document-categories.includes.sidebar", function (View $view){
                $view->with("categoriesTree", DocumentCategory::getTree());
            });
        }
        // Подключение метатегов.
        $seo = app()->config["seo-integration.models"];
        $seo["documentCategories"] = DocumentCategory::class;
        app()->config["seo-integration.models"] = $seo;

        // Events
        $this->addEvents();

        // Наблюдатели.
        $this->addObservers();

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/site-documents'),
        ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/site-documents.php','site-documents'
        );
        $this->initFacades();
    }

    /**
     * Подключение Facade.
     */
    protected function initFacades()
    {
        $this->app->singleton("document-category-actions", function () {
            $class = config("site-documents.documentCategoryFacade");
            return new $class;
        });
        $this->app->singleton("document-actions", function () {
            $class = config("site-documents.documentFacade");
            return new $class;
        });
    }

    /**
     * Подключение Events.
     */

    protected function addEvents()
    {
        // Изменение позиции категории.
        $this->app["events"]->listen(DocumentCategoryChangePosition::class, DocumentCategoryIdsInfoClearCache::class);
    }

    /**
     * Добавление наблюдателей.
     */
    protected function addObservers()
    {
        if (class_exists(DocumentCategoryObserver::class) && class_exists(DocumentCategory::class)) {
            DocumentCategory::observe(DocumentCategoryObserver::class);
        }
        if (class_exists(DocumentObserver::class) && class_exists(Document::class)) {
            Document::observe(DocumentObserver::class);
        }
    }
}
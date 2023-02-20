<?php

namespace Notabenedev\SiteDocuments;

use Illuminate\Support\ServiceProvider;
use Notabenedev\SiteDocuments\Console\Commands\SiteDocumentsMakeCommand;

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
    }


}
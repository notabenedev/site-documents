<?php

namespace Notabenedev\SiteDocuments\Console\Commands;

use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class SiteDocumentsMakeCommand extends BaseConfigModelCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:documents
     {--all : Run all}
     {--models : Export models}
     {--policies : Export and create rules} 
     {--only-default : Create only default rules}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Settings for documents and document_categories';

    /**
     * Vendor Name
     * @var string
     *
     */
    protected $vendorName = 'Notabenedev';

    /**
     * Package Name
     * @var string
     *
     */
    protected $packageName = 'SiteDocuments';

    /**
     * The models to  be exported
     * @var array
     */
    protected $models = ["DocumentCategory"];


    /**
     * Policies
     * @var array
     *
     */
    protected $ruleRules = [
        [
            "title" => "Категории документов",
            "slug" => "documentCategory",
            "policy" => "DocumentCategoryPolicy",
        ],
    ];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all = $this->option("all");


        if ($this->option("models") || $all) {
            $this->exportModels();
        }

        if ($this->option("policies") || $all) {
            $this->makeRules();
        }
    }
}

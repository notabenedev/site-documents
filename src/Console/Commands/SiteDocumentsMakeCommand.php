<?php

namespace Notabenedev\SiteDocuments\Console\Commands;

use App\Menu;
use App\MenuItem;
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
     {--only-default : Create only default rules}
     {--controllers : Create controllers}
     {--observers : Export observers}
     {--vue : Export vue}
     {--menu : Config menu}
     ';

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
    protected $models = ["DocumentCategory", "Document", "DocumentSignature"];


    /**
     * Policies
     * @var array
     *
     */
    protected $ruleRules = [
        [
            "title" => "Категории документов",
            "slug" => "document-categories",
            "policy" => "DocumentCategoryPolicy",
        ],
        [
            "title" => "Документы",
            "slug" => "documents",
            "policy" => "DocumentPolicy",
        ],
        [
            "title" => "Подписи к документам",
            "slug" => "document-signatures",
            "policy" => "DocumentSignaturePolicy",
        ],
    ];


    /**
     * Make Controllers
     */
    protected $controllers = [
        "Admin" => ["DocumentCategoryController", "DocumentController"],
        "Site" => ["DocumentController"],
    ];


    /**
     * Создание наблюдателей
     *
     * @var array
     */
    protected $observers = ["DocumentCategoryObserver"];

    /**
     * Vue files folder
     *
     * @var string
     */
    protected $vueFolder = "site-documents";

    /**
     * Vue files list
     *
     * @var array
     */
    protected $vueIncludes = [
        'admin' => [
            'admin-document-category-list' => "DocumentCategoryListComponent",
            'documents-loader' => "DocumentComponent",
            'document-signature-loader' => "DocumentSignatureComponent"
        ],
        'app' => [],
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

        if ($this->option("controllers") || $all) {
            $this->exportControllers("Admin");
            $this->exportControllers("Site");
        }

        if ($this->option("observers") || $all) {
            $this->exportObservers();
        }

        if ($this->option("vue") || $all) {
            $this->makeVueIncludes("admin");
        }

        if ($this->option('menu') || $all) {
            $this->makeMenu();
        }

    }

    /**
     * Создать меню.
     */
    protected function makeMenu()
    {
        try {
            $menu = Menu::query()->where("key", "admin")->firstOrFail();
        }
        catch (\Exception $exception) {
            return;
        }

        $title = config("site-documents.sitePackageName");
        $itemData = [
            "title" => $title,
            "menu_id" => $menu->id,
            "url" => "#",
            "template" => "site-documents::admin.menu",
        ];

        try {
            $menuItem = MenuItem::query()
                ->where("menu_id", $menu->id)
                ->where("title", "$title")
                ->firstOrFail();
            $this->info("Menu item '{$title}' not updated");
        }
        catch (\Exception $exception) {
            MenuItem::create($itemData);
            $this->info("Menu item '{$title}' was created");
        }
    }
}

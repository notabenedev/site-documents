<?php

namespace Notabenedev\SiteDocuments\Facades;

use Illuminate\Support\Facades\Facade;
use Notabenedev\SiteDocuments\Helpers\DocumentCategoryActionsManager;

/**
 *
 * Class DocumentCategoryActions
 * @package Notabenedev\SiteDocuments\Facades
 *
 *
 */
class DocumentCategoryActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "document-category-actions";
    }
}
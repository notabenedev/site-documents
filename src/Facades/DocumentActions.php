<?php

namespace Notabenedev\SiteDocuments\Facades;

use Illuminate\Support\Facades\Facade;
use Notabenedev\SiteDocuments\Helpers\DocumentActionsManager;

/**
 *
 * Class DocumentCategoryActions
 * @package Notabenedev\SiteDocuments\Facades
 *
 */
class DocumentActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "document-actions";
    }
}
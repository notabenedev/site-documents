<?php

namespace Notabenedev\SiteDocuments\Listeners;

use Notabenedev\SiteDocuments\Events\DocumentCategoryChangePosition;
use Notabenedev\SiteDocuments\Facades\DocumentCategoryActions;

class DocumentCategoryIdsInfoClearCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DocumentCategoryChangePosition $event)
    {
        $category = $event->category;
        // Очистить список id категорий.
        DocumentCategoryActions::forgetCategoryChildrenIdsCache($category);
        DocumentCategoryActions::forgetCategoryParentsCache($category);
    }
}

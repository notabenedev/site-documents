<?php

namespace Notabenedev\SiteDocuments\Helpers;

use App\Document;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Notabenedev\SiteDocuments\Facades\DocumentCategoryActions;

class DocumentActionsManager
{
    /**
     * Получить id документов категории, либо  категории и подкатегории.
     *
     * @param string $modelName
     * @param int $modelId
     * @param $includeSubs
     * @return mixed
     */
    public function getModelDocumentsIds($modelName, $modelId, $includeSubs = false)
    {
        $model = Document::getDocumentModel($modelName, $modelId);
        $modelShort =  substr($modelName, strrpos($modelName, '\\') + 1);

        $key = "document-actions-get{$modelShort}Documents:{$modelId}";
        $key .= $includeSubs ? "-true" : "-false";
        return Cache::rememberForever($key, function() use ($modelName, $modelId, $model, $includeSubs) {
            $query = Document::query()
                ->orderBy("priority");
            if ($includeSubs && $modelName=="App\DocumentCategory") {
                $query->where("documentable_type", $modelName);
                $query->whereIn("documentable_id", DocumentCategoryActions::getCategoryChildren($model, true));
            }
            else {
                $query->where("documentable_type", $modelName);
                $query->where("documentable_id", $modelId);
            }
            $documents = $query->get();

            $items = [];
            foreach ($documents as $item) {
                $items[$item->id] = $item;
            }

            return $items;
        });
    }

    /**
     * Очистить кэш
     *
     * @param $modelShort
     * @param $modelId
     * @param $model
     * @return void
     */
    public function forgetModelDocumentsIds($modelShort, $modelId, $model)
    {
        $key = "document-actions-get{$modelShort}Documents:{$modelId}";

        Cache::forget("$key-true");
        Cache::forget("$key-false");
        if (isset($model->parent_id) && ! empty($model->parent_id)) {
            $this->forgetModelDocumentsIds($modelShort, $model->parent->id,$model->parent);
        }
    }

}
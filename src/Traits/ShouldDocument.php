<?php

namespace Notabenedev\SiteDocuments\Traits;

use App\Document;
use Illuminate\Http\Request;

trait ShouldDocument
{
    protected static function bootShouldDocument()
    {
        static::deleting(function($model) {
             // Чистим документы
            $model->clearDocuments();
        });
    }

    /**
     * Документы.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function documents() {
        return $this->morphMany(Document::class, 'documentable')->orderBy("priority");
    }


    /**
     * Удалить все
     */
    public function clearDocuments()
    {
        foreach ($this->documents()->get() as $document) {
            $document->delete();
        }
    }
}
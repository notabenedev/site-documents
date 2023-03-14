<?php

namespace Notabenedev\SiteDocuments\Observers;

use App\Document;
use Notabenedev\SiteDocuments\Facades\DocumentActions;

class DocumentObserver
{

    /**
     * После сохранения
     *
     * @param Document $document
     */
    public function created(Document $document){
        $model = Document::getDocumentModel($document->documentable_type, $document->documentable_id);
        $modelShort =  substr($document->documentable_type, strrpos($document->documentable_type, '\\') + 1);
        DocumentActions::forgetModelDocumentsIds($modelShort, $document->documentable_id, $model);
    }


    /**
     * После обновления.
     *
     * @param Document $document
     */
    public function updated(Document $document)
    {
        $model = Document::getDocumentModel($document->documentable_type, $document->documentable_id);
        $modelShort =  substr($document->documentable_type, strrpos($document->documentable_type, '\\') + 1);
        DocumentActions::forgetModelDocumentsIds($modelShort, $document->documentable_id, $model);
    }

    /**
     * После удаления.
     *
     * @param Document $document
     */
    public function deleted(Document $document)
    {
        $model = Document::getDocumentModel($document->documentable_type, $document->documentable_id);
        $modelShort =  substr($document->documentable_type, strrpos($document->documentable_type, '\\') + 1);
        DocumentActions::forgetModelDocumentsIds($modelShort, $document->documentable_id, $model);
    }

}

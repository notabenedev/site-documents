<?php

namespace Notabenedev\SiteDocuments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelName = "";
        foreach (config('site-documents.documentModels') as $name => $model) {
            if ($this->documentable_type == $model) {
                $modelName = $name;
                break;
            }
        }

        return [
            "id" => $this->id,
            "priority" => $this->priority,

            "title" => $this->title,
            "titleChanged" => $this->title,
            "titleInput" => false,
            "slug" => $this->slug,
            "slugChanged" => $this->slug,
            "slugInput" => false,
            "description" => $this->description,
            "descriptionChanged" => $this->description,
            "descriptionInput" => false,
            "updateUrl" => route("admin.vue.documents.update", [
                "model" => $modelName,
                "id" => $this->documentable_id,
                "document" => $this->slug,
            ]),

            "deleteUrl" => route("admin.vue.documents.delete", [
                "model" => $modelName,
                "id" => $this->documentable_id,
                "document" => $this->slug,
            ]),
            "downloadUrl" => $this->show_url,
            "signatureShowUrl" => route("admin.vue.document-signatures.show", ["id" => $this->id]),
            "signatureStoreUrl" => route("admin.vue.document-signatures.store", ["id" => $this->id]),
        ];
    }
}

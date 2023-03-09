<?php

namespace Notabenedev\SiteDocuments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentSignatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,

            "title" => $this->title,
            "titleChanged" => $this->title,
            "titleInput" => false,
            "person" => $this->person,
            "personChanged" => $this->person,
            "personInput" => false,
            "position" => $this->position,
            "positionChanged" => $this->position,
            "positionInput" => false,
            "organization" => $this->organization,
            "organizationChanged" => $this->organization,
            "organizationInput" => false,
            "date" => $this->date,
            "dateChanged" => $this->date,
            "dateInput" => false,
            "certificate" => $this->certificate,
            "certificateChanged" => $this->certificate,
            "certificateInput" => false,
            "issued" => $this->issued,
            "issuedChanged" => $this->issued,
            "issuedInput" => false,
            "period" => $this->period,
            "periodChanged" => $this->period,
            "periodInput" => false,

            "updateUrl" => route("admin.vue.document-signatures.update", [
                "id" => $this->document_id,
                "signature" => $this->id,
            ]),
            "deleteUrl" => route("admin.vue.document-signatures.delete", [
                "id" => $this->document_id,
                "signature" => $this->id,
            ]),
            "downloadUrl" => $this->show_url,
        ];
    }
}

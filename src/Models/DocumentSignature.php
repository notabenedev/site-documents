<?php

namespace Notabenedev\SiteDocuments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Notabenedev\SiteDocuments\Http\Resources\DocumentSignatureResource;


class DocumentSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "path",
        "person",
        "position",
        "date",
        "organization",
        "certificate",
        "issued",
        "period",
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $model) {
            Storage::delete($model->path);
        });
    }


    /**
     * Получить документ.
     *
     * @param $id
     * @return bool
     */
    public static function getDocumentModel($id)
    {
        try{
            $document = Document::findOrFail($id);
        }
        catch (\Exception $e){
            return false;
        }
        return $document;
    }


    /**
     * Вывод подписей
     *
     * @param Document $document
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function forRender($document)
    {
        $collection = $document->signatures()->get();
        return DocumentSignatureResource::collection($collection);
    }

    /**
     * Signature document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function document(){
        return $this->belongsTo(\App\Document::class);
    }

    /**
     * Ссылка на скачивание.
     *
     * @return string
     */
    public function getShowUrlAttribute()
    {
        return route("site.documents.sig.show", [
            "sig" => $this
        ]);
    }


}

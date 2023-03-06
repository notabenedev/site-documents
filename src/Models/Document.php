<?php

namespace Notabenedev\SiteDocuments\Models;

use Notabenedev\SiteDocuments\Http\Resources\DocumentResource as DocResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PortedCheese\BaseSettings\Traits\ShouldSlug;


class Document extends Model
{
    use HasFactory, ShouldSlug;

    protected $fillable = [
        "title",
        "slug",
        "path",
        "description",
        "priority"
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $model) {
            Storage::delete($model->path);
        });
    }

    /**
     * Ссылка на скачивание.
     *
     * @return string
     */
    public function getShowUrlAttribute()
    {
        return route("site.documents.show", [
            "document" => $this
        ]);
    }

    /**
     * Изменить дату создания.
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return datehelper()->changeTz($value);
    }

    /**
     * Вывод документов.
     *
     * @param $model
     * @return mixed
     */
    public static function forRender($model)
    {
        $collection = $model->documents;
        return DocResource::collection($collection);
    }


    /**
     * Получить следующий вес.
     */
    public function setMax()
    {
        $max = self::query()
            ->where("documentable_type", $this->documentable_type)
            ->where("documentable_id", $this->documentable_id)
            ->max("priority");
        $this->priority = $max + 1;
        $this->save();
    }


    /**
     * Тизер.
     *
     * @return array|string
     * @throws \Throwable
     */
    public function getTeaser()
    {
        $ext = mb_strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
        $view = view("site.documents.includes.document",
            [
                "document" => $this,
                "size" => Storage::exists($this->path)? Storage::size($this->path) : "0",
                "lightbox" => ($ext === "jpg" || $ext === "jpeg" || $ext === "png" || $ext === "webp") ? $ext : false,
                "download" => $ext === "pdf" ? false : true,
            ]);
        return $view->render();
    }

    /**
     * Найти модель по имени в конфиге.
     *
     * @param $modelName
     * @param $id
     * @return bool
     */
    public static function getDocumentModel($modelName, $id)
    {
        $model = false;
        foreach (config('site-documents.documentModels') as $name => $class) {
            if (
                $name == $modelName &&
                class_exists($class)
            ) {
                try {
                    $model = $class::findOrFail($id);
                } catch (\Exception $e) {
                    return false;
                }
                break;
            }
        }
        return $model;
    }

}

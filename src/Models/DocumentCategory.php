<?php

namespace Notabenedev\SiteDocuments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PortedCheese\BaseSettings\Traits\ShouldSlug;
use PortedCheese\SeoIntegration\Traits\ShouldMetas;

class DocumentCategory extends Model
{
    use HasFactory, ShouldSlug, ShouldMetas;

    protected $fillable = [
        "title",
        "slug",
        "short",
        "description",
        "info",
        "nested",
    ];
    protected $metaKey = "documentCategories";

    protected static function booting() {

        parent::booting();
    }

    /**
     * Родительская категория
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(\App\DocumentCategory::class, "parent_id");
    }

    /**
     * Дочерние категории.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(\App\DocumentCategory::class, "parent_id")->orderBy("priority");

    }

    /**
     * Уровень вложенности.
     *
     * @return int
     */
    public function getNestingAttribute()
    {
        if (empty($this->parent_id)) {
            return 1;
        }
        return $this->parent->nesting + 1;
    }


    /**
     * Model's tree
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     *
     */
    public static function getTree(){
        $query = self::query();
        return $query
            ->whereNull("parent_id")
            ->orderBy("priority")
            ->with("children")
            ->get();
    }
}

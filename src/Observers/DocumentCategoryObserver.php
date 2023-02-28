<?php

namespace Notabenedev\SiteDocuments\Observers;

use App\DocumentCategory;
use Notabenedev\SiteDocuments\Events\DocumentCategoryChangePosition;
use PortedCheese\BaseSettings\Exceptions\PreventActionException;
use PortedCheese\BaseSettings\Exceptions\PreventDeleteException;

class DocumentCategoryObserver
{

    /**
     * Перед сохранением
     *
     * @param DocumentCategory $category
     */
    public function creating(DocumentCategory $category){
        if (isset($category->parent_id)) {
            $max = DocumentCategory::query()
                ->where("parent_id", $category->parent_id)
                ->max("priority");
        }
        else
            $max = DocumentCategory::query()
                ->whereNull("parent_id")
                ->max("priority");

        $category->priority = $max +1;
    }

    /**
     * После создания.
     *
     * @param DocumentCategory $category
     */
    public function created(DocumentCategory $category)
    {
        event(new DocumentCategoryChangePosition($category));
    }

    /**
     * Перед обновлением.
     *
     * @param DocumentCategory $category
     * @throws PreventActionException
     */
    public function updating(DocumentCategory $category)
    {
        $original = $category->getOriginal();
        if (isset($original["parent_id"]) && $original["parent_id"] !== $category->parent_id) {
            $this->categoryChangedParent($category, $original["parent_id"]);
        }

    }
    /**
     * После создания.
     *
     * @param DocumentCategory $category
     */
    public function updated(DocumentCategory $category)
    {
        if (isset($category->parent))
        $this->categoryChangedParent($category, $category->parent->id);
        else
            $this->categoryChangedParent($category, "");
    }

    /**
     * Перед удалением
     *
     * @param DocumentCategory $category
     * @throws PreventDeleteException
     */
    public function deleting(DocumentCategory $category){
        if ($category->children->count()){
            throw new PreventDeleteException("Невозможно удалить категорию, у нее есть подкатегории");
        }
//        if ($category->documents->count()){
//            throw new PreventDeleteException("Невозможно удалить категорию, у нее есть элементы");
//        }
    }

    /**
     * Очистить список id дочерних категорий.
     *
     * @param DocumentCategory $category
     * @param $parent
     */
    protected function categoryChangedParent(DocumentCategory $category, $parent)
    {
        if (! empty($parent)) {
            $parent = DocumentCategory::find($parent);
            event(new DocumentCategoryChangePosition($parent));
        }
        event(new DocumentCategoryChangePosition($category));
    }

}

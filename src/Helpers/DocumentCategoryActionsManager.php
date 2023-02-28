<?php

namespace Notabenedev\SiteDocuments\Helpers;

use App\DocumentCategory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use PhpParser\Comment\Doc;

class DocumentCategoryActionsManager
{

    /**
     * Список всех категорий.
     *
     * @return array
     */
    public function getAllList()
    {
        $categories = [];
        foreach (DocumentCategory::all()->sortBy("title") as $item) {
            $categories[$item->id] = "$item->title ({$item->slug})";
        }
        return $categories;
    }

    /**
     * Получить дерево категорий.
     *
     * @param bool $forJs
     * @return array
     */
    public function getTree()
    {
        list($tree, $noParent) = $this->makeTreeDataWithNoParent();
        $this->addChildren($tree);
        $this->clearTree($tree, $noParent);
        return $this->sortByPriority($tree);
    }

    /**
     * Сохранить порядок.
     *
     * @param array $data
     * @return bool
     */
    public function saveOrder(array $data)
    {
        try {
            $this->setItemsWeight($data, 0);
        }
        catch (\Exception $exception) {
            return false;
        }
        return true;
    }


    /**
     * Сохранить порядок.
     *
     * @param array $items
     * @param int $parent
     */
    protected function setItemsWeight(array $items, int $parent)
    {
        foreach ($items as $priority => $item) {
            $id = $item["id"];
            if (! empty($item["children"])) {
                $this->setItemsWeight($item["children"], $id);
            }
            $parentId = ! empty($parent) ? $parent : null;
            // Обновление Категории.
            $category = DocumentCategory::query()
                ->where("id", $id)
                ->first();
            $category->priority = $priority;
            $category->parent_id = $parentId;
            $category->save();
        }
    }

    /**
     * Сортировка результата.
     *
     * @param $tree
     * @return array
     */
    protected function sortByPriority($tree)
    {
        $sorted = array_values(Arr::sort($tree, function ($value) {
            return $value["priority"];
        }));
        foreach ($sorted as &$item) {
            if (! empty($item["children"])) {
                $item["children"] = self::sortByPriority($item["children"]);
            }
        }
        return $sorted;
    }

    /**
     * Очистить дерево от дочерних.
     *
     * @param $tree
     * @param $noParent
     */
    protected function clearTree(&$tree, $noParent)
    {
        foreach ($noParent as $id) {
            $this->removeChildren($tree, $id);
        }
    }

    /**
     * Убрать подкатегории.
     *
     * @param $tree
     * @param $id
     */
    protected function removeChildren(&$tree, $id)
    {
        if (empty($tree[$id])) {
            return;
        }
        $item = $tree[$id];
        foreach ($item["children"] as $key => $child) {
            $this->removeChildren($tree, $key);
            if (! empty($tree[$key])) {
                unset($tree[$key]);
            }
        }
    }

    /**
     * Добавить дочернии элементы.
     *
     * @param $tree
     */
    protected function addChildren(&$tree)
    {
        foreach ($tree as $id => $item) {
            if (empty($item["parent"])) {
                continue;
            }
            $this->addChild($tree, $item, $id);
        }
    }

    /**
     * Добавить дочерний элемент.
     *
     * @param $tree
     * @param $item
     * @param $id
     * @param bool $children
     */
    protected function addChild(&$tree, $item, $id, $children = false)
    {
        // Добавление к дочерним.
        if (! $children) {
            $tree[$item["parent"]]["children"][$id] = $item;
        }
        // Обновление дочерних.
        else {
            $tree[$item["parent"]]["children"][$id]["children"] = $children;
        }

        $parent = $tree[$item["parent"]];
        if (! empty($parent["parent"])) {
            $items = $parent["children"];
            $this->addChild($tree, $parent, $parent["id"], $items);
        }
    }

    /**
     * Получить данные по категориям.
     *
     * @return array
     */
    protected function makeTreeDataWithNoParent()
    {
        $categories = DB::table("document_categories")
            ->select("id", "title", "slug", "short", "description", "info", "parent_id","priority", "nested")
            ->orderBy("parent_id")
            ->get();

        $tree = [];
        $noParent = [];
        foreach ($categories as $category) {
            $tree[$category->id] = [
                "title" => $category->title,
                'slug' => $category->slug,
                'short' => $category->short,
                'description' => $category->description,
                'info' => $category->info,
                'parent' => $category->parent_id,
                "priority" => $category->priority,
                "id" => $category->id,
                'children' => [],
                "url" => route("admin.document-categories.show", ['category' => $category->slug]),
                "siteUrl" => route("site.document-categories.show", ["category" => $category->slug]),
            ];
            if (empty($category->parent_id)) {
                $noParent[] = $category->id;
            }
        }
        return [$tree, $noParent];
    }

    /**
     * Получить id всех подкатегорий.
     *
     * @param DocumentCategory $category
     * @param bool $includeSelf
     * @return array
     */
    public function getCategoryChildren(DocumentCategory $category, $includeSelf = false)
    {
        $key = "document-category-actions-getCategoryChildren:{$category->id}";
        $children = Cache::rememberForever($key, function () use ($category) {
            $children = [];
            $collection = DocumentCategory::query()
                ->select("id")
                ->where("parent_id", $category->id)
                ->get();
            foreach ($collection as $child) {
                $children[] = $child->id;
                $categories = $this->getCategoryChildren($child);
                if (! empty($categories)) {
                    foreach ($categories as $id) {
                        $children[] = $id;
                    }
                }
            }
            return $children;
        });
        if ($includeSelf) {
            $children[] = $category->id;
        }
        return $children;
    }

    /**
     * Очистить кэш списка id категорий.
     *
     * @param DocumentCategory $category
     */
    public function forgetCategoryChildrenIdsCache(DocumentCategory $category)
    {
        Cache::forget("category-actions-getCategoryChildren:{$category->id}");
        $parent = $category->parent;
        if (! empty($parent)) {
            $this->forgetCategoryChildrenIdsCache($parent);
        }
    }

    /**
     * Admin breadcrumbs
     *
     * @param DocumentCategory $category
     * @param bool $isDocument
     * @return array
     *
     */
    public function getAdminBreadcrumb(DocumentCategory $category, $isDocument = false)
    {
        $breadcrumb = [];
        if (! empty($category->parent)) {
            $breadcrumb = $this->getAdminBreadcrumb($category->parent);
        }
        else {
            $breadcrumb[] = (object) [
                "title" => config("site-documents.sitePackageName"),
                "url" => route("admin.document-categories.index"),
                "active" => false,
            ];
        }
        $routeParams = Route::current()->parameters();
        $isDocument = $isDocument && ! empty($routeParams["document"]);
        $active = ! empty($routeParams["category"]) &&
            $routeParams["category"]->id == $category->id &&
            ! $isDocument;
        $breadcrumb[] = (object) [
            "title" => $category->title,
            "url" => route("admin.document-categories.show", ["category" => $category]),
            "active" => $active,
        ];
        if ($isDocument) {
            $document = $routeParams["document"];
            $breadcrumb[] = (object) [
                "title" => $document->title,
                "url" => route("admin.documents.show", ["document" => $document]),
                "active" => true,
            ];
        }
        return $breadcrumb;
    }

    /**
     * Site breadcrumbs
     *
     * @param DocumentCategory $category
     * @param bool $isPagePage
     * @return array
     *
     */
    public function getSiteBreadcrumb(DocumentCategory $category)
    {
        $breadcrumb = [];
        if (! empty($category->parent)) {
            $breadcrumb = $this->getSiteBreadcrumb($category->parent);
        }
        else {
            $breadcrumb[] = (object) [
                "title" => config("site-documents.sitePackageName"),
                "url" => route("site.document-categories.index"),
                "active" => false,
            ];
        }
        $routeParams = Route::current()->parameters();
        $active = ! empty($routeParams["category"]) &&
            $routeParams["category"]->id == $category->id;
        $breadcrumb[] = (object) [
            "title" => $category->title,
            "url" => route("site.document-categories.show", ["category" => $category]),
            "active" => $active,
        ];

        return $breadcrumb;
    }

    /**
     * Get root categories
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */

    public function getRootCategories(){

        $rootCategories = DocumentCategory::query()
            ->whereNull("parent_id")
            ->orderBy("priority")
            ->get();

        return $rootCategories;
    }

    /**
     * Получить дерево подкатегорий.
     *
     * @param DocumentCategory $category
     * @return array
     */
    public function getChildrenTree(DocumentCategory $category)
    {
        list($tree, $noParent) = $this->makeChildrenTreeData($category);
        $this->addChildren($tree);
        $this->clearTree($tree, $noParent);
        return $tree;
    }

    /**
     * Получить данные по категориям.
     *
     * @return array
     */
    protected function makeChildrenTreeData(DocumentCategory $parent)
    {
        $childrenIds = self::getCategoryChildren($parent);
        $categories = DB::table("document_categories")
            ->select("id", "title", "slug", "short", "description", "info", "parent_id", "priority", "nested")
            ->whereIn('id', $childrenIds)
            ->orderBy("parent_id")
            ->orderBy('priority')
            ->get();

        $tree = [];
        $noParent = [];
        foreach ($categories as $category) {
            $tree[$category->id] = [
                "title" => $category->title,
                'slug' => $category->slug,
                'short' => $category->short,
                'description' => $category->description,
                'info' => $category->info,
                'parent' => $category->parent_id,
                "priority" => $category->priority,
                "id" => $category->id,
                'children' => [],
                "url" => route("admin.document-categories.show", ['category' => $category->slug]),
                "siteUrl" => route("site.document-categories.show", ["category" => $category->slug]),
            ];
            if ($parent->id == $category->parent_id) {
                $noParent[] = $category->id;
            }
        }
        return [$tree, $noParent];
    }

    /**
     * Получить id всех родителей.
     *
     * @param DocumentCategory $category
     * @return array
     */
    public function getCategoryParents(DocumentCategory $category)
    {
        $key = "document-category-actions-getCategoryParents:{$category->id}";
        $parentsIds = Cache::rememberForever($key, function () use ($category) {
            $parentsIds = [];
            $collection = DocumentCategory::query()
                ->select("id")
                ->where("id", $category->parent->id)
                ->get();
            foreach ($collection as $parent) {
                $parentsIds[] = $parent->id;
                $categories = $this->getCategoryParents($parent);
                if (! empty($categories)) {
                    foreach ($categories as $id) {
                        $parentsIds[] = $id;
                    }
                }
            }
            return $parentsIds;
        });

        return $parentsIds;
    }
    /**
     * Очистить кэш списка id родителей.
     *
     * @param DocumentCategory $category
     */
    public function forgetCategoryParentsCache(DocumentCategory $category)
    {
        Cache::forget("document-category-actions-getCategoryParents:{$category->id}");
        $parent = $category->parent;
        if (! empty($parent)) {
            $this->forgetCategoryParentsCache($parent);
        }
    }
}
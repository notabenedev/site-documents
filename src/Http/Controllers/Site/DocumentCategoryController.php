<?php

namespace Notabenedev\SiteDocuments\Http\Controllers\Site;

use App\DocumentCategory;
use App\Http\Controllers\Controller;
use App\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Notabenedev\SiteDocuments\Facades\DocumentCategoryActions;


class DocumentCategoryController extends Controller
{
    /**
     *
     * Show first or first child
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     *
     */
    public function index()
    {
        if (config("site-documents.onePage", true)) {

            $siteBreadcrumb = null;

            if (config("site-documents.siteBreadcrumbs")){
                $siteBreadcrumb = [
                    (object) [
                        'active' => true,
                        'url' => route("site.document-categories.index"),
                        'title' => config("site-documents.sitePackageName"),
                    ]
                ];
            }

            $categories = DocumentCategoryActions::getTree();
            $pageMetas = Meta::getByPageKey(config("site-documents.documentCategoryUrlName"));

            return view("site-documents::site.document-categories.index", [
                "rootCategories" =>  DocumentCategoryActions::getRootCategories(),
                "categories" => $categories,
                "siteBreadcrumb" => $siteBreadcrumb,
                "pageMetas" =>  $pageMetas,
            ]);

        }
        else {

            try {
                $category = DocumentCategory::query()
                    ->whereNull("parent_id")
                    ->orderBy("priority")
                    ->firstOrFail();
            }

            catch (\Exception $exception) {
                abort(404);
                $category = null;
            }

            $child = $category->children()->orderBy("priority")->first();
            if ($child) {
                $category = $child;
            }

            return redirect()
                ->route("site.document-categories.show",
                    ["category" => $category]);

        }

    }

    /**
     * Show category
     *
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     */
    public function show(DocumentCategory $category){

        if (config("site-documents.onePage", false)) {
            return redirect()
                ->route("site.document-categories.index", [], 301);
        }
        else{

            $siteBreadcrumb = null;

            if (config("site-documents.siteBreadcrumbs")){
                $siteBreadcrumb = DocumentCategoryActions::getSiteBreadcrumb($category);
            }
            $childrenTree = DocumentCategoryActions::getChildrenTree($category);
            $pageMetas = Meta::getByModelKey($category);
            return view("site-documents::site.document-categories.show", [
                "category" => $category,
                "childrenTree" => $childrenTree,
                "siteBreadcrumb" => $siteBreadcrumb,
                "pageMetas" => $pageMetas,
            ]);
        }
    }
}

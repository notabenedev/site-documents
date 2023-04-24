<?php

namespace Notabenedev\SiteDocuments\Http\Controllers\Site;

use App\Document;
use App\DocumentCategory;
use App\Http\Controllers\Controller;
use App\Meta;
use Illuminate\Http\Request;
use Notabenedev\SiteDocuments\Facades\DocumentCategoryActions;


class DocumentSearchController extends Controller
{

    public function index(Request $request){

        $pageMetas = Meta::getByPageKey(config("site.document-categories.index")."-search");
        $siteBreadcrumb = null;
        $categories = DocumentCategoryActions::getTree();

        if (config("site-documents.siteBreadcrumbs")){
            $siteBreadcrumb = [
                (object) [
                    'active' => false,
                    'url' => route("site.document-categories.index"),
                    'title' => config("site-documents.sitePackageName"),
                ],
                (object) [
                    'active' => true,
                    'url' => route("site.document-search.index"),
                    'title' => "Поиск",
                ]
            ];
        }

        $search = $request->get("search", "");
        $findDocuments = null;
        $findCategories = null;


        if ($search) {
            $query = Document::query()->select();
            if (! empty($search)){
                $query->where("title", "like", "%$search%")
                    ->orWhere("description", "like", "%$search%");
            }
            $findDocuments = $query->paginate()->appends($request->input());

            $query = DocumentCategory::query();
            if (! empty($search)) {
                $query->where("title", "like", "%$search%")
                    ->orWhere("description", "like", "%$search%");
            }
            $findCategories = $query->get();

        }

        return view("site-documents::site.search",
            compact("request", "categories","findDocuments", "findCategories", "siteBreadcrumb", "pageMetas"));
    }

}

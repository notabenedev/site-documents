<?php

namespace Notabenedev\SiteDocuments\Http\Controllers\Admin;

use App\DocumentCategory;
use Notabenedev\SiteDocuments\Facades\DocumentCategoryActions;
use App\Http\Controllers\Controller;
use App\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentCategoryController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(DocumentCategory::class, "category");
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $view = $request->get("view","default");
        $isTree = $view == "tree";
        if ($isTree) {
            $categories = DocumentCategoryActions::getTree();
        }
        else {
            $collection = DocumentCategory::query()
                ->whereNull("parent_id")
                ->orderBy("priority","asc");
            $categories = $collection->get();
        }
        return view("site-documents::admin.document-categories.index", compact("categories", "isTree"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param DocumentCategory|null $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(DocumentCategory $category = null)
    {
        return view("site-documents::admin.document-categories.create", [
            "category" => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param DocumentCategory|null $category
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     *
     */
    public function store(Request $request,  DocumentCategory $category = null)
    {
        if(!$request->has('nested'))
            $request->merge(['nested' => false]);
        else
            $request->merge(['nested' => true]);

        $this->storeValidator($request->all());
        if (empty($category)) {
            $item = DocumentCategory::create($request->all());
        }
        else {
            $item = $category->children()->create($request->all());
        }

        return redirect()
            ->route("admin.document-categories.show", ["category" => $item])
            ->with("success", "Добавлено");
    }


    /**
     * Validator
     *
     * @param array $data
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function storeValidator(array $data)
    {
        Validator::make($data, [
            "title" => ["required", "max:150"],
            "slug" => ["nullable", "max:150", "unique:document_categories,slug"],
            "short" => ["nullable", "max:500"],
            "info" => ["nullable"],
            "nested" => ["nullable"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Адресная строка",
            "short" => "Краткое описание",
            "info" => "Информация",
            "nested" => "Раскрыть Вложенные",
        ])->validate();
    }


    /**
     * Display the specified resource.
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(DocumentCategory $category)
    {
        $childrenCount = $category->children->count();
        if ($childrenCount) {
            $children = $category->children()->orderBy("priority")->get();
        }
        else {
            $children = false;
        }
        return view("site-documents::admin.document-categories.show", [
            "category" => $category,
            "childrenCount" => $childrenCount,
            "children" => $children
        ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(DocumentCategory $category)
    {
        return view("site-documents::admin.document-categories.edit", compact("category"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DocumentCategory $category
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, DocumentCategory $category)
    {
        if(!$request->has('nested'))
            $request->merge(['nested' => false]);
        else
            $request->merge(['nested' => true]);

        $this->updateValidator($request->all(), $category);
        $category->update($request->all());

        return redirect()
            ->route("admin.document-categories.show", ["category" => $category])
            ->with("success", "Успешно обновлено");
    }

    /**
     * Update validate
     *
     * @param array $data
     * @param DocumentCategory $category
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function updateValidator(array $data, DocumentCategory $category)
    {
        $id = $category->id;
        Validator::make($data, [
            "title" => ["required", "max:150"],
            "slug" => ["nullable", "max:150", "unique:document_categories,slug,{$id}"],
            "short" => ["nullable", "max:500"],
            "info" => ["nullable"],
            "nested" => ["nullable"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Адресная строка",
            "short" => "Краткое описание",
            "info" => "Информация",
            "nested" => "Раскрыть Вложенные",
        ])->validate();
    }


    /**
     *  Remove the specified resource from storage.
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DocumentCategory $category)
    {
        $parent = $category->parent;

        $category->delete();
        if ($parent) {
            return redirect()
                ->route("admin.document-categories.show", ["category" => $parent])
                ->with("success", "Успешно удалено");
        }
        else {
            return redirect()
                ->route("admin.document-categories.index")
                ->with("success", "Успешно удалено");
        }
    }

    /**
     * Add metas
     *
     * @param DocumentCategory $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function metas(DocumentCategory $category)
    {
        $this->authorize("viewAny", Meta::class);
        $this->authorize("update", $category);

        return view("site-documents::admin.document-categories.metas", [
            'category' => $category,
        ]);
    }

    /**
     * Изменить приоритет
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeItemsPriority(Request $request)
    {
        $data = $request->get("items", false);
        if ($data) {
            $result = DocumentCategoryActions::saveOrder($data);
            if ($result) {
                return response()
                    ->json("Порядок сохранен");
            }
            else {
                return response()
                    ->json("Ошибка, что то пошло не так");
            }
        }
        else {
            return response()
                ->json("Ошибка, недостаточно данных");
        }
    }
}

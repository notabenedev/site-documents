<?php

namespace Notabenedev\SiteDocuments\Http\Controllers\Admin;

use App\Document;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Notabenedev\SiteDocuments\Facades\DocumentActions;

class DocumentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(Document::class, "document");
    }
    /**
     * Список документов.
     *
     * @param $model
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function list($model, $id)
    {
        $modelObj = Document::getDocumentModel($model, $id);
        if (! $modelObj) {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Model not found!",
                ]);
        }
        return response()
            ->json([
                "error" => false,
                "success" => true,
               "documents" => Document::forRender($modelObj),
            ]);
    }

    /**
     *
     * Загрузить документ.
     *
     * @param Request $request
     * @param $model
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     *
     */
    public function store(Request $request, $model, $id)
    {
        $this->storeValidator($request->all());
        $modelObj = Document::getDocumentModel($model, $id);
        if (! $modelObj) {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Model not found",
                ]);
        }
        $file = $request->file("file");
        $name = Str::random(40);
        $ext = $file->getClientOriginalExtension();
        while(Storage::exists("documents/$model/{$name}.{$ext}")) {
            $name = Str::random(40);
        }
        $path = $request->file("file")->storeAs("documents/$model", "{$name}.{$ext}");
        $document = Document::create([
            "path" => $path,
            "title" => $request->get("title", "$model-$id"),
            "slug" => $request->get("slug", null ),
            "description" => $request->get("description", null),
        ]);
        $modelObj->documents()->save($document);
        $document->setMax();
        return response()
            ->json([
                "success" => true,
                "files" => Document::forRender($modelObj),
            ]);
    }

    /**
     * * Валидация загрузки.
     *
     * @param $data
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function storeValidator($data)
    {
        Validator::make($data, [
            "file" => ["bail", "required", "file"],
            "title" => ["bail", "required"],
            "slug" => ["nullable", "max:150", "unique:documents,slug"],
        ], [
            "file.required" => "Файл не найден",
        ], [
            "file" => "Файл",
            "title" => "Заголовок",
            "slug" => "Slug",
        ])->validate();
    }

    /**
     * Обновление веса.
     * @param Request $request
     * @param $model
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateOrder(Request $request, $modelShort, $modelId)
    {
        Validator::make($request->all(), [
            "documents" => ["required", "array"],
        ], [], [
            "documents" => "Документы",
        ])->validate();

        $ids = $request->get("documents", []);
        foreach ($ids as $weight => $id) {
            DB::table("documents")
                ->where("id", $id)
                ->update(["priority" => $weight]);
        }
        $model =  Document::getDocumentModel($modelShort, $modelId);
        DocumentActions::forgetModelDocumentsIds(ucfirst($modelShort), $model->id, $model);
        return response()
            ->json([
                "success" => true,
                "message" => "Success"
            ]);
    }

    /**
     * Обновить заголовок.
     *
     * @param Request $request
     * @param $model
     * @param $id
     * @param Document $document
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $model, $id, Document $document)
    {
        $modelObj = Document::getDocumentModel($model, $id);
        if (! $modelObj) {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Model not found",
                ]);
        }

        Validator::make($request->all(), [
            "title" => ["bail", "required"],
            "slug" => ["nullable", "max:150", "unique:document_categories,slug,{$id}"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Slug",
        ])->validate();

        $document->title = $request->get("title");
        $document->description = $request->get("description");
        $document->slug = $request->get("slug");
        $document->save();

        return response()
            ->json([
                "success" => true,
                "files" => Document::forRender($modelObj),
            ]);
    }

    /**
     * Удаление.
     *
     * @param $model
     * @param $id
     * @param Document $document
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($model, $id, Document $document)
    {
        $modelObj = Document::getDocumentModel($model, $id);
        if (! $modelObj) {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Model not found",
                ]);
        }
        $document->delete();

        return response()
            ->json([
                "success" => true,
                "message" => "Success",
                "files" => Document::forRender($modelObj),
            ]);
    }
}

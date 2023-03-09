<?php

namespace Notabenedev\SiteDocuments\Http\Controllers\Admin;

use App\Document;
use App\DocumentSignature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DocumentSignatureController extends Controller
{
    /**
     * Список подписей.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $modelObj = DocumentSignature::getDocumentModel($id);
        if (! $modelObj) {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Model not found",
                ]);
        }
        return response()
            ->json([
                "success" => true,
                "files" => DocumentSignature::forRender($modelObj),
            ]);
    }

    /**
     * Загрузить подпись
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $id)
    {
        $this->storeValidator($request->all());
        $modelObj = Document::findOrFail($id);
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
        if ($ext !== "sig") {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Выберите файл подписи с разрешением .sig",
                ]);
        }
        while(Storage::exists("documents/sig/{$name}.{$ext}")) {
            $name = Str::random(40);
        }
        $path = $request->file("file")->storeAs("documents/sig", "{$name}.{$ext}");
        $sig = DocumentSignature::create([
            "path" => $path,
            "title" => $request->get("title", "$modelObj-$id"),
            "person" => $request->get("person", null),
            "position" => $request->get("position", null),
            "organization" => $request->get("organization", null),
            "date" => $request->get("date", null),
            "certificate" => $request->get("certificate", null),
            "issued" => $request->get("issued", null),
            "period" => $request->get("period", null),
        ]);
        $modelObj->signatures()->save($sig);
        return response()
            ->json([
                "success" => true,
                "files" => DocumentSignature::forRender($modelObj),
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
        ], [
            "file.required" => "Файл не найден",
        ], [
            "file" => "Файл",
            "title" => "Заголовок",
        ])->validate();
    }


    /**
     * Обновить заголовок.
     *
     * @param Request $request
     * @param $id
     * @param DocumentSignature $signature
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request,$id, DocumentSignature $signature)
    {
        $model = Document::findOrFail($id);
        if (! $model) {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Model not found",
                ]);
        }
        Validator::make($request->all(), [
            "title" => ["bail", "required"],
        ], [], [
            "title" => "Заголовок",
        ])->validate();

        $signature->title = $request->get("title");
        $signature->person = $request->get("person");
        $signature->position = $request->get("position");
        $signature->organization = $request->get("organization");
        $signature->date = $request->get("date");
        $signature->certificate = $request->get("certificate");
        $signature->issued = $request->get("issued");
        $signature->period = $request->get("period");
        $signature->save();

        return response()
            ->json([
                "success" => true,
                "files" => DocumentSignature::forRender($model),
            ]);
    }

    /**
     * Удаление.
     *
     * @param $model
     * @param $id
     * @param DocumentSignature $signature
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id, DocumentSignature $signature)
    {
        $model = Document::findOrFail($id);
        if (! $model) {
            return response()
                ->json([
                    "success" => false,
                    "message" => "Model not found",
                ]);
        }
        $signature->delete();

        return response()
            ->json([
                "success" => true,
                "message" => "Success",
                "files" => DocumentSignature::forRender($model),
            ]);
    }
}

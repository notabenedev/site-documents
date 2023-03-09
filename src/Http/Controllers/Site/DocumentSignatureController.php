<?php

namespace Notabenedev\SiteDocuments\Http\Controllers\Site;

use App\DocumentSignature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Storage;

class DocumentSignatureController extends Controller
{
    /**
     * Show file.
     *
     * @param DocumentSignature $sig
     * @return IlluminateResponse
     */
    public function show(DocumentSignature $sig)
    {
        $filePath = $sig->path;
        $exploded = explode('.', $filePath);
        $ext = end($exploded);
        $content = Storage::get($filePath);

        // define mime type
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);
        if ($mime != "application/pdf") {
            $document = $sig->document;
            $title =  $document->title;
            $documentExt = pathinfo($document->path, PATHINFO_EXTENSION);
            $fileName = "$title.$documentExt.{$ext}";
            $file = Storage::disk("local")->put($fileName, $content);
            if (! $file) {
                abort(404);
            }
            return response()->download(storage_path("app/" . $fileName))->deleteFileAfterSend();
        }

        // respond with 304 not modified if browser has the image cached
        $etag = md5($content);
        $not_modified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag;
        $content = $not_modified ? NULL : $content;
        $status_code = $not_modified ? 304 : 200;

        // return http response
        return new IlluminateResponse($content, $status_code, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age=0, public',
            'Content-Length' => strlen($content),
            'Etag' => $etag,
        ));
    }
}

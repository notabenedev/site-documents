<?php

namespace Notabenedev\SiteDocuments\Http\Controllers\Site;

use App\Document;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     *
     * @param Document $document
     * @return IlluminateResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show(Document $document)
    {
        if ($document->path){
            $filePath = $document->path;
            $exploded = explode('.', $filePath);
            $ext = end($exploded);
            $content = Storage::get($filePath);

            // define mime type
            $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);
            if ($mime != "application/pdf") {
                $title =  $document->title;
                $fileName = "$title.{$ext}";
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
        else
            return abort(404);

    }
}

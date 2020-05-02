<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function up(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('public/upload');
        $url = Storage::url($path);

        $size = Storage::size($path);
        $mime_type = $file->getMimeType();
        $image_mime_type = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp'];
        $info = array();
        $type = 2;
        if (in_array($mime_type, $image_mime_type)) {
            $type = 1;
            $info = $file->getFileInfo();
        }

        $upload = new Upload();
        $upload->url = $url;
        $upload->path = $path;
        $upload->in_local = 1;
        $upload->size = $size;
        $upload->type = $type;
        $upload->info = \GuzzleHttp\json_encode($info);
        $upload->save();

        return $this->_output_success('文件路径', ['path' => $path, 'url' => $url, 'id' => $upload->id]);
    }
}

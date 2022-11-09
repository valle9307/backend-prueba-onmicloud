<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
trait FilesTrait
{
    public function putFile(Request $request,$disk)
    {
        $file     = $request->file('file');
        $fileName = time().$request->file('file')->getClientOriginalName();

        $fileNameStorage = Storage::disk($disk)->put($fileName,$file);

        return Storage::disk($disk)->url($fileNameStorage);
    }
}

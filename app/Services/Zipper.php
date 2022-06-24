<?php

namespace App\Services;

use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class Zipper
{

    public static function createZipof($filename)
    {




        $zip = new ZipArchive();

        $zipFileName =  storage_path('app/public/temp/' . now()->timestamp . '-task.zip');
        if ($zip->open($zipFileName, ZipArchive::CREATE) == true) {
            $filePath =  storage_path('app/public/temp/' . $filename);
            $zip->addFile($filePath, $filename);
        }
        $zip->close();

        return $zipFileName;
    }
}

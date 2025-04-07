<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileUploader
{
    public function uploadFile($requestFile, $folderName, $inputName = 'files')
    {
        try {
            if ($requestFile) {
                $dir = 'public/files/' . $folderName;
                $fileName = $requestFile->getClientOriginalName();
                $fixName = time() . '-' . $fileName;

                Storage::putFileAs($dir, $requestFile, $fixName);

                return 'files/' . $folderName . '/' . $fixName;
            }

            return null;
        } catch (\Throwable $th) {
            report($th);

            return $th->getMessage();
        }
    }

    // delete file
    public function deleteFile($fileName = 'files')
    {
        try {
            if ($fileName) {
                Storage::delete('public/files/' . $fileName);
            }

            return true;
        } catch (\Throwable $th) {
            report($th);

            return $th->getMessage();
        }
    }

}

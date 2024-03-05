<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait StorageFileTrait
{
    public function fileRemove($file){
        if (Storage::disk('dr_amr')->exists($file)) {
            Storage::disk('dr_amr')->delete($file);
        }
    }
}

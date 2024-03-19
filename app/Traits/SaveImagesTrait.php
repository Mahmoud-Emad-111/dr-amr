<?php

namespace App\Traits;


trait SaveImagesTrait
{
    /**
     * Save images
     */
    public function saveImage($image, string $name)
    {
        $full=$image->store($name , 'dr_amr');
        return $full;
    }


}

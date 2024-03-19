<?php

namespace App\Traits;

use App\Http\Resources\IDResource;
use App\Models\Articles;

trait RandomIDTrait
{
    /**
     * Set random ID for articles
     */
    public function RandomID()
    {
        return rand(1,100).time();
    }

    // Get Real ID
    public function getRealID($model, int $ID){

        $id=$model::where('random_id',$ID)->get('id');
        return $id[0];
    }


}

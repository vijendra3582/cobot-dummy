<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $table="subscribe";
    protected $guarded=['id']; 

    public function getCreatedAtAttribute($created_at)
    {
        return date('m/d/Y H:i', strtotime($created_at));
    }
}

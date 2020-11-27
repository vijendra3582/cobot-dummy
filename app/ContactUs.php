<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = "contact_us";
    protected $guarded = ["id"];


    public function getCreatedAtAttribute($created_at)
    {
        return date('m/d/Y H:i', strtotime($created_at));
    }
}

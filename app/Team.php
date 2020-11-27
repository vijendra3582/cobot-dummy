<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table="team";
    protected $guarded=['id'];

    protected $appends = ['image_disp'];

    public function getImageDispAttribute(){ 
        return  !empty($this->image) ? asset('/team-uploads/'.$this->image) : '';
    }

    const ACTIVE = 1;
    const INACTIVE= 0;
    const DELETED = 2;
}

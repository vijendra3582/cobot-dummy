<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceDisclosure extends Model
{
    protected $table="resource_content_disclosure";
    protected $guarded=['id'];

    protected $appends = ['banner_img_disp'];

    public function getBannerImgDispAttribute(){
    	return !empty($this->banner_img) ? asset('/resource-upload/'.$this->banner_img) : '';
    }
}

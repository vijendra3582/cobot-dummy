<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table="resources";
    protected $guarded=['id'];

    const ACTIVE = 1;
    const INACTIVE = 0;
    const DELETED = 2;

    protected $appends = ['resource_file_link','video_file_link','video_image_link'];

    public function getResourceFileLinkAttribute(){
    	return !empty($this->resource_file) ? asset('/resource-upload/'.$this->resource_file) : '';
    }

    public function getVideoFileLinkAttribute(){
    	return !empty($this->video_file) ? asset('/resource-upload/'.$this->video_file) :'';
    }

    public function getVideoImageLinkAttribute(){
    	return !empty($this->video_image) ? asset('/resource-upload/'.$this->video_image) : '';
    }
}

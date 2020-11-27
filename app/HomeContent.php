<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    protected $table = "home_content";
    protected $guarded = ["id"];

    public function delete()
	{
		if(file_exists(public_path('/home-content-uploads/'.$this->banner_img))){
            @unlink(public_path('/home-content-uploads/'.$this->banner_img));
        }

        if(file_exists(public_path('/home-content-uploads/'.$this->catalog_file))){
            @unlink(public_path('/home-content-uploads/'.$this->catalog_file));
        }
        parent::delete();
	}
}

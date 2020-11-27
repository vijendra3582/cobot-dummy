<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = "general_setting";
    protected $guarded = ["id"];

    protected $appends = ['map_img_disp', 'map_background_img_disp'];
    public function getMapImgDispAttribute(){
    	return !empty($this->map_img) ? asset(config('constants.upload_folder').$this->map_img) : '';
    }

    public function getMapBackgroundImgDispAttribute(){
    	return !empty($this->map_background_img) ? asset(config('constants.upload_folder').$this->map_background_img) : '';
    }
}

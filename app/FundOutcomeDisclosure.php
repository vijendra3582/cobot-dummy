<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundOutcomeDisclosure extends Model
{
    protected $table='fund_outcome_disclosure';
    protected $guarded=['id'];

    protected $appends = ['banner_img_disp'];

    public function getBannerImgDispAttribute(){
    	return !empty($this->banner_img) ? asset('/fund-upload/'.$this->banner_img) : '';
    }
}

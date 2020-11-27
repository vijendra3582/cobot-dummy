<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterimFund extends Model
{
    //
    const DELETED = 2;
    protected $table = "interim_fund";
    protected $guarded = ["id"];

    protected $appends = ['banner_image_disp'];
    
    public function getBannerImageDispAttribute(){
    	return !empty($this->banner_image) ? asset('/interim-fund-upload/'.$this->banner_image) : '';
    }

    /*************/
    public function fundData(){
        return $this->hasMany('App\InterimFundData','fund_id', 'id')->where('status', "<>", config('constants.const_deleted'))->orderBy('position','ASC');
    }

    public function fundFiles(){
        return $this->hasMany('App\InterimFundFiles','fund_id','id')->where('status', "<>", config('constants.const_deleted'))->orderBy('position','ASC');
    }


}

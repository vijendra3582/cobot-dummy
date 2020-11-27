<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterimFundFiles extends Model
{
    //
    protected $table = "interim_fund_files";
    protected $guarded = ["id"];

    protected $appends = ['file_link','file_disp','file_extension'];
	public function getFileLinkAttribute(){
    	return !empty($this->file_path) ? asset('/interim-fund-upload/'.$this->file_path) : '';
    }

    public function getFileDispAttribute(){
    	return !empty($this->file_path) ? $this->file_path : '';
    }
    public function getFileExtensionAttribute(){
    	return !empty($this->file_type) ? $this->file_type : '';
    }
}

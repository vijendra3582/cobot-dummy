<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundFiles extends Model
{
	protected $table="fund_files";
	protected $guarded = ['id'];

    protected $appends = ['file_link','file_disp','file_extension'];
	public function getFileLinkAttribute(){
    	return !empty($this->file_path) ? asset('/fund-upload/'.$this->file_path) : '';
    }

    public function getFileDispAttribute(){
    	return !empty($this->file_path) ? $this->file_path : '';
    }
    public function getFileExtensionAttribute(){
    	return !empty($this->file_type) ? $this->file_type : '';
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table="news";
    protected $guarded=['id'];

    const ACTIVE = 1;
    const INACTIVE = 0;
    const DELETED = 2;

    protected $appends = ['news_file_link','video_file_link','video_image_link','news_image_link'];

    public function getNewsFileLinkAttribute(){
    	return !empty($this->news_file) ? asset('/news-upload/'.$this->news_file) : '';
    }

    public function getVideoFileLinkAttribute(){
    	return !empty($this->video_file) ? asset('/news-upload/'.$this->video_file) :'';
    }

    public function getVideoImageLinkAttribute(){
    	return !empty($this->video_image) ? asset('/news-upload/'.$this->video_image) : '';
    }
    public function getNewsImageLinkAttribute(){
        return !empty($this->news_image) ? asset('/news-upload/'.$this->news_image) : '';
    }
    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsDisclosure extends Model
{
    protected $table = "news_content_disclosure";
    protected $guarded = ['id'];

    protected $appends = ['banner_img_disp'];

    public function getBannerImgDispAttribute()
    {
        return !empty($this->banner_img) ? asset('/news-upload/' . $this->banner_img) : '';
    }
}

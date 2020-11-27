<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageResizer extends Controller
{
    
    private $originalFile = '';
    private $imageType='';
    public function __construct($originalFile = '',$imageType) {
        $this->originalFile = $originalFile;
        $this->imageType = $imageType;
    }
    public function resize($wt = "", $ht = "", $targetFile = "", $resize_type = "") {
        if(trim($resize_type) == "") {
            if (empty($ht) || empty($wt) || empty($targetFile)) {
                return false;
            }
            if($this->imageType == "image/jpeg" || $this->imageType == "image/jpg" || $this->imageType == "image/pjpeg")
            {
                $src = imagecreatefromjpeg($this->originalFile);
            }
            if($this->imageType == "image/gif")
            {
                $src = imagecreatefromgif($this->originalFile);
            }
            if($this->imageType == "image/png")
            {
                $src = imagecreatefrompng($this->originalFile);
            }
            
            list($width, $height) = getimagesize($this->originalFile);
            
            if($width>=$height)
            {
                $newWidth = $wt;
                $newHeight = ($height / $width) * $newWidth;
            
                if($newHeight>$ht)
                {
                    $newHeight = $ht;
                    $newWidth = ($width/$height ) * $newHeight;
                }
                
                $lastHeight = $newHeight;
                $lastWidth = $newWidth;
            
            }
            else if($height>=$width)
            {
                $newHeight = $ht;
                $newWidth = ($width / $height) * $newHeight;
                
                if($newWidth>$wt)
                {
                    $newWidth = $wt;
                    $newHeight = ($height/$width) * $newWidth;
                }
                
                $lastHeight = $newHeight;
                $lastWidth = $newWidth;
            }
            //$newHeight = ($height / $width) * $newWidth;
            
            $tmp = imagecreatetruecolor($lastWidth, $lastHeight);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $lastWidth, $lastHeight, $width, $height);
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }
            
            if($this->imageType == "image/jpeg" || $this->imageType == "image/jpg" || $this->imageType == "image/pjpeg")
            {
                imagejpeg($tmp, $targetFile, 100); 
            }
            if($this->imageType == "image/gif")
            {
               imagegif($tmp, $targetFile, 100); 
            }
            if($this->imageType == "image/png")
            {
                $black = imagecolorallocate($tmp, 0, 0, 0);
                // Make the background transparent
                imagecolortransparent($tmp, $black);
                imagepng($tmp, $targetFile, 9); 
            }
            // 85 is my choice, make it between 0 – 100 for output image quality with 100 being the most luxurious
        } else if(trim($resize_type) == "H") {
            if (empty($ht) || empty($targetFile)) {
                return false;
            }
            if($this->imageType == "image/jpeg" || $this->imageType == "image/jpg" || $this->imageType == "image/pjpeg")
            {
                $src = imagecreatefromjpeg($this->originalFile);
            }
            if($this->imageType == "image/gif")
            {
                $src = imagecreatefromgif($this->originalFile);
            }
            if($this->imageType == "image/png")
            {
                $src = imagecreatefrompng($this->originalFile);
                
                imagealphablending($src, false);
                imagesavealpha($src, true);
    
            }
            
            list($width, $height) = getimagesize($this -> originalFile);
             
            $newHeight = $ht;
            $newWidth = ($width / $height) * $newHeight;
             
            $lastHeight = $newHeight;
            $lastWidth = $newWidth;
            //$newHeight = ($height / $width) * $newWidth;
            
            $tmp = imagecreatetruecolor($lastWidth, $lastHeight);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $lastWidth, $lastHeight, $width, $height);
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }
            
            if($this->imageType == "image/jpeg" || $this->imageType == "image/jpg" || $this->imageType == "image/pjpeg")
            {
                imagejpeg($tmp, $targetFile, 100); 
            }
            if($this->imageType == "image/gif")
            {
               imagegif($tmp, $targetFile, 100); 
            }
            if($this->imageType == "image/png")
            {
                $black = imagecolorallocate($tmp, 0, 0, 0);
                // Make the background transparent
                imagecolortransparent($tmp, $black);
                imagepng($tmp, $targetFile, 9); 
            }
            // 85 is my choice, make it between 0 – 100 for output image quality with 100 being the most luxurious
        } else if(trim($resize_type) == "W") {
            if (empty($wt) || empty($targetFile)) {
                return false;
            }
            if($this->imageType == "image/jpeg" || $this->imageType == "image/jpg" || $this->imageType == "image/pjpeg")
            {
                $src = imagecreatefromjpeg($this->originalFile);
            }
            if($this->imageType == "image/gif")
            {
                $src = imagecreatefromgif($this->originalFile);
            }
            if($this->imageType == "image/png")
            {
                $src = imagecreatefrompng($this->originalFile);
                
                imagealphablending($src, false);
                imagesavealpha($src, true);
    
            }
            
            list($width, $height) = getimagesize($this->originalFile);
            
            $newWidth = $wt;
            $newHeight = ($height / $width) * $newWidth;
        
            
             
            $lastHeight = $newHeight;
            $lastWidth = $newWidth;
            //$newHeight = ($height / $width) * $newWidth;
            
            $tmp = imagecreatetruecolor($lastWidth, $lastHeight);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $lastWidth, $lastHeight, $width, $height);
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }
            
            if($this->imageType == "image/jpeg" || $this->imageType == "image/jpg" || $this->imageType == "image/pjpeg")
            {
                imagejpeg($tmp, $targetFile, 100); 
            }
            if($this->imageType == "image/gif")
            {
               imagegif($tmp, $targetFile, 100); 
            }
            if($this->imageType == "image/png")
            {
                $black = imagecolorallocate($tmp, 0, 0, 0);
                // Make the background transparent
                imagecolortransparent($tmp, $black);
                imagepng($tmp, $targetFile, 9); 
            }
            // 85 is my choice, make it between 0 – 100 for output image quality with 100 being the most luxurious
        }
        
    } 
}

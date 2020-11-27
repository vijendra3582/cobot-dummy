<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting;
use App\SitePopup;
use Validator;
class GeneralSettingController extends Controller
{
	private $id;

    public function __construct()
    {
        $this->id = 1001; 
    }

    /*** function to move file from temp to folder **/

     private function processImage($file, $type, $destination ) {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type."-".rand(0,99).time()."-" . $file;
        /// rename image ============
        $SOURCE_PATH = public_path(\Config::get("constants.temp_folder")) . $file;
        $DESTINATION_PATH = public_path($destination) . $NEW_FILE_NAME;
        rename($SOURCE_PATH, $DESTINATION_PATH);
       
        // $file_type = getimagesize($DESTINATION_PATH);
        // $work = new imageresizer($DESTINATION_PATH, $file_type['mime']);
        // $work->resize(200, 200, $this->path_to_uploads . $this->uploads_folder . "/" . $this->module_folder . "/R200-" . $NEW_FILE_NAME, "");
        // $work->resize(300, 300, $this->path_to_uploads . $this->uploads_folder . "/" . $this->module_folder . "/R300-" . $NEW_FILE_NAME, "");
        
        return $NEW_FILE_NAME;
    
    }

    /*************************************************/

   public function editSetting(){
   		$row = GeneralSetting::first();  
        $returnArr = array("SUCCESS" => 1, "DATA"=> $row); 
        echo json_encode($returnArr);
   }

   public function saveSetting(){ 
   		$validator = Validator::make(request()->all(),[   
            'info_email' => 'required|email',
        ],[ 'info_email.required'=> 'Please enter a valid info email.',
            'info_email.email'=> 'Please enter a valid info email.'
        ]);

        if ($validator->fails()) {
            $validation_array = [];
            foreach ($validator->errors()->getMessages()  as $key => $value) {
                array_push($validation_array, implode(',', $value));
            } 

            $returnArr = array("SUCCESS" => 2, "MSG"=> implode(',', $validation_array));
            echo json_encode($returnArr);
            exit();
        }

        if(!empty(request('facebook_url'))){
        	$facebook_url = str_replace('https://', '', request('facebook_url')); 
        	$facebook_url = str_replace('http://', '', $facebook_url);
        	$facebook_url = 'https://'.$facebook_url; 
        }else{
            $facebook_url = '';
        }

        if(!empty(request('twitter_url'))){
        	$twitter_url = str_replace('https://', '', request('twitter_url')); 
        	$twitter_url = str_replace('http://', '', $twitter_url);
        	$twitter_url = 'https://'.$twitter_url; 
        }else{
            $twitter_url = ''; 
        }

        if(!empty(request('linkedin_url'))){
            $linkedin_url = str_replace('https://', '', request('linkedin_url')); 
            $linkedin_url = str_replace('http://', '', $linkedin_url);
            $linkedin_url = 'https://'.$linkedin_url; 
        }else{
            $linkedin_url = ''; 
        }

        if(!empty(request('location_url'))){
            $location_url = str_replace('https://', '', request('location_url')); 
            $location_url = str_replace('http://', '', $location_url);
            $location_url = 'https://'.$location_url; 
        }else{
            $location_url = ''; 
        }

        $upd = GeneralSetting::updateOrCreate(['id' => request('id')],[
            "telephone" => request("telephone"),
            "company_name" => request("company_name"),
            "info_email" => request('info_email'),
            "address" => request('address'),
            "location_url" => $location_url,
            "facebook_url" => $facebook_url,
            
            "twitter_url" => $twitter_url,
            "linkedin_url" => $linkedin_url,
            
            "copyrights" => request('copyrights'),
            "contact_us_header" => request('contact_us_header'),
            "contact_us_footer" => request('contact_us_footer'),
            "subscribe_header" => request('subscribe_header'),
            "subscribe_footer" => request('subscribe_footer'),
            "contact_us_mail_to" => request('contact_us_mail_to'),

            "enable_map_button" => request('enable_map_button'),
            "button_txt" => request('button_txt')             
        ]); 

        if(!empty(request('map_img')))
        {
            if(!empty($upd->map_img) && \File::exists(public_path(\Config::get("constants.upload_folder")).$upd->map_img))
                  \File::delete(public_path(\Config::get("constants.upload_folder")) . $upd->map_img);
                    
            $NEW_FILE_NAME = $this->processImage(request('map_img'), 'map', \Config::get("constants.upload_folder"));

            $upd->update([
                "map_img" => $NEW_FILE_NAME
            ]);
        }   

        if(!empty(request('map_background_img')))
        {
            if(!empty($upd->map_background_img) && \File::exists(public_path(\Config::get("constants.upload_folder")).$upd->map_background_img))
                  \File::delete(public_path(\Config::get("constants.upload_folder")) . $upd->map_background_img);
                    
            $NEW_FILE_NAME = $this->processImage(request('map_background_img'), 'map_background', \Config::get("constants.upload_folder"));

            $upd->update([
                "map_background_img" => $NEW_FILE_NAME
            ]);
        }   

        $returnArr = array("SUCCESS" => 1, "data"=> $upd, "MSG"=> "General settings saved.");
        echo json_encode($returnArr);
   }

   public function removeBanner(){
        $general = GeneralSetting::first();
        if(request('stype') == 'background'){
            if(!empty($general) && !empty($general->map_background_img) && \File::exists(public_path(\Config::get("constants.upload_folder")).$general->map_background_img)){
                  \File::delete(public_path(\Config::get("constants.upload_folder")) . $general->map_background_img);

                  $general->update(['map_background_img'=>null]);
            }

        }else{
            if(!empty($general) && !empty($general->map_img) && \File::exists(public_path(\Config::get("constants.upload_folder")).$general->map_img)){
                  \File::delete(public_path(\Config::get("constants.upload_folder")) . $general->map_img);

                  $general->update(['map_img'=>null]);
            }
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
   }

    public function sitepopup(){

        $data = SitePopup::where('key', request('call'))->first();
        if(!empty($data)){
            $returnArr = array("SUCCESS" => 1, "DATA"=> $data); 
            echo json_encode($returnArr);
        }else{
            $returnArr = array("SUCCESS" => 2, "MSG"=> "wrong input found.");
            echo json_encode($returnArr);
        }
    }

    public function saveSitePopup(){
        if(!empty(request('link'))){
            $location_url = str_replace('https://', '', request('link')); 
            $location_url = str_replace('http://', '', $location_url);
            $location_url = 'https://'.$location_url; 
        }else{
            $location_url = ''; 
        }

        $upd = SitePopup::updateOrCreate(['key' => request('call')],[
            "title" => request("title"),
            "content" => request('content'),
            "link" => $location_url,
            'status' => request('status')           
        ]);    

        $returnArr = array("SUCCESS" => 1, "data"=> $upd, "MSG"=> request("title")." saved.");
        echo json_encode($returnArr);
    }
}

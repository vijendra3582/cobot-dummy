<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fund;
use App\FundContentDisclosure;
use App\FundOutcomeDisclosure;
class FundController extends Controller
{

    
	/*** function to move file from temp to folder **/

    private function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = strtolower($string); // Convert to lowercase
 
        return $string;
    }


     private function processImage($file, $type, $destination ) {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type."-".time()."-" . $file;
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

    public function listFunds($page){
    	$skip = ($page-1)*request('perpage');
        $list = Fund::where('status','!=', Fund::DELETED)->orderBy('position','ASC');

        // $searchedFields = [
        //     'search_news_title' => request('search_news_title') ? request('search_news_title') : '',
        //     'search_news_publication' => request('search_news_publication') ? request('search_news_publication') : '',
        //     'search_news_status'=> request('search_news_status') != '' ? request('search_news_status') : ''

        // ];
        // if(!empty(request('search_news_title'))){
        //     $list = $list->where('title', 'like', '%'.request('search_news_title').'%');      
        // }
        // if(request('search_news_status') != '' ){
        //     $list = $list->where('status', request('search_news_status'));        
        // }
        // if(!empty(request('search_news_publication'))){
        //     $list = $list->where('publication', 'like', '%'.request('search_news_publication').'%');      
        // }
             
        $total_records = $list->count();
        $list = $list->skip($skip)->take(request('perpage'))->get()->groupBy(function($q){
            return $q->is_outcome_product;
        })->toArray();
    
        $returnArr = array("SUCCESS" => 1, "data"=> $list, 'searchedFields'=>'', 'total_records'=> $total_records);
        echo json_encode($returnArr);
    }

    public function saveFund(){

        $uniqueFund = Fund::where('id','!=',request('fund_id'))->where('fund_name',request('fund_name'))->first();
        if(!empty($uniqueFund)){
            $returnArr = array("SUCCESS" => 2, "MSG"=> request('fund_name').' already exist.');
            return json_encode($returnArr);  
        }

        if (!empty(request('fund_inception_date'))) {
            $a_exc = explode('-', request('fund_inception_date'));
            $asOfDate = $a_exc[2].'-'.$a_exc[0].'-'.$a_exc[1];
        }else{
            $asOfDate = null;
        }
        
        if (!empty(request('launch_date'))) {
            $a_launch = explode('-', request('launch_date'));
            $launchDate = $a_launch[2].'-'.$a_launch[0].'-'.$a_launch[1];
        }else{
            $launchDate = null;
        }
        
        if (!empty(request('end_date'))) {
            $e_launch = explode('-', request('end_date'));
            $endDate = $e_launch[2].'-'.$e_launch[0].'-'.$e_launch[1];
        }else{
            $endDate = null;
        }

    	$upd = Fund::updateOrCreate(['id' => request('fund_id')],[
            "fund_name" => request('fund_name'),
            "sub_title" => request('sub_title'),
            "menu_title"=> request('menu_title'),
            "fund_inception_date" => $asOfDate,
            "fund_profile_id" => request('fund_profile_id'),
            "fund_ticker" => request('fund_ticker'),
            'fund_short_description' => request('fund_short_description') ,
            'fund_description' => request('fund_description') ,
            'fund_index_description' => request('fund_index_description'),
            'index_learn_more_link' => request('index_learn_more_link'),
            'fund_detail_notes' => request('fund_detail_notes') ,
            'fund_data_pricing_notes' => request('fund_data_pricing_notes') ,
            'holdings_notes' => request('holdings_notes') ,
            'performance_description' => request('performance_description'), 
            'fund_disclosure' => request('fund_disclosure') ,
            'is_premium_discount' => request('is_premium_discount') ,
            'url_key' => !empty(request('url_key')) ? request('url_key') : $this->clean(request('fund_name')) ,
            'meta_title' => !empty(request('meta_title')) ? request('meta_title') : request('fund_name') ,
            'meta_description' => request('meta_description') ,
            'meta_keyword' => request('meta_keyword') ,
            'status' => request('status'),
            'is_outcome_product'=>request('is_outcome_product'),
            'product_series'=>request('product_series'),
            'launch_date' => $launchDate,
            'end_date'=>$endDate
        ]);

       
        if((request('is_premium_discount') == 1) && !empty(request("premium_discount_file")))
        {
			if(!empty($upd->premium_discount_file) && \File::exists(public_path(\Config::get("constants.fund_folder")).$upd->premium_discount_file))
	            \File::delete(public_path(\Config::get("constants.fund_folder")) . $upd->premium_discount_file);
	        	        
	        $NEW_FILE_NAME = $this->processImage(request('premium_discount_file'), 'discount', \Config::get("constants.fund_folder"));

	            $upd->update([
	                "premium_discount_file" => $NEW_FILE_NAME
	            ]);
        }

        if(!empty(request("banner_image")))
        {
			if(!empty($upd->banner_image) && \File::exists(public_path(\Config::get("constants.fund_folder")).$upd->banner_image))
	            \File::delete(public_path(\Config::get("constants.fund_folder")) . $upd->banner_image);
	        	        
	        $NEW_FILE_NAME = $this->processImage(request('banner_image'), 'banner', \Config::get("constants.fund_folder"));

	            $upd->update([
	                "banner_image" => $NEW_FILE_NAME
	            ]);
        }

        if(!empty(request("fund_image")))
        {
            if(!empty($upd->fund_logo) && \File::exists(public_path(\Config::get("constants.fund_folder")).$upd->fund_logo))
                \File::delete(public_path(\Config::get("constants.fund_folder")) . $upd->fund_logo);
                        
            $NEW_FILE_NAME = $this->processImage(request('fund_image'), 'fund-logo', \Config::get("constants.fund_folder"));

                $upd->update([
                    "fund_logo" => $NEW_FILE_NAME
                ]);
        }

        if(!empty(request("fund_banner_logo")))
        {
            if(!empty($upd->fund_banner_logo) && \File::exists(public_path(\Config::get("constants.fund_folder")).$upd->fund_banner_logo))
                \File::delete(public_path(\Config::get("constants.fund_folder")) . $upd->fund_banner_logo);
                        
            $NEW_FILE_NAME = $this->processImage(request('fund_banner_logo'), 'f-banner-logo', \Config::get("constants.fund_folder"));

                $upd->update([
                    "fund_banner_logo" => $NEW_FILE_NAME
                ]);
        }

        if(empty(request('fund_id'))){
            $last = Fund::where('id', '!=', request('fund_id'))->max('position'); 
            $upd = $upd->update(['position' => $last+1 ]);
        }
                

        $returnArr = array("SUCCESS" => 1, "DATA"=> $upd);
        echo json_encode($returnArr);  
    }

    /********************************/
    public function editFund(){
    	$result = Fund::where('id', request('id'))->where('status', '!=', Fund::DELETED)->first()->toArray();
    	if(!empty($result)){
    		$length = 1;
    	}else{
    		$length = 0;
    	}
        $returnArr = array("SUCCESS" => 1, "data"=> $result, "length" => $length);
        echo json_encode($returnArr); 
    }

    /*******************************/
    public function deleteFund(){
    	$result = Fund::where('id', request('fund_id'))->first();
    	if(!empty($result)){
    		$result->update(['status' => Fund::DELETED]);
    	}
    	$returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr); 
    }

    /*********************************/
    public function fundStatus(){
    	$result = Fund::where('id', request('fund_id'))->first();
    	if(!empty($result)){
    		$result->update(['status' => request('fund_status')]);
    	}
    	$returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr); 
    }
    /*********************************/
    public function fundDeleteAll(){
    	$ids_to_delete = array_map(function($item){ return $item['id']; }, request('DIDS'));
        Fund::whereIn('id', $ids_to_delete)->update(['status'=> Fund::DELETED]); 
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
    /*********************************/
    public function fundPosition(){
    	foreach(request('data') as $k=> $val){
            $res = Fund::where('id', $val['fund_id'])->first();
            if(!empty($res))
                $res->update(['position'=> $val['fund_position']]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
    /**********************************/

    public function removeFile(){
        if(request('field') === 'banner_image'){
            if(!empty(request('file')) && \File::exists(public_path(\Config::get("constants.fund_folder")). request('file'))){ 
                \File::delete(public_path(\Config::get("constants.temp_folder")) . request('file'));
            }else{
                $fund = Fund::where('id',request('fund_id'))->first();
                if(!empty($fund)){
                    if(!empty($fund->banner_image) && \File::exists(public_path(\Config::get("constants.fund_folder")).$fund->banner_image))
                    \File::delete(public_path(\Config::get("constants.fund_folder")) . $fund->banner_image);

                    $fund->update(['banner_image'=> null]);
                }
            }
        }
        if(request('field') === 'premium_discount'){
            $fund = Fund::where('id',request('fund_id'))->first();
            if(!empty($fund)){
                if(!empty($fund->premium_discount_file) && \File::exists(public_path(\Config::get("constants.fund_folder")).$fund->premium_discount_file))
                \File::delete(public_path(\Config::get("constants.fund_folder")) . $fund->premium_discount_file);

                $fund->update(['premium_discount_file'=> null]);
            }
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }

    /***********************/
    public function saveFundContent(){
        $upd = FundContentDisclosure::updateOrCreate(['id' => 1001],[
            "title" => request("title"),
            "description" => request("description")
        ]);

        $returnArr = array("SUCCESS" => 1, "DATA"=> $upd);
        echo json_encode($returnArr);  
    }

    public function getFundContent(){
        $row = FundContentDisclosure::first();  
        $returnArr = array("SUCCESS" => 1, "DATA"=> $row); 
        echo json_encode($returnArr);
    }

    public function getFundOutcomeContent(){
        $row = FundOutcomeDisclosure::first()->toArray();  
        $returnArr = array("SUCCESS" => 1, "DATA"=> $row); 
        echo json_encode($returnArr);
    }

    public function saveFundOutcomeContent(){
        
        $upd = FundOutcomeDisclosure::updateOrCreate(['id' => 1001],[
            "title" => request("title"),
            "sub_title" => request("sub_title"),
            "description" => request("description"),
            "disclosure"=>request('disclosure'),
            "banner_footer_txt"=>request('banner_footer_txt'),
            "slug"=>$this->clean(request('title')),
            "meta_title"=> !empty(request('meta_title')) ? request('meta_title') : $this->clean(request('title')),
            "meta_keyword" => request('meta_keyword'),
            "meta_description" => request('meta_description'),
            "structured_outcome_title"=> request('structured_outcome_title'),
            "structured_outcome_subtitle"=> request('structured_outcome_subtitle'),
            "structured_outcome_short_desc"=> request('structured_outcome_short_desc')
        ]);

        if(!empty(request("banner_img")))
        {
            $NEW_FILE_NAME = $this->processImage(request('banner_img'), 'BANNER-OUTCOME-LIST', \Config::get("constants.fund_folder"));

            $upd->update([
                "banner_img" => $NEW_FILE_NAME
            ]);
        }

        $returnArr = array("SUCCESS" => 1, "DATA"=> $upd);
        echo json_encode($returnArr);  
    }

    public function removeOutcomeBanner(){
        $row = FundContentDisclosure::first(); 
        if(!empty($row)){
            if(!empty($row->banner_img) && file_exists(public_path(config('constants.fund_folder').$row->banner_img ))){
                \File::delete(public_path(config('constants.fund_folder').$row->banner_img ));
            }

            $row->update(['banner_img'=>null]);
        }

        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }

   
}

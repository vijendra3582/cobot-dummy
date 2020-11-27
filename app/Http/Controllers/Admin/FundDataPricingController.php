<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fund;
use App\FundDataPricing;
class FundDataPricingController extends Controller
{
    /*** function to move file from temp to folder **/

     private function processImage($file, $type, $destination ) {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type."-".rand(10,99).time()."-" . $file;
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

    public function details(){
    	 $list = FundDataPricing::where('fund_id',request('fund_id'))->where('status', '!=', \Config::get("constants.const_deleted"))->orderBy('position','ASC')->get();
        if(!empty($list)){
        	$list=$list->toArray();
        }

        $RETURN_ARRAY = array(); 
        $RETURN_ARRAY['data'] = $list; 
        echo json_encode($RETURN_ARRAY);
    }

    public function save(){

    	if(count(request('allRows')) > 0){
    		
    		foreach (request('allRows') as $key => $value) {
    			 
    			$upd = FundDataPricing::updateOrCreate(['id' => $value['id']],[
		            "fund_id" => request('fund_id'),
		            "data_type"=> !empty($value['data_type']) ? $value['data_type'] : '',
		            "data_head"=> $value['data_head'],
		            "data_value"=> $value['data_value'],
                    "display_status"=> (int)$value['display_status'],
                    "tags"=> $value['tags'],
                    "tags_field"=> $value['tags_field'],
                    "tags_table"=> $value['tags_table'],
                    "tags_cond" => (int)$value['tags_cond'],
                    "do_not_update"=> (int)$value['do_not_update'], 
		            "position" => $key,
		            "status"=> (int)$value['status']            
		        ]);

		        if(empty($value['id']))
		        	$upd->update([
		                "add_ip" => request()->ip()
		            ]);
		        else
		        	$upd->update([
		                "update_ip" => request()->ip()
		            ]);
    		}

            if (!empty(request('fund_data_and_pricing_as_of_date'))) {
                $a_exc = explode('-', request('fund_data_and_pricing_as_of_date'));
                $asOfDate = $a_exc[2].'-'.$a_exc[0].'-'.$a_exc[1];
            }else{
                $asOfDate = null;
            }

            $fund = Fund::where('id',request('fund_id'))->update(['fund_data_and_pricing_as_of_date'=> $asOfDate]);
    		$returnArr = array("SUCCESS" => 1);
    	}else{
    		$returnArr = array("SUCCESS" => 2);
    	}

        echo json_encode($returnArr);  
    }

    /*****/
    public function deleteDetail(){

    	$fundDetail = FundDataPricing::where('id',request('id'))->where('fund_id', request('fund_id'))->first();
    	if(!empty($fundDetail)){
    		$fundDetail->update(['status'=> \Config::get('constants.const_deleted')]);
    	}

    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);  
    }
}

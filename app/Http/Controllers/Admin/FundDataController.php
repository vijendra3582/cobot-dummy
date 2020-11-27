<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fund;
use App\FundData;
class FundDataController extends Controller
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


    public function fundDetails(){
    	 $list = FundData::where('fund_id',request('fund_id'))->where('status', '!=', \Config::get("constants.const_deleted"))->orderBy('position','ASC')->get();
        if(!empty($list)){
        	$list=$list->toArray();
        }

        $RETURN_ARRAY = array(); 
        $RETURN_ARRAY['data'] = $list; 
        echo json_encode($RETURN_ARRAY);
    }

    public function saveDetails(){
    	if(count(request('allRows')) > 0){
    		
    		foreach (request('allRows') as $key => $value) {
    			 
    			$upd = FundData::updateOrCreate(['id' => $value['id']],[
		            "fund_id" => request('fund_id'),
		            "data_type"=> !empty($value['data_type']) ? $value['data_type'] : '',
		            "data_head"=> $value['data_head'],
		            "data_value"=> $value['data_value'],
		            "position" => $key,
		            "status"=> $value['status']            
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
    		$returnArr = array("SUCCESS" => 1);
    	}else{
    		$returnArr = array("SUCCESS" => 2);
    	}

        echo json_encode($returnArr);  
    }

    /*****/
    public function deleteDetail(){
    	$fundDetail = FundData::where('id',request('id'))->where('fund_id', request('fund_id'))->first();
    	if(!empty($fundDetail)){
    		$fundDetail->update(['status'=> \Config::get('constants.const_deleted')]);
    	}

    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);  
    }
}

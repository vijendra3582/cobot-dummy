<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fund;
class FundPerformanceController extends Controller
{
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

    /********************/

    public function fundPerformance(){
    	$list = Fund::where('id', request('fund_id'))->first();
    	
        // print_r($list);
		$RETURN_ARRAY = array(); 
        $RETURN_ARRAY['cumulative_performance_table'] = json_decode($list->cumulative_performance_table); 
        $RETURN_ARRAY['calendar_yr_performance_table'] = json_decode($list->calendar_yr_performance_table); 
        echo json_encode($RETURN_ARRAY);
    }

    public function savePerformance(){
    	//print_r(request()->all());
    	$fund = Fund::where('id', request('fund_id'))->first();
    	if(!empty($fund)){
    		$data_arr = [
    			'f_override_performance'=> (int)(request('f_override_performance') ?? 0) ,
    			'f_active_performance' => (int)request('f_active_performance'),
    			'calendar_yr_perfromance_display'=> (int)request('calendar_yr_perfromance_display'),
    			'cumulative_performance_display'=>(int)request('cumulative_performance_display'),

    			'cumulative_performance_text' => request('cumulative_performance_text') ?? '',

    			// 'performance_available_after'=> request('performance_available_after') ?? '',
    			// 'performance_expense_ratio' => request('performance_expense_ratio') ?? '',
    			// 'performance_heading' => request('performance_heading') ?? '',

    			'calendar_yr_performance_table' => json_encode(request('allRows')),
    			'cumulative_performance_table' => json_encode(request('allRowsMonthly'))
    		];
    		//print_r($data_arr);
    		$fund->update($data_arr);

    		$returnArr = array("SUCCESS" => 1);
    	}else{
    		$returnArr = array("SUCCESS" => 2);
    	}
    	echo json_encode($returnArr);
    }

    public function activatePerformance(){
    	$fund = Fund::where('id', request('fund_id'))->first();
    	if(!empty($fund)){
    		$fund->update(['f_active_performance' => (int)request('f_active_performance')]);
    	}

    	echo json_encode(array('success' => 1));
    }
}

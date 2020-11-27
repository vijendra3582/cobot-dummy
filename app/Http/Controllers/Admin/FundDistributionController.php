<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fund;
use App\FundDistribution;
class FundDistributionController extends Controller
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
    	$list = FundDistribution::where('fund_id',request('fund_id'))->where('status', '!=', \Config::get("constants.const_deleted"))->orderBy('position','ASC')->get();
        if(!empty($list)){
        	$list=$list->toArray();
        }

        $RETURN_ARRAY = array(); 
        $RETURN_ARRAY['data'] = $list; 
        echo json_encode($RETURN_ARRAY);
    }

    public function activatateFundDistribution(){
    	$fund = Fund::where('id',request('fund_id'))->first();
    	if(!empty($fund)){
    		$fund->update(['f_active_fund_distribution'=> (int)request('f_active_fund_distribution')]);
    	}
    	$returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr); 
    }

    public function saveDistribution(){
// print_r(request()->all());
    	if(count(request('allRows')) > 0){
    		
    		foreach (request('allRows') as $key => $value) {
    			 
    			$upd = FundDistribution::updateOrCreate(['id' => $value['id']],[
		            "fund_id" => request('fund_id'),
		            "ex_date"=>$value['ex_date'],
		            "record_date"=> $value['record_date'],
		            "payable_date"=> $value['payable_date'],
                    "amount"=> $value['amount'],
		            "position" => $key            
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


            if((!empty(request("distribution_schedule_file"))))
            {
                $fund = Fund::where('id',request('fund_id'))->first();
                if(!empty($fund->distribution_schedule_file) && \File::exists(public_path(\Config::get("constants.fund_folder")).$fund->distribution_schedule_file))
                    \File::delete(public_path(\Config::get("constants.fund_folder")) . $fund->distribution_schedule_file);
                            
                    $NEW_FILE_NAME = $this->processImage(request('distribution_schedule_file'), 'distribution_schedule', \Config::get("constants.fund_folder"));

                    $fund->update([
                        "distribution_schedule_file" => $NEW_FILE_NAME
                    ]);
            }
            
    		$returnArr = array("SUCCESS" => 1);
    	}else{
    		$returnArr = array("SUCCESS" => 2);
    	}

        echo json_encode($returnArr);  
    }

    /*****/
   
    public function deleteDistribution(){
    	$FundDistribution = FundDistribution::where('id',request('id'))->where('fund_id', request('fund_id'))->first();
    	if(!empty($FundDistribution)){
            // $FundDistribution->update(['status'=> \Config::get('constants.const_deleted')]);
    		$FundDistribution->delete();
    	}

    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);
    }

    /********/

    public function removeScheduleFile(){
        $fund = Fund::where('id',request('fund_id'))->first();
        if(!empty($fund)){
            if(!empty($fund->distribution_schedule_file) && \File::exists(public_path(\Config::get("constants.fund_folder")).$fund->distribution_schedule_file))
            \File::delete(public_path(\Config::get("constants.fund_folder")) . $fund->distribution_schedule_file);
                    
            $fund->update([
                "distribution_schedule_file" => ''
            ]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);           
    }
}

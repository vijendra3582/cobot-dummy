<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fund;
use App\FundHoldings;
class FundHoldingsController extends Controller
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

    /********************/

    public function details(){
    	$list = FundHoldings::where('fund_id',request('fund_id'))->where('status', '!=', \Config::get("constants.const_deleted"))->orderBy('position','ASC')->get();
        if(!empty($list)){
        	$list=$list->toArray();
        }

        $RETURN_ARRAY = array(); 
        $RETURN_ARRAY['data'] = $list; 
        echo json_encode($RETURN_ARRAY);
    }

    public function save(){
    	if(count(request('allRows')) > 0){
    		$existingFund = Fund::where('id',request('fund_id'))->first();  
    		foreach (request('allRows') as $key => $value) {
    			 
    			$upd = FundHoldings::updateOrCreate(['id' => $value['id']],[
		            "fund_id" => request('fund_id'),
		            "name"=> $value['name'],
                    "identifier"=> $value['identifier'],
		            "cusip"=> $value['cusip'],
		            "percentage_of_net_assets"=> (double)$value['percentage_of_net_assets'],
                    "shares_held"=> (double)$value['shares_held'],
                    "market_value"=> (double)$value['market_value'],
                      
		            "position" => $key,
		            // "status"=> (int)$value['status'],
		            "url_key"=> strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', trim(preg_replace('/\s+/', ' ', $value['name']))))),
		            "meta_title"=> $value['name']

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

            if (!empty(request('fund_holdings_as_of_date'))) {
                $a_exc = explode('-', request('fund_holdings_as_of_date'));
                $asOfDate = $a_exc[2].'-'.$a_exc[0].'-'.$a_exc[1];
            }else{
                $asOfDate = null;
            }

            $fund_arr = [
            	'f_override_fund_holdings'=>(int)request('f_override_fund_holdings'),
            	//'f_active_fund_holdings'=> (int)request('f_active_fund_holdings'),
            	'fund_holdings_as_of_date' => $asOfDate
            ];
            $fund = $existingFund->update($fund_arr);

            if(!empty(request('holdings_file')))
            {
                if(!empty($existingFund->holdings_file) && \File::exists(public_path(\Config::get("constants.fund_folder")).$existingFund->holdings_file))
                      \File::delete(public_path(\Config::get("constants.fund_folder")) . $existingFund->holdings_file);
                        
                $NEW_FILE_NAME = $this->processImage(request('holdings_file'), 'fund_holdings', \Config::get("constants.fund_folder"));

                $existingFund->update([
                    "holdings_file" => $NEW_FILE_NAME
                ]);
            }

    		$returnArr = array("SUCCESS" => 1);
    	}else{
    		$returnArr = array("SUCCESS" => 2);
    	}

        echo json_encode($returnArr);  
    }


    public function activateFundHolding(){
    	$fund = Fund::where('id', request('fund_id'))->first();
    	if(!empty($fund)){
    		$fund->update(['f_active_fund_holdings'=> (int)request('f_active_fund_holdings')]);
    	}
    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);
    }

    public function deleteHoldings(){
    	
    	$fundHoldings = FundHoldings::where('id',request('id'))->where('fund_id', request('fund_id'))->first();
    	if(!empty($fundHoldings)){
    		// $fundHoldings->update(['status'=> \Config::get('constants.const_deleted')]);
            $fundHoldings->delete();
    	}

    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);  
    }

    public function removeHoldingFiles(){
        $fund = Fund::where('id',request('fund_id'))->first();
        if(!empty($fund)){
            if(!empty($fund->holdings_file) && \File::exists(public_path(\Config::get("constants.fund_folder")).$fund->holdings_file))
                      \File::delete(public_path(\Config::get("constants.fund_folder")) . $fund->holdings_file);
                        
                $NEW_FILE_NAME = $this->processImage(request('holdings_file'), 'fund_holdings', \Config::get("constants.fund_folder"));

                $fund->update([
                    "holdings_file" => ''
                ]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);  
    }
}

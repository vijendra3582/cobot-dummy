<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\fundOutcomePeriodValues as PeriodValues;
use App\fundCurrentOutcomePeriodValues as CurrentPeriodValues;
use App\Fund;
class FundOutcomePeriodController extends Controller
{
    public function index(){
    	$list = PeriodValues::where('fund_id',request('fund_id'))->where('status', '!=', \Config::get("constants.const_deleted"))->orderBy('position','ASC')->get();
        if(!empty($list)){
        	$list=$list->toArray();
        }

        $RETURN_ARRAY = array(); 
        $RETURN_ARRAY['data'] = $list; 
        echo json_encode($RETURN_ARRAY);
    }

    public function indexCurrent(){
        $list = CurrentPeriodValues::where('fund_id',request('fund_id'))->where('status', '!=', \Config::get("constants.const_deleted"))->first();
        if(!empty($list)){
            $list=$list->toArray();
        }
        echo json_encode($list);
    }

    public function activateCurrentOutcome(){
    	$fund = Fund::where('id',request('fund_id'))->first();
    	if(!empty($fund)){
    		$fund->update(['display_current_outcome_period_values'=> (int)request('display_current_outcome_period_values')]);
    	}
    	$returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr); 
    }

    public function activateOutcome(){
        $fund = Fund::where('id',request('fund_id'))->first();
        if(!empty($fund)){
            $fund->update(['display_outcome_period_values'=> (int)request('display_outcome_period_values')]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr); 
    }

    public function save(){  

    	if(!empty(request('outcome_period_values_text'))){
    		$fund = Fund::where('id',request('fund_id'))->first();
	    	if(!empty($fund)){
	    		$fund->update(['outcome_period_values_text'=> request('outcome_period_values_text')]);
	    	}
    	}


    	if(count(request('allRows')) > 0){
    		foreach (request('allRows') as $key => $value) {
    			 
    			$upd = PeriodValues::updateOrCreate(['id' => $value['id']],[
		            "fund_id" => request('fund_id'),
		            "etf_starting_nav_period_return"=>$value['etf_starting_nav_period_return'],
		            "spx_index_reference_price"=> $value['spx_index_reference_price'],
		            "downside_buffer"=> $value['downside_buffer'],
                    "expected_upside_participation"=> $value['expected_upside_participation'],
                    "days_remaining"=> $value['days_remaining'],                    
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
            
    		$returnArr = array("SUCCESS" => 1);
    	}else{
    		$returnArr = array("SUCCESS" => 2);
    	}
        echo json_encode($returnArr);  
    }

    /*****/

    public function saveCurrent(){  

        $upd = CurrentPeriodValues::updateOrCreate(['id' => request('id')],[
            "fund_id" => request('fund_id'),
            "etf_starting_nav"=> request('etf_starting_nav'),
            "total_buffer"=> request('total_buffer'),
            "treasury_yield"=> request('treasury_yield'),                    
            "s_p_year_start_value"=>request('s_p_year_start_value'),
            "s_p_reference_value"=> request('s_p_reference_value'),
            "upside_participation"=> request('upside_participation'),
            
            "etf_current_price" =>request('etf_current_price'),
            "period_return"=>request('period_return'),
            "spx_period_return"=>request('spx_period_return'),
            "remaining_buffer"=>request('remaining_buffer'),
            "downside_before_buffer"=>request('downside_before_buffer'),
            "downside_to_floor_of_buffer"=>request('downside_to_floor_of_buffer'),
            "remaining_outcome_period"=>request('remaining_outcome_period'),

            "override_etf_current_price" =>request('override_etf_current_price'),
            "override_period_return"=>request('override_period_return'),
            "override_spx_period_return"=>request('override_spx_period_return'),
            "override_remaining_buffer"=>request('override_remaining_buffer'),
            "override_downside_before_buffer"=>request('override_downside_before_buffer'),
            "override_downside_to_floor_of_buffer"=>request('override_downside_to_floor_of_buffer'),
            "override_remaining_outcome_period"=>request('override_remaining_outcome_period')
        ]);

        if(empty(request('id')))
            $upd->update([
                "add_ip" => request()->ip(),
                "position" => CurrentPeriodValues::max('position')+1            
            ]);
        else
            $upd->update([
                "update_ip" => request()->ip()
            ]);
        
        // Fund::where('id',request('fund_id'))->update(['f_override_current_outcome'=>request('f_override_current_outcome')]);

        // if(!empty(request('current_outcome_period_text'))){
            Fund::where('id',request('fund_id'))->update(['current_outcome_period_text'=>request('current_outcome_period_text')]);   
        // }
        
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
   
    public function delete(){
    	$res = PeriodValues::where('id',request('id'))->where('fund_id', request('fund_id'))->first();
    	if(!empty($res)){
            // $FundDistribution->update(['status'=> \Config::get('constants.const_deleted')]);
    		$res->delete();
    	}

    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);
    }

    /********/
}

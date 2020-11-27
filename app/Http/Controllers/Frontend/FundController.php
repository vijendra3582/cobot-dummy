<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fund;
use App\FundData;
use App\FundDataPricing;
use App\FundDistribution;
use App\FundFiles;
use App\FundHoldings;
use App\FundPerformance;
use App\FundOutcomeDisclosure;
use DB;
use App\Exports\USBanksHoldingsExport; 
use App\Exports\StructuredOutcomeEtfExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MobileDetect;
use App\InterimFund;
use App\InterimFundFiles;
class FundController extends Controller
{
    public function indexFund($fundKey){
    	$fund = Fund::where('url_key',$fundKey)->where('status', config('constants.const_active'))->with('fundData','fundDataPricing','fundDistribution','fundFiles','fundHoldings','fundOutcome','fundCurrentOutcome')->orderBy('position','ASC')->first();
    	
    	if(empty($fund) || $fund->coming_soon == config('constants.const_active')){

    		$interimFund = InterimFund::where('url_key',$fundKey)->where('status', config('constants.const_active'))->with('fundData','fundFiles')->first();

    		if(!empty($interimFund)){
    			return view('fund-interim',['fund' => $interimFund->toArray()]);
    		}else{
    			return redirect()->route('home');
    		}

    	}
    	$fund = $fund->toArray(); 
    	 
    	return view('fund-detail',compact('fund'));	
    }

    public function fundFile($fundKey, $fundFileKey){ 
    	  
    	$fund = Fund::where('url_key',$fundKey)->where('status', config('constants.const_active'))->first();
    	if(empty($fund) || $fund->coming_soon == config('constants.const_active')){

    		$interimFund = InterimFund::where('url_key',$fundKey)->where('status', config('constants.const_active'))->first();

    		if(!empty($interimFund)){
    			return $this->interimFundFile($interimFund->id,$fundFileKey);
    		}else{
    			return redirect()->route('home');
    		}

    	}

    	if($fundFileKey == 'premium-discount'){
			$file = $fund['premium_discount_file_link'];
			return view('iframeLayout', compact('file'));
		}
		
		elseif($fundFileKey == 'fund-holdings'){
			$myFile = public_path('fund-upload/').$fund['holdings_file'];
			$newName = 'fund-holding'.'.'.pathinfo($fund['holdings_file'], PATHINFO_EXTENSION) ;
			return response()->download($myFile, $newName);
		}

		else{
			$fundfile = FundFiles::where('fund_id', $fund->id)->where('url_key',$fundFileKey)->where('status', config('constants.const_active'))->first();
	    	if(empty($fundfile)){
	    		return redirect()->route('home');
	    	}
			
	    	$fundfile = $fundfile->toArray();
	    	if(strtolower($fundfile['file_type']) == 'pdf'){
	    		$file = $fundfile['file_link'];

	    		/****************************************/
	    		$detect = new MobileDetect;
				$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
				$scriptVersion = $detect->getScriptVersion();

	    		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false)
				{
				    // User agent is Google Chrome
				    //$deviceType = 'computer';

					if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
				    {
 
				    }
				    else
				    {
				        $deviceType = 'computer';
				    }

			    }

			    if( trim($deviceType) == "tablet" || trim($deviceType) == "phone" ) {
		            return redirect()->to($file);
		            exit;
		        } else if(strtolower(pathinfo($file, PATHINFO_EXTENSION)) != "pdf") {
		            return redirect()->to($file);
		            exit;
		        } else {


					/******************************/    	
	    			return view('iframeLayout', compact('file'));
	    		}
	    	}else{
	    		$myFile = public_path('fund-upload/').$fundfile['file_path'];
		    	// $headers = ['Content-Type: application/pdf'];
		    	$newName = $fundfile['label_name'].'.'.$fundfile['file_type'];
		    	return response()->download($myFile, $newName);
	    	}			
		} 
    }

    public function interimFundFile($interim_id, $fundFileKey){
    	$fundfile = InterimFundFiles::where('fund_id', $interim_id)->where('url_key',$fundFileKey)->where('status', config('constants.const_active'))->first();
	    	if(empty($fundfile)){
	    		return redirect()->route('home');
	    	}
			
	    	$fundfile = $fundfile->toArray();
	    	if(strtolower($fundfile['file_type']) == 'pdf'){
	    		$file = $fundfile['file_link'];

	    		/****************************************/
	    		$detect = new MobileDetect;
				$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
				$scriptVersion = $detect->getScriptVersion();

	    		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false)
				{
				    // User agent is Google Chrome
				    //$deviceType = 'computer';

					if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
				    {
 
				    }
				    else
				    {
				        $deviceType = 'computer';
				    }

			    }

			    if( trim($deviceType) == "tablet" || trim($deviceType) == "phone" ) {
		            return redirect()->to($file);
		            exit;
		        } else if(strtolower(pathinfo($file, PATHINFO_EXTENSION)) != "pdf") {
		            return redirect()->to($file);
		            exit;
		        } else {


					/******************************/    	
	    			return view('iframeLayout', compact('file'));
	    		}
	    	}else{
	    		$myFile = public_path('interim-fund-upload/').$fundfile['file_path'];
		    	// $headers = ['Content-Type: application/pdf'];
		    	$newName = $fundfile['label_name'].'.'.$fundfile['file_type'];
		    	return response()->download($myFile, $newName);
	    	}
    }

    /*** download us banks holdings **/
    public function downloadUSBanksHoldings(){
    	 
    	$fund = Fund::where('url_key', request('fund'))->where('status', config('constants.const_active'))->first();
    	if(empty($fund) || $fund->coming_soon == config('constants.const_active')){
    		return redirect()->route('home');
    	}

    	$ex_fund_name = "";
	    $ex_fund_name = trim($fund->fund_name);

	    $ex_ticker_symbol = "";
	    $ex_ticker_symbol = trim($fund->fund_ticker);

	    $ex_profile_id = "";
	    $ex_profile_id = trim($fund->fund_profile_id);

	    $ex_url_key = "";
	    $ex_url_key = trim($fund->url_key);

	    $USBANK_FUND_TICKER = strtoupper($ex_ticker_symbol);

	    $stmtHoldDateUSBank = DB::table('fund_holdings_usbanks')->where('account', $USBANK_FUND_TICKER)->max('date'); 
	    		 
	    $date = $stmtHoldDateUSBank;
	    		
		$data = DB::table('fund_holdings_usbanks')->select(
				  	'weightings AS percentage_of_net_assets',
					'security_description as name',
				 	'stock_ticker as identifier', 
				 	'cusip as cusip', 
				  	'shares as shares_held' , 
				  	'market_value' )
		->where('account', $USBANK_FUND_TICKER)
		->where('date', $date)
		->orderByRaw("CAST(market_value AS DECIMAL(10,2)) DESC") 
		->get();

		// $data = json_decode(json_encode($data), true);
		
       	return Excel::download(new USBanksHoldingsExport($data, $USBANK_FUND_TICKER), 'USBanksHoldings.xlsx');
        
    }

    /**************************/

    public function ajaxPerformance(){
    	//print_r(request()->all());
    	switch(request('type')) {
		    case "getPerformanceData":
		        $this->getPerformanceData();
		    break; 
		}
    }

    private function getPerformanceData(){
    	$CUMULATIVE_ARRAY = array();
	    $AVERAGE_ANNUAL_ARRAY = array();
	    $CALENDER_YEAR_ARRAY = array();

    	$stmtPerformanceDates = DB::select('SELECT * FROM tbl_fund_monthly_performance_usbanks WHERE fund_ticker LIKE :fund_ticker AND `date` = :as_of_date ORDER BY fund_ticker DESC', ['fund_ticker' => '%'.request('fticker').'%', 'as_of_date'=> request('performance_date')]);
    	
    	$CUMULATIVE_ARRAY[] = array("", "QUARTER", "1 YEAR", "3 YEAR", "SINCE INCEPTION");
    	// $CUMULATIVE_ARRAY[] = array("", "MTD", "QUARTER", "1 YEAR", "3 YEAR", "SINCE INCEPTION");
    	$AVERAGE_ANNUAL_ARRAY[] = array("", "1Y", "3Y", "5Y", "10Y", "Incept.");

    	// $rowPerformanceDates = json_decode(json_encode($stmtPerformanceDates), true);
    	$rowPerformanceDates = $stmtPerformanceDates;
    	// print_r($rowPerformanceDates);

    	foreach ($rowPerformanceDates as $objPerformanceDates) {
	        $INN_CUM_ARRAY = array();
	        $INN_ANN_ARRAY = array();
	        if(stristr($objPerformanceDates->fund_ticker, "NAV")) {
	            $INN_CUM_ARRAY[] = "Fund NAV"; 
	            $INN_ANN_ARRAY[] = "Total Return (%)";              
	        } else if(stristr($objPerformanceDates->fund_ticker, "MKT")) {
	            $INN_CUM_ARRAY[] = "Market Price"; 
	            $INN_ANN_ARRAY[] = "Market Price (%)"; 
	        }

	        $INN_CUM_ARRAY[] = ($objPerformanceDates->one_month == "-" || $objPerformanceDates->one_month == "") ? "xx.xx" : $objPerformanceDates->one_month;
	        
	        $INN_CUM_ARRAY[] = ($objPerformanceDates->three_month == "-" || $objPerformanceDates->three_month == "") ? "xx.xx" : $objPerformanceDates->three_month;
	        
	        $INN_CUM_ARRAY[] = ($objPerformanceDates->one_year == "-" || $objPerformanceDates->one_year == "") ? "xx.xx" : $objPerformanceDates->one_year;
	        
	        $INN_CUM_ARRAY[] = ($objPerformanceDates->three_year == "-" || $objPerformanceDates->three_year == "") ? "xx.xx" : $objPerformanceDates->three_year;
	         
	        $INN_CUM_ARRAY[] = ($objPerformanceDates->since_inception_cumulative == "-" || $objPerformanceDates->since_inception_cumulative == "") ? "xx.xx" : $objPerformanceDates->since_inception_cumulative; 

	        $INN_ANN_ARRAY[] = ($objPerformanceDates->one_year == "-" || $objPerformanceDates->one_year == "") ? "xx.xx" : $objPerformanceDates->one_year;
	        $INN_ANN_ARRAY[] = ($objPerformanceDates->three_year == "-" || $objPerformanceDates->three_year == "") ? "xx.xx" : $objPerformanceDates->three_year;
	        $INN_ANN_ARRAY[] = ($objPerformanceDates->five_year == "-" || $objPerformanceDates->five_year == "") ? "xx.xx" : $objPerformanceDates->five_year;
	        $INN_ANN_ARRAY[] = "xx.xx";
	        $INN_ANN_ARRAY[] = ($objPerformanceDates->since_inception_annualized == "-" || $objPerformanceDates->since_inception_annualized == "") ? "xx.xx" : $objPerformanceDates->since_inception_annualized;

	        $CUMULATIVE_ARRAY[] = $INN_CUM_ARRAY;
	        $AVERAGE_ANNUAL_ARRAY[] = $INN_ANN_ARRAY;
	    }


	    /** calendar year data **/
	    $stmtPDatesMinYear = DB::select(' SELECT MIN(YEAR(`date`)) as min_year FROM tbl_fund_monthly_performance_usbanks WHERE fund_ticker LIKE :fund_ticker', ['fund_ticker' => '%'.request('fticker').'%' ]);

	    $objMinYear = json_decode(json_encode($stmtPDatesMinYear),true);
	    $MIN_YEAR = isset($objMinYear[0]['min_year']) ? $objMinYear['0']['min_year'] : '';

	    if($MIN_YEAR != "") {

	        if($MIN_YEAR != date("Y")) {
	            $MAX_YEAR = date("Y") - 1;

	            $CALENDER_YEAR_ARRAY[0][] = "";
	            for($ik = $MIN_YEAR; $ik <= $MAX_YEAR; $ik++) {

	                $CALENDER_YEAR_ARRAY[0][] = $ik;

	                $stmtPerformanceDates = DB::select('SELECT * FROM tbl_fund_monthly_performance_usbanks WHERE fund_ticker LIKE :fund_ticker AND YEAR(`date`) = :dyear AND MONTH(`date`) = 12 ORDER BY fund_ticker DESC ', ['fund_ticker' => '%'.request('fticker').'%', 'dyear'=> $ik]);

	                $rowPerformanceDates = $stmtPerformanceDates;

	               
	                foreach($rowPerformanceDates as $objPerformanceDates) {
	                    $INN_ANN_ARRAY = array();
	                    if(stristr($objPerformanceDates->fund_ticker, "NAV")) {
	                        
	                        $INN_ANN_ARRAY[] = "Total Return (%)";              
	                    } else if(stristr($objPerformanceDates->fund_ticker, "MKT")) {

	                        $INN_ANN_ARRAY[] = "Market Price (%)"; 
	                    }

	                    $INN_ANN_ARRAY[] = ($objPerformanceDates->one_year == "-" || $objPerformanceDates->one_year == "") ? "xx.xx" : $objPerformanceDates->one_year;
	                    
	                    $CALENDER_YEAR_ARRAY[] = $INN_ANN_ARRAY;
	                }
	            }
	        } else {

	            $CALENDER_YEAR_ARRAY[0][] = ""; 
	            $CALENDER_YEAR_ARRAY[0][] = $MIN_YEAR - 1;

	            $INN_ANN_ARRAY = array();
	            $INN_ANN_ARRAY[] = "Total Return (%)";  
	            $INN_ANN_ARRAY[] = "xx.xx";
	            $CALENDER_YEAR_ARRAY[] = $INN_ANN_ARRAY;

	            $INN_ANN_ARRAY = array(); 
	            $INN_ANN_ARRAY[] = "Market Price (%)"; 
	            $INN_ANN_ARRAY[] = "xx.xx";
	            $CALENDER_YEAR_ARRAY[] = $INN_ANN_ARRAY;

	        }

	    }
	    $RETURN_ARRAY = array();
	    $RETURN_ARRAY['CUMULATIVE_ARRAY'] = $CUMULATIVE_ARRAY;
	    $RETURN_ARRAY['CALENDER_YEAR_ARRAY'] = $CALENDER_YEAR_ARRAY;
	    $RETURN_ARRAY['AVERAGE_ANNUAL_ARRAY'] = $AVERAGE_ANNUAL_ARRAY;

	    echo json_encode($RETURN_ARRAY); 
    }


    public function productList(){
    	$content = FundOutcomeDisclosure::first();
    	$funds = Fund::where('is_outcome_product',true)->where('status', config('constants.const_active'))->with('fundData','fundDataPricing','fundDistribution','prospectusFile','fundHoldings','fundOutcome','fundCurrentOutcome')->orderBy('position','ASC')->paginate(100);
    	return view('product-list',compact('content','funds'));
    }

    public function exportStructredETFcsv(){
    	$fund = Fund::where('is_outcome_product',true)->where('status', config('constants.const_active'))->get();

    	$outcome_date = \DB::table('sp_usbanks')->orderBy('date','DESC')->first();
    	$AS_OF_DATE = 'As of '.date('m/d/Y', strtotime($outcome_date->date));
    	$data =  array();
    	foreach ($fund as $key => $value) {
    		$temp = Fund::outcomeETF($value->fund_ticker);
    		if($temp != false)
    			$data[] = $temp;
    	} 
        
    	if(count($data) <= 0){
    		return redirect()->to('/');
    	}
// 		dd(collect($data));
       	return Excel::download(new StructuredOutcomeEtfExport(collect($data), $AS_OF_DATE), 'StructuredOutcomeETFs.csv');
    }

}


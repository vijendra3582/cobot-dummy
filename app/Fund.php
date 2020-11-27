<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $table="fund";
    protected $guarded =['id'];

    protected $appends = ['fund_image_disp','banner_image_disp', 'premium_discount_file_link','holdings_file_link', 'distribution_schedule_file_link','fund_banner_logo_disp'];
    const ACTIVE = 1;
    const INACTIVE = 0;
    const DELETED = 2;

    public function getFundImageDispAttribute(){
        return !empty($this->fund_logo) ? asset('/fund-upload/'.$this->fund_logo) : '';
    }

    public function getFundBannerLogoDispAttribute(){
        return !empty($this->fund_banner_logo) ? asset('/fund-upload/'.$this->fund_banner_logo) : '';
    }

    public function getBannerImageDispAttribute(){
    	return !empty($this->banner_image) ? asset('/fund-upload/'.$this->banner_image) : '';
    }

    public function getPremiumDiscountFileLinkAttribute(){
    	return !empty($this->premium_discount_file) ? asset('/fund-upload/'.$this->premium_discount_file) : '';
    }

    public function getHoldingsFileLinkAttribute(){
        return !empty($this->holdings_file) ? asset('/fund-upload/'.$this->holdings_file) : '';
    }

    public function getDistributionScheduleFileLinkAttribute(){
        return !empty($this->distribution_schedule_file) ? asset('/fund-upload/'.$this->distribution_schedule_file) : '';
    }

    /*************/
    public function fundData(){
        return $this->hasMany('App\FundData','fund_id', 'id')->where('status', config('constants.const_active'))->orderBy('position','ASC');
    }

    public function fundDataPricing(){
        return $this->hasMany('App\FundDataPricing','fund_id','id')->where('status', config('constants.const_active'))->orderBy('position','ASC');
    }

    public function fundDistribution(){
        return $this->hasMany('App\FundDistribution','fund_id','id');
    }

    public function fundOutcome(){
        return $this->hasMany('App\fundOutcomePeriodValues','fund_id','id');
    }

    public function fundCurrentOutcome(){
        return $this->hasMany('App\fundCurrentOutcomePeriodValues','fund_id','id');
    }

    public function fundFiles(){
        return $this->hasMany('App\FundFiles','fund_id','id')->where('status', config('constants.const_active'))->orderBy('position','ASC');
    }

    public function fundHoldings(){
        return $this->hasMany('App\FundHoldings','fund_id','id');
    }

    public function fundPerformance(){
        return $this->hasMany('App\FundPerformance','fund_id','id');
    }

    // 

    public static function outcomeETF($ticker = ''){
        if(empty($ticker)){
            return false;
        }

        $fund = Fund::where('fund_ticker', $ticker)->where('is_outcome_product',true)->where('status', config('constants.const_active'))->with('fundOutcome','fundCurrentOutcome')->first();
        
        if(!empty($fund)){
            $fund = $fund->toArray();
        }else{
            return false;
        }
        
        if(empty($fund['fund_current_outcome']) ){
            return false;
        }

        $TOTAL_BUFFER = (float)$fund['fund_current_outcome'][0]['total_buffer'];
        $TREASURY_YIELD = (float)$fund['fund_current_outcome'][0]['treasury_yield'];
        $ETF_STARTING_NAV = (float)$fund['fund_current_outcome'][0]['etf_starting_nav'];
        $S_P_YEAR_START_VALUE = (float)$fund['fund_current_outcome'][0]['s_p_year_start_value'];
        $S_P_REFERENCE_VALUE = (float)$fund['fund_current_outcome'][0]['s_p_reference_value'];

        /***/
        

        if($fund['fund_current_outcome'][0]['override_etf_current_price'] == true)
        {
            $ETF_CURRENT_NAV = $fund['fund_current_outcome'][0]['etf_current_price'];
        }else{
            $daily_nav_usbank = \DB::table('fund_daily_nav_usbanks')->where('fund_ticker', $fund['fund_ticker'])->orderBy('rate_date','DESC')->first();


            if(empty($daily_nav_usbank)){
                return false;
            }

            $ETF_CURRENT_NAV = !empty($daily_nav_usbank) ? (float)$daily_nav_usbank->nav : 0.00;

        }    

        /****/
        $sp_usbanks = \DB::table('sp_usbanks')->orderBy('date','DESC')->first();
        
        if(empty($sp_usbanks)){
            return false;
        }

        $YTD_SP_RETURN =!empty($sp_usbanks) ? (float)($sp_usbanks->ytd_return/100) : 0.00;
        // $INDEX_RETURN = !empty($sp_usbanks) ? (float)$sp_usbanks->ytd_return : 0.00;

        $OUTCOME_START_DATE = !empty($fund['launch_date']) ? date('Y-m-d', strtotime($fund['launch_date'])) : date('Y').'-07-01';

        $date = date('Y-m-d');
        $CURRENT_DATE =  $date < date('Y-m-d',strtotime($OUTCOME_START_DATE)) ? $OUTCOME_START_DATE : $date ;
        
        //$OUTCOME_END_DATE =  date('Y-m-d', strtotime($OUTCOME_START_DATE.'+1 years')) ;
        $OUTCOME_END_DATE = !empty($fund['end_date']) ? date('Y-m-d', strtotime($fund['end_date'])) : date('Y-m-d', strtotime($OUTCOME_START_DATE.'+1 years'));
        
        /****/
        $PERIOD_RETURN = ($ETF_STARTING_NAV != 0) ? ($ETF_CURRENT_NAV/$ETF_STARTING_NAV)-1 : '0.00';

        /****/
        if($fund['fund_current_outcome'][0]['override_spx_period_return'] == true){
            $SPX_PERIOD_RETURN = $fund['fund_current_outcome'][0]['spx_period_return'];
        }else{
            $CURRENT_SP_LEVEL = $S_P_YEAR_START_VALUE * (1+ $YTD_SP_RETURN);

            $SPX_PERIOD_RETURN = $S_P_REFERENCE_VALUE != 0 ? ($CURRENT_SP_LEVEL/$S_P_REFERENCE_VALUE) -1 : '0.00';

        }

        
        if(!empty($fund['fund_current_outcome'][0]['override_downside_to_floor_of_buffer'] == true)){
            $S_P_DOWNSIDE_TO_FLOOR_OF_BUFFER = !empty($fund['fund_current_outcome'][0]['downside_to_floor_of_buffer']) && ((float)($fund['fund_current_outcome'][0]['downside_to_floor_of_buffer']) < 0) ? $fund['fund_current_outcome'][0]['downside_to_floor_of_buffer'].'%' : 'N/A';  
        }else{
            $S_P_DOWNSIDE_TO_FLOOR_OF_BUFFER = ($S_P_REFERENCE_VALUE * (1+$TOTAL_BUFFER) - $CURRENT_SP_LEVEL) / $CURRENT_SP_LEVEL;
            if($S_P_DOWNSIDE_TO_FLOOR_OF_BUFFER >= 0){
                $S_P_DOWNSIDE_TO_FLOOR_OF_BUFFER = 'N/A';
            }else{
                $S_P_DOWNSIDE_TO_FLOOR_OF_BUFFER = round($S_P_DOWNSIDE_TO_FLOOR_OF_BUFFER*100,2).'%';
            }
        }


        /*****/    
        $INDEX_RETURN = !empty($SPX_PERIOD_RETURN) ? (float)$SPX_PERIOD_RETURN : '0.00';
        /****/
        /*if($INDEX_RETURN >= 0 ){

            $REMAINING_BUFFER = $TOTAL_BUFFER != 0 ?  (-1)*$TOTAL_BUFFER : '0.00';

            $DOWNSIDE_TO_BUFFER = ($ETF_STARTING_NAV - $ETF_CURRENT_NAV)/ $ETF_CURRENT_NAV;

        
        }elseif($INDEX_RETURN < 0){

            $REMAINING_BUFFER = ((-1)*($PERIOD_RETURN-($SPX_PERIOD_RETURN - $TOTAL_BUFFER))*$ETF_STARTING_NAV/$ETF_CURRENT_NAV)+$TREASURY_YIELD;

            $DOWNSIDE_TO_BUFFER = '0.00';

        }else{

            $REMAINING_BUFFER = '0.00';

            $DOWNSIDE_TO_BUFFER = '0.00';
        }*/
            
            if($INDEX_RETURN >= 0 ){

                $REMAINING_BUFFER = $TOTAL_BUFFER != 0 ?  round(((-1)*$TOTAL_BUFFER)*100 , 2)  .'%' : 'N/A';

                $DOWNSIDE_TO_BUFFER = ($ETF_STARTING_NAV - $ETF_CURRENT_NAV)/ $ETF_CURRENT_NAV;

                $DOWNSIDE_TO_BUFFER = round($DOWNSIDE_TO_BUFFER*100, 2).'%';
            
            }elseif($INDEX_RETURN < 0 && $INDEX_RETURN >= $TOTAL_BUFFER){

                $REMAINING_BUFFER = ((-1)*($PERIOD_RETURN-($SPX_PERIOD_RETURN - $TOTAL_BUFFER))*$ETF_STARTING_NAV/$ETF_CURRENT_NAV)+$TREASURY_YIELD;

                $REMAINING_BUFFER = round($REMAINING_BUFFER*100, 2).'%';

                $DOWNSIDE_TO_BUFFER = 'N/A';

            }elseif($INDEX_RETURN < $TOTAL_BUFFER){
                
                $REMAINING_BUFFER = ((-1)*($PERIOD_RETURN-($SPX_PERIOD_RETURN - $TOTAL_BUFFER)/(1-$TOTAL_BUFFER)) * $ETF_STARTING_NAV/$ETF_CURRENT_NAV)+$TREASURY_YIELD;

                $REMAINING_BUFFER = round($REMAINING_BUFFER*100, 2).'%';

                $DOWNSIDE_TO_BUFFER = 'N/A';
                
            }else{

                $REMAINING_BUFFER = 'N/A';

                $DOWNSIDE_TO_BUFFER = 'N/A';
            } 

        
        /****/
        //$DOWNSIDE_BEFORE_BUFFER = $SPX_PERIOD_RETURN >= 0 ? $SPX_PERIOD_RETURN : '0.00';

        /****/
        $DATE_DIFF = strtotime($OUTCOME_END_DATE) - strtotime($CURRENT_DATE);

        $REMAINING_OUTCOME_PERIOD = round($DATE_DIFF / (60 * 60 * 24)) +1 ;

        /****/
        $FUND_RETURN = floatval($ETF_CURRENT_NAV) - floatval($ETF_STARTING_NAV);

        $INDEX = !empty($sp_usbanks) ? $sp_usbanks->fund_name : 'S&P';

        $returnArr = [
            "ticker"=>$fund['fund_ticker'],
            "name"=>$fund['fund_name'],
            "series"=>$fund['product_series'],
            
            "fund_price"=>(string)(($fund['fund_current_outcome'][0]['override_etf_current_price'] == true) ? $fund['fund_current_outcome'][0]['etf_current_price'] : $ETF_CURRENT_NAV),
            
            "fund_return"=>round($PERIOD_RETURN*100, 2).'%',
            
            "index"=>$INDEX,
            
            "index_return"=> round($INDEX_RETURN*100, 2).'%',
            
            "upside_participation"=>!empty($fund['fund_current_outcome'][0]['upside_participation']) ? $fund['fund_current_outcome'][0]['upside_participation'] : '-',

            "remaining_buffer"=> ($fund['fund_current_outcome'][0]['override_remaining_buffer'] == true) ? (!empty($fund['fund_current_outcome'][0]['remaining_buffer']) ? $fund['fund_current_outcome'][0]['remaining_buffer'] : 'N/A') : $REMAINING_BUFFER,
            
            "downside_to_buffer"=>($fund['fund_current_outcome'][0]['override_downside_before_buffer'] == true) ? (!empty($fund['fund_current_outcome'][0]['downside_before_buffer']) ? $fund['fund_current_outcome'][0]['downside_before_buffer'] : 'N/A') : $DOWNSIDE_TO_BUFFER,
            
            "s_p_downside_to_floor_of_buffer" => $S_P_DOWNSIDE_TO_FLOOR_OF_BUFFER,
            "remaining_outcome_period" => (string)(($fund['fund_current_outcome'][0]['override_remaining_outcome_period'] == true) ? $fund['fund_current_outcome'][0]['remaining_outcome_period'] : round($REMAINING_OUTCOME_PERIOD,2))
        ];
        return $returnArr;
    }

    public function prospectusFile(){
        return $this->hasOne('App\FundFiles','fund_id','id')->where('status', config('constants.const_active'))->where('label_name','Prospectus');
    }
}

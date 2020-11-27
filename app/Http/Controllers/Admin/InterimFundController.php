<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InterimFund;
use App\InterimFundFiles;
use App\InterimFundData;


class InterimFundController extends Controller
{
    //
    public function editFund() { 
        $result = InterimFund::where('status', '!=', InterimFund::DELETED)->with("fundData")->with("fundFiles")->first();
    	if(!empty($result)){
            $result = $result->toArray();
    		$length = 1;
    	}else{
    		$length = 0;
        }
        
        // dd($result);

        $returnArr = array("SUCCESS" => 1, "data"=> $result, "length" => $length);
        echo json_encode($returnArr); 
    }

    private function processImage($file, $type, $destination ) {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type."-".time()."-" . $file;
        /// rename image ============
        $SOURCE_PATH = public_path(\Config::get("constants.temp_folder")) . $file;
        $DESTINATION_PATH = public_path($destination) . $NEW_FILE_NAME;
        rename($SOURCE_PATH, $DESTINATION_PATH);
        
        return $NEW_FILE_NAME;
    
    }

    public function saveFund() {
        $interimfund = InterimFund::find(1);
        $interimfund->fund_name = request("fund_name");
        $interimfund->sub_title = request("sub_title");
        $interimfund->fund_ticker = request("fund_ticker");
        $interimfund->fund_description = request("fund_description");
        $interimfund->fund_launch_description = request("fund_launch_description");
        $interimfund->fund_detail_notes = request("fund_detail_notes");
        $interimfund->fund_disclosure = request("fund_disclosure");

        $interimfund->url_key = request("url_key");
        $interimfund->meta_title = request("meta_title");
        $interimfund->meta_description = request("meta_description");
        $interimfund->meta_keyword = request("meta_keyword");
        $interimfund->status = request("status");


        if(!empty(request("banner_image")))
        {
			if(!empty($interimfund->banner_image) && \File::exists(public_path(\Config::get("constants.interim_fund_folder")).$interimfund->banner_image))
	            \File::delete(public_path(\Config::get("constants.interim_fund_folder")) . $interimfund->banner_image);
	        	        
	        $NEW_FILE_NAME = $this->processImage(request('banner_image'), 'banner', \Config::get("constants.interim_fund_folder"));

            // $interimfund->update([
            //     "banner_image" => $NEW_FILE_NAME
            // ]);
            $interimfund->banner_image = $NEW_FILE_NAME;
        }


        if($interimfund->save()) {
            $this->saveFundsData($interimfund->id, request("allRowsFD"));
            $this->saveFundsFiles($interimfund->id, request("allRowsFF"));

            $returnArr = array("SUCCESS" => 1);
        } else {
            $returnArr = array("SUCCESS" => 0);
        }

        echo json_encode($returnArr);  
        
    }

    public function saveFundsData($fund_id, $row) {
        ///// delete all ================
        InterimFundData::where("fund_id", $fund_id)->delete();

        if(!empty($row)) {
            $pos = 1;
            foreach($row as $rs) { 
                if(trim($rs['data_head']) != "") {
                    $interimfunddata = new InterimFundData;
                    $interimfunddata->fund_id = $fund_id;
                    $interimfunddata->data_head = $rs['data_head'];
                    $interimfunddata->data_value = $rs['data_value'];
                    $interimfunddata->position = $pos;
                    $interimfunddata->status = $rs['status'];
                    $interimfunddata->save();
                    $pos++;
                }
            }
        }
    }

    public function saveFundsFiles($fund_id, $row) {
        foreach ($row as $key => $value) {
    			 
            $upd = InterimFundFiles::updateOrCreate(['id' => $value['id']],[
                "fund_id" => $fund_id,
                "label_name"=> $value['label_name'],
                "position" => $key,
                "status"=> $value['status'],
                "url_key"=>!empty($value['url_key']) ? $value['url_key'] : strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', trim(preg_replace('/\s+/', ' ', $value['label_name'])))))		            
            ]);

           
            if(!empty($value['file_path']))
            {
                if(!empty($upd->file_path) && \File::exists(public_path(\Config::get("constants.interim_fund_folder")).$upd->file_path))
                      \File::delete(public_path(\Config::get("constants.interim_fund_folder")) . $upd->file_path);
                        
                $NEW_FILE_NAME = $this->processImage($value['file_path'], 'fund_files', \Config::get("constants.interim_fund_folder"));

                $upd->update([
                    "file_path" => $NEW_FILE_NAME,
                    'file_type' => $value['file_extension']
                ]);
            }
 
            if(empty($value['id']))
                $upd->update([
                    "add_ip" => request()->ip()
                ]);
            else
                $upd->update([
                    "update_ip" => request()->ip()
                ]);
        }
    }

    public function removeImage() {
        if(request('field') === 'banner_image'){
            if(!empty(request('file')) && \File::exists(public_path(\Config::get("constants.interim_fund_folder")). request('file'))){ 
                \File::delete(public_path(\Config::get("constants.temp_folder")) . request('file'));
            }else{
                $fund = InterimFund::first();
                if(!empty($fund)){
                    if(!empty($fund->banner_image) && \File::exists(public_path(\Config::get("constants.interim_fund_folder")).$fund->banner_image))
                    \File::delete(public_path(\Config::get("constants.interim_fund_folder")) . $fund->banner_image);

                    $fund->update(['banner_image'=> null]);
                }
            }
        }

        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }

    public function removeFile() {
        if(!empty(request('file')) && \File::exists(public_path(\Config::get("constants.interim_fund_folder")). request('file'))){ 
            \File::delete(public_path(\Config::get("constants.temp_folder")) . request('file'));
        }else{
            $fund = InterimFundFiles::where("id", request("id"))->first();
            if(!empty($fund)){
                if(!empty($fund->file_path) && \File::exists(public_path(\Config::get("constants.interim_fund_folder")).$fund->file_path))
                \File::delete(public_path(\Config::get("constants.interim_fund_folder")) . $fund->file_path);

                $fund->update(['file_path'=> null]);
            }
        }
 

        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }

    public function deleteFundFile() {
        $fundFile = InterimFundFiles::where('id',request('id'))->first();
    	if(!empty($fundFile)){
    		$fundFile->update(['status'=> \Config::get('constants.const_deleted')]);
    	}

    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);  
    }
}

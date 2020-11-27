<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FundFiles;
use App\Fund;
class FundFilesController extends Controller
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

    /*************************************************/

    public function fundFiles(){
        $list = FundFiles::where('fund_id',request('fund_id'))->where('status', '!=', \Config::get("constants.const_deleted"))->orderBy('position','ASC')->get();
        if(!empty($list)){
        	$list=$list->toArray();
        }

        $RETURN_ARRAY = array(); 
        $RETURN_ARRAY['data'] = $list; 
        echo json_encode($RETURN_ARRAY);
    }

    public function saveFundFiles(){


    	if(count(request('allRows')) > 0){
    		
    		foreach (request('allRows') as $key => $value) {
    			 
    			$upd = FundFiles::updateOrCreate(['id' => $value['id']],[
		            "fund_id" => request('fund_id'),
		            "label_name"=> $value['label_name'],
		            "position" => $key,
		            "status"=> $value['status'],
		            "url_key"=>!empty($value['url_key']) ? $value['url_key'] : strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', trim(preg_replace('/\s+/', ' ', $value['label_name'])))))		            
		        ]);

		       
		        if(!empty($value['file_path']))
		        {
					if(!empty($upd->file_path) && \File::exists(public_path(\Config::get("constants.fund_folder")).$upd->file_path))
		                  \File::delete(public_path(\Config::get("constants.fund_folder")) . $upd->file_path);
		        	        
		        	$NEW_FILE_NAME = $this->processImage($value['file_path'], 'fund_files', \Config::get("constants.fund_folder"));

		            $upd->update([
		                "file_path" => $NEW_FILE_NAME,
		                'file_type' => $value['file_extension']
		            ]);
		        }

		        // if(empty($value['id'])){
		        // 	$max = FundFiles::where('id', '!=', $value['id'])->max('position');
		        // 	$upd->update([
		        //         "position" => $max+1
		        //     ]);
		        // }

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

    /***************/
    public function delete(){
    	$fundFile = FundFiles::where('id',request('id'))->where('fund_id', request('fund_id'))->first();
    	if(!empty($fundFile)){
    		$fundFile->update(['status'=> \Config::get('constants.const_deleted')]);
    	}

    	$returnArr = array("SUCCESS" => 1);
    	echo json_encode($returnArr);  
    }

    public function removeFiles(){
    	$fundFile = FundFiles::where('id',request('id'))->where('fund_id', request('fund_id'))->first();
        if(!empty($fundFile)) {
            if(!empty($fundFile->file_path) && \File::exists(public_path(\Config::get("constants.fund_folder")).$fundFile->file_path))
                \File::delete(public_path(\Config::get("constants.fund_folder")) . $fundFile->file_path);
            
            $fundFile->update(['file_path'=> null,
                               'file_type'=> null 
                            ]);
        }

        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);  
    }
}

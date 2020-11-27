<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Resource;
use App\ResourceDisclosure;
use App\ResourceCategory;
class ResourceController extends Controller
{	
    private $disclosure_id;

    public function __construct()
    {
        $this->disclosure_id = 1001; 
    }
	/*** function to move file from temp to folder **/

     private function processImage($file, $type, $destination ) {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type."-".time()."-" . $file;
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
    /** *
    * list resource categories
    * @return categories array
    **/
    public function listCategories(){
        $list = ResourceCategory::where('status', config('constants.const_active'))->orderBy('position','ASC')->get();
        $returnArr = array("SUCCESS" => 1, "data"=> $list);
        echo json_encode($returnArr);
    }
    /***********************/

    public function listResource($page){
    	//print_r(request()->all());
    	$skip = ($page-1)*request('perpage');
        $list = Resource::where('status','!=', Resource::DELETED)->orderBy('position','ASC');

        $searchedFields = [
            'search_title' => request('search_title') ? request('search_title') : '', 
            'search_status'=> request('search_status') != '' ? request('search_status') : '' 
        ];
        if(!empty(request('search_title'))){
            $list = $list->where('title', 'like', '%'.request('search_title').'%');      
        }
        if(request('search_status') != '' ){
            $list = $list->where('status', request('search_status'));        
        }
        
             
        $total_records = $list->count();
        $list = $list->skip($skip)->take(request('perpage'))->get()->toArray();
    
        $returnArr = array("SUCCESS" => 1, "data"=> $list, 'searchedFields'=>$searchedFields, 'total_records'=> $total_records);
        echo json_encode($returnArr);
    }

    /*********/
     public function saveResource(){ 

     	// dd(request()->all());

        if (!empty(request('date'))) {
            $a_exc = explode('-', request('date'));
            $asOfDate = $a_exc[2].'-'.$a_exc[0].'-'.$a_exc[1];
        }else{
            $asOfDate = null;
        }

        $upd = Resource::updateOrCreate(['id' => request('id')],[
            "title" => request('title'),
            "sub_title" => request('sub_title'),
            "file_type" => request('file_type'),
            "short_description" => request('short_description'),
            "date" => $asOfDate,
            "resource_type" => request('resource_type'),
            "status" => request('status'),
            'set_at_homepage' => request('set_at_homepage') 
        ]);

        if((request('resource_type') == 'FILE') && !empty(request("resource_file")))
        {
            if(!empty($upd->resource_file) && \File::exists(public_path(\Config::get("constants.resource_folder")).$upd->resource_file)){
                \File::delete(public_path(\Config::get("constants.resource_folder")) . $upd->resource_file);
            }
            
            $NEW_FILE_NAME = $this->processImage(request('resource_file'), 'RESOURCE_FILE', \Config::get("constants.resource_folder"));

            $upd->update([
                "resource_file" => $NEW_FILE_NAME
            ]);
        }
        if((request('resource_type') == 'URL') && !empty(request("resource_url")))
        {
            $upd->update([
                "resource_url" => request('resource_url')
            ]);
        }
        if((request('resource_type') == 'VIDEO'))
        {
            if(!empty(request('video_file'))){
                if(!empty($upd->video_file) && \File::exists(public_path(\Config::get("constants.resource_folder")).$upd->video_file)){
                    \File::delete(public_path(\Config::get("constants.resource_folder")) . $upd->video_file);
                }
                
                $NEW_FILE_NAME = $this->processImage(request('video_file'), 'RESOURCE_VIDEO', \Config::get("constants.resource_folder"));

                $upd->update([
                    "video_file" => $NEW_FILE_NAME
                ]);
            }

            if(!empty(request('video_image'))){
                if(!empty($upd->video_image) && \File::exists(public_path(\Config::get("constants.resource_folder")).$upd->video_image)){
                    \File::delete(public_path(\Config::get("constants.resource_folder")) . $upd->video_image);
                }
                
                $NEW_FILE_NAME = $this->processImage(request('video_image'), 'RESOURCE_POSTER', \Config::get("constants.resource_folder"));

                $upd->update([
                    "video_image" => $NEW_FILE_NAME
                ]);
            }
        }

        if(empty(request('id'))){
            $last = Resource::max('position'); 
            $upd = $upd->update(['position' => $last+1 ]);
        }
                

        $returnArr = array("SUCCESS" => 1, "DATA"=> $upd);
        echo json_encode($returnArr);  
    }

    /******************************************************************/
    public function editResource(){
        $result = Resource::where('id', request('id'))->where('status', '!=', Resource::DELETED)->first()->toArray();
        $returnArr = array("SUCCESS" => 1, "data"=> $result, "length" => 1);
        echo json_encode($returnArr); 
    }

    /*****************************************************************/
    public function removeImage(){
        if(!empty(request('field'))){
            if(\File::exists(public_path(\Config::get("constants.temp_folder")) . request('field'))){
                \File::delete(public_path(\Config::get("constants.temp_folder")) . request('field'));
            }
            $returnArr = array("SUCCESS" => 1);
        }elseif(!empty(request('id'))){
            $res = 0;
            $resource = Resource::where('id', request('id'))->first();

            switch (request('type')) {
                case 'FILE':
                    if(!empty($resource) && !empty($resource->resource_file)){
                        if(\File::exists(public_path(\Config::get("constants.resource_folder")) . $resource->resource_file)){
                            \File::delete(public_path(\Config::get("constants.resource_folder")) . $resource->resource_file);
                        }
                        $res = $resource->update(['resource_file' => null]);
                    }
                    break;
                case 'VIDEO':
                    if(!empty($resource) && !empty($resource->video_file)){
                        if(\File::exists(public_path(\Config::get("constants.resource_folder")) . $resource->video_file)){
                            \File::delete(public_path(\Config::get("constants.resource_folder")) . $resource->video_file);
                        }
                        $res = $resource->update(['video_file' => null]);
                    }
                    break;

                case 'VIDEO_POSTER':
                    if(!empty($resource) && !empty($resource->video_image)){
                        if(\File::exists(public_path(\Config::get("constants.resource_folder")) . $resource->video_image)){
                            \File::delete(public_path(\Config::get("constants.resource_folder")) . $resource->video_image);
                        }
                        $res = $resource->update(['video_image' => null]);
                    }
                    break;
                default:
                    # code...
                    break;
            }

            $returnArr = array("SUCCESS" => 1);   
        }
        echo json_encode($returnArr); 
    }

    /*****************************************************/

    public function deleteResource(){
        $resource = Resource::where('id', request('resource_id'))->first();
        if(!empty($resource)){
            $resource->update(['status' => Resource::DELETED]);
        }
        $returnArr = array("SUCCESS" => 1);  
        echo json_encode($returnArr);  
    }

    /*****************************************************/
    public function updateStatus(){
        $resource = Resource::where('id', request('resource_id'))->first();
        if(!empty($resource)){
            $resource->update(['status' => request('resource_status')]);
        }
        $returnArr = array("SUCCESS" => 1);  
        echo json_encode($returnArr);  
    }
    /*******************************************************/
    public function deleteAll(){
        $ids_to_delete = array_map(function($item){ return $item['id']; }, request('DIDS'));
        Resource::whereIn('id', $ids_to_delete)->update(['status'=> Resource::DELETED]); 
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr); 
    }
    /*******************************************************/

    public function savePosition(){
        foreach(request('data') as $k=> $val){
            $res = Resource::where('id', $val['id'])->first();
            if(!empty($res))
                $res->update(['position'=> $val['position']]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
    /**********/
    /******************************************************************/
    public function resourceDisclosureEdit(){
        $row = ResourceDisclosure::first()->toArray(); 
        $returnArr = array("SUCCESS" => 1, "DATA"=> $row); 
        echo json_encode($returnArr);
    }

    public function saveResourceDisclosure(){
        $upd = ResourceDisclosure::updateOrCreate(['id' => $this->disclosure_id],[
            "title" => request("title"),
            // "description" => request("description"),
            'disclosure' => request('disclosure'),
            // "short_description" => request('short_description'),  
            "meta_title" => !empty(request('meta_title')) ? request('meta_title') : request('title'),
            "meta_keyword" => request('meta_keyword'),
            "meta_description" => request('meta_description')
        ]);

        if(!empty(request("banner_img")))
        {
            $NEW_FILE_NAME = $this->processImage(request('banner_img'), 'BANNER', \Config::get("constants.resource_folder"));

            $upd->update([
                "banner_img" => $NEW_FILE_NAME
            ]);
        }

        $returnArr = array("SUCCESS" => 1, "DATA"=> $upd);
        echo json_encode($returnArr);    
    }

    public function removeResourceDisclosure(){
        if(!empty(request('field'))){
            if(\File::exists(public_path(\Config::get("constants.temp_folder")) . request('field'))){
                \File::delete(public_path(\Config::get("constants.temp_folder")) . request('field'));
            }
            $returnArr = array("SUCCESS" => 1);
        }else{
            $res = 0;
            $content = ResourceDisclosure::first();
            if(!empty($content))
            { 
                $file = $content->banner_img ? $content->banner_img : '';
                if(!empty($file)){
                    if(\File::exists(public_path(\Config::get("constants.resource_folder")).$file)){
                        \File::delete(public_path(\Config::get("constants.resource_folder")).$file);
                    }
                } 
                $res = $content->update(['banner_img'=> '']);
            }
            $returnArr = array("SUCCESS" => ($res == true ? 1 : 0));   
        }
        echo json_encode($returnArr); 
    }
}

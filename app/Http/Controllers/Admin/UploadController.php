<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
class UploadController extends Controller
{
    public function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}

    public function chunk_upload() {
 
    	if(request('_chunkNumber') != "") {
 
    		$uid = request("uuid");
		    $filename = request("filename"); 
		    
		    // the file is uploaded piece by piece, chunk mode
		    $current_chunk_number = request('_chunkNumber');
		    $chunk_size = request("_chunkSize");
		    $total_size = request("_totalSize");

		    $upload_folder = public_path('temp/');
		    $total_chunk_number = ceil($total_size / $chunk_size);

		    $path = public_path().'/temp';
			    if(!File::exists($path)) {
					File::makeDirectory($path, $mode = 0777, true, true);
				}


		    move_uploaded_file($_FILES['file']['tmp_name'], $upload_folder . $uid . '.part' . $current_chunk_number);
		      
		    // the last chunk of file has been received
		    if ($current_chunk_number == ($total_chunk_number - 1)) {
	        	
	        	if(file_exists($upload_folder . $filename) && is_file($upload_folder . $filename)) {
	        		unlink($upload_folder . $filename);	
	        	}
		        
		        // reassemble the partial pieces to a whole file
		        for ($i = 0; $i < $total_chunk_number; $i ++) {
		            //echo filesize($upload_folder . $uid . '.part' . $i);
		            $content = file_get_contents($upload_folder . $uid . '.part' . $i);
		            file_put_contents($upload_folder . $filename, $content, FILE_APPEND);


		            
		            unlink($upload_folder . $uid . '.part' . $i);
	            	
		        }
		         
		        
		        //$returnArr = array( 'SUCCESS' => 1, 'MSG' => 'Unable to upload file. Please try again. 2', "PATH_TO_IMAGE" => $upload_folder . $filename, 'IMAGE_NAME' => '', 'IMAGE_EXTENSION' => '' );
		        
		        //======= rename file ==============
		        $EXT = pathinfo($filename, PATHINFO_EXTENSION);
		        $NEW_FILE_NAME = $this->clean(str_ireplace("." . $EXT, "", $filename)) . "." . $EXT;
		        $NEW_UPLOAD_PATH = public_path('temp/' . $NEW_FILE_NAME);
		        
		        rename($upload_folder . $filename, $NEW_UPLOAD_PATH);
		        
		        $returnArr = array( 'SUCCESS' => 1, 'MSG' => 'File upload completed', "PATH_TO_IMAGE" => asset('temp/' . $NEW_FILE_NAME), 'IMAGE_NAME' => $NEW_FILE_NAME, 'IMAGE_EXTENSION' => pathinfo($filename, PATHINFO_EXTENSION));
		        
		        //$returnArr = array( 'SUCCESS' => 1, 'MSG' => 'File upload completed', "PATH_TO_IMAGE" => $upload_folder . $filename, 'IMAGE_NAME' => $filename, 'IMAGE_EXTENSION' => pathinfo($filename, PATHINFO_EXTENSION));
		        
		        
		        $json = json_encode( $returnArr );
		        echo $json;
		        
		         
		        
		        //unlink($upload_folder . $filename);
		    } 
		}
	}	    
}

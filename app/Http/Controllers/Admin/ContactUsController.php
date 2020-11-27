<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ContactUs;
use App\Subscribe;
use App\Exports\ContactUsExport;
use App\Exports\SubscribeExport; 
use Maatwebsite\Excel\Facades\Excel;
use Storage;
class ContactUsController extends Controller
{
  # Contact us functions 
   	public function listContactUsDB($page){ 
   		$skip = ($page-1)*request('perpage');
        $total_records = ContactUs::orderBy('created_at', 'DESC');
        $list = ContactUs::orderBy('created_at', 'DESC');

        $searchedFields = [
            'search_name' => request('search_name') ? request('search_name') : '',
            'search_email'=> request('search_email') != '' ? request('search_email') : ''
        ];
        if(!empty(request('search_name'))){
            $list = $list->where('name', 'like', '%'.request('search_name').'%');
            $total_records =$total_records->where('name', 'like', '%'.request('search_name').'%');       
        }
        if(!empty(request('search_email'))){
            $list = $list->where('email', 'like', '%'.request('search_email').'%');
            $total_records =$total_records->where('email', 'like', '%'.request('search_email').'%');       
        }
         

        $list = $list->skip($skip)->take(request('perpage'))->get()->toArray();
        $total_records = $total_records->count();

        $returnArr = array("SUCCESS" => 1, "data"=> $list, 'searchedFields'=>$searchedFields, 'total_records'=> $total_records);
        echo json_encode($returnArr);
   	}

   	public function deleteContactUs(){
   		$result = ContactUs::where('id', request('id'))->first();
   		if(!empty($result)){
   			$result->delete();
   		}
   		$returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
   		
   	}

   	public function deleteAllContactus()
   	{
   		foreach(request('DIDS') as $k=>$val){
   			$result = ContactUs::where('id', $val['id'])->first();
	   		if(!empty($result)){
	   			$result->delete();
	   		}
   		}

   		$returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
   	}

   	public function exportContactus() 
    {   
        $list = ContactUs::select('name','email','phone','investor_type','message','created_at')->orderBy('created_at', 'DESC');

        if(!empty(request('search_name'))){
            $list = $list->where('name', 'like', '%'.request('search_name').'%');
        }
        if(!empty(request('search_email'))){
            $list = $list->where('email', 'like', '%'.request('search_email').'%');
        }

        $list = $list->get();

       	$response = Excel::store(new ContactUsExport($list), 'contact-us-db.xlsx', 'export');
       	$returnArr = array("SUCCESS" => $response, 'DATA'=> asset('export/contact-us-db.xlsx'));
        echo json_encode($returnArr);
    }


  # Subscribe functions 
    public function listSubscribeDB($page){ 
      $skip = ($page-1)*request('perpage');
        $total_records = Subscribe::orderBy('created_at', 'DESC');
        $list = Subscribe::orderBy('created_at', 'DESC');

        $searchedFields = [
            'search_name' => request('search_name') ? request('search_name') : '',
            'search_email'=> request('search_email') != '' ? request('search_email') : ''
        ];
        if(!empty(request('search_name'))){
            $list = $list->where('name', 'like', '%'.request('search_name').'%');
            $total_records =$total_records->where('name', 'like', '%'.request('search_name').'%');       
        }
        if(!empty(request('search_email'))){
            $list = $list->where('email', 'like', '%'.request('search_email').'%');
            $total_records =$total_records->where('email', 'like', '%'.request('search_email').'%');       
        }

        $list = $list->skip($skip)->take(request('perpage'))->get()->toArray();
        $total_records = $total_records->count();

        $returnArr = array("SUCCESS" => 1, "data"=> $list, 'searchedFields'=>$searchedFields, 'total_records'=> $total_records);
        echo json_encode($returnArr);
    }

    public function deleteSubscribe(){
      $result = Subscribe::where('id', request('id'))->first();
      if(!empty($result)){
        $result->delete();
      }
      $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
      
    }

    public function deleteAllSubscribe()
    {
      foreach(request('DIDS') as $k=>$val){
        $result = Subscribe::where('id', $val['id'])->first();
        if(!empty($result)){
          $result->delete();
        }
      }

      $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }

    public function exportSubscribe() 
    { 
        $list = Subscribe::select('name','email','created_at')->orderBy('created_at', 'DESC');

        if(!empty(request('search_name'))){
            $list = $list->where('name', 'like', '%'.request('search_name').'%');
        }
        if(!empty(request('search_email'))){
            $list = $list->where('email', 'like', '%'.request('search_email').'%');
        }

        $list = $list->get();

        $response = Excel::store(new SubscribeExport($list), 'subscribe-db.xlsx', 'export');
        $returnArr = array("SUCCESS" => $response, 'DATA'=> asset('export/subscribe-db.xlsx'));
        echo json_encode($returnArr);
    } 
}

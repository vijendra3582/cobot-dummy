<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HomeContent; 
use App\GeneralSetting;
use App\AboutUs;
use App\Team;
use App\ContactUs;
use App\Subscribe;
use App\BlogSubscribe;
use App\Fund;
use App\FundContentDisclosure;
use App\News;
use App\NewsDisclosure;
use App\Resource;
use App\ResourceDisclosure;
use App\FundOutcomeDisclosure;
use App\ResourceCategory;
use App\PrivacyPolicy;
use Mail;
use App\BlogContent;
use App\Blog;
use App\Http\Controllers\MobileDetect;

class HomeController extends Controller
{
    public function index(){
        $homeContent = HomeContent::first();
        $aboutUs = AboutUs::first();
        $generalSetting = GeneralSetting::first();
        $funds = Fund::where('status',config('constants.const_active'))->where('is_outcome_product',config('constants.const_inactive'))->orderBy('position','ASC')->get();
        $countStructuredEtf = Fund::where('status',config('constants.const_active'))->where('is_outcome_product',config('constants.const_active'))->orderBy('position','ASC')->count();
        $outcomeContent = FundOutcomeDisclosure::first(); 
        $fundContent = FundContentDisclosure::first();
        $news = News::where('status',config('constants.const_active'))->where('set_at_homepage', config('constants.const_active'))->orderBy('position','ASC')->get();
        $newsDisclosure = NewsDisclosure::first(); 
        return view('index', compact('homeContent','aboutUs','generalSetting','funds','fundContent', 'news','newsDisclosure','countStructuredEtf','outcomeContent'));
    }

    public function contactUs(Request $request){
         
    	$data_mail = [
            'name' =>request('name'),
            'email'=>request('email'),
            'phone'=>request('phone'), 
            'message'=>request('message'),
            'investor_type' => !empty(request('investor_type')) ? implode(', ', request('investor_type')) : ''
        ];

        // if(!empty(trim(request('enquiry')))){
        //     $data_mail['enquiry_for'] = trim(request('enquiry')); 
        // }

        if(!empty(request('subscribe')) && request('subscribe') == 'on'){
            $data = [
                'name'=>request('name'),
                'email'=>request('email')
            ];
            $subs = Subscribe::create($data);
        } 

        $res = ContactUs::create($data_mail);
        if(!empty($res)){
            $data_mail = $res->toArray();

            $_json = view('Mail.contact-us-mail', compact('data_mail'))->render(); 

            $mail_data = [
                'subject' => config('app.name')." | New query from website", 
                'from'=> $data_mail['email'],
                'from_name'=> $data_mail['name'],
                'massege' => $_json, 
            ];  
            $data = $mail_data; 
            $result = \App\User::sendMail($data);

            return response()->json(['status'=>'success','data'=>$result]); 
        }else{
            return response()->json(['status'=>'fail']); 
        } 
    }

    public function subsribe(){ 
         
        $data = [
            'name'=>request('name'),
            'email'=>request('email')
        ];
        $existing = Subscribe::where('email', request('email'))->first();
        if(empty($existing)){
            $subs = Subscribe::create($data);
        } 

        return response()->json(['status'=>'success']); 
    }



    public function aboutUs(){
        $aboutUs = AboutUs::first();
        $team = Team::where('status', config('constants.const_active'))->orderBy('position','ASC')->get();
        return view('about-us', compact('aboutUs', 'team'));
    }

    public function news(Request $request){
        
        $disclosure = NewsDisclosure::first();
        $news = News::where('status', config('constants.const_active'))->orderBy('is_disclosure','DESC')->orderBy('date','desc')->paginate(9);

        $html='';
        foreach ($news as $val) {
            $html = \View::make('load-more-news', compact('news'))->render();
        }
        if ($request->ajax() == true) {
            return $html;
        }else{
            return view('news', compact('disclosure', 'news'));
        }
    }

    public function resources(){
        
        $sortBy = '';
        if(!empty(request('sort'))){
            $sortBy = request('sort');
        }

        $res_count = Resource::where('status', config('constants.const_active'))->count();
                           
        /* if($res_count == 0){
            return redirect()->route('home');
        } */

        $disclosure = ResourceDisclosure::first();
        $resources = Resource::where('status', config('constants.const_active'))->orderBy('position','ASC');
 
        if(!empty($sortBy)){
            $resources = $resources->where('file_type', $sortBy);
        }   

        $resources = $resources->paginate(9);

        $html = \View::make('load-more-resource', compact('resources'))->render();
    
        if (request()->ajax() == true) {
            return $html;
        }

        $category = ResourceCategory::get();
        return view('resources', compact('disclosure', 'resources','category','sortBy','res_count'));
       
    }

    public function privacyPolicy(){
        $policy = PrivacyPolicy::first();
        return view('privacy-policy', compact('policy'));
    }

    public function fileframe($type){
        
        $file = decrypt(request('key'));
        
        $file = !empty(request('key')) ? decrypt(request('key')) : '';

        if(empty($file)){
            redirect()->route('home');
        }

        $file_info = pathinfo($file, PATHINFO_EXTENSION);
         
        if(strtolower($file_info) == 'pdf'){ 
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
            $myFile = $file;
            // $headers = ['Content-Type: application/pdf'];
            $newName = 'file-'.date('m-d-Y').'.'.$file_info;
            return response()->download($myFile, $newName);
        }
    }

}

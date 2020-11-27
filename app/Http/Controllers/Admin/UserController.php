<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use Validator;
use DB;
use Illuminate\Validation\Rule;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Redirect;
class UserController extends Controller
{
    public function index(){
    	if(Auth::check()){
             return redirect('/cms');
		}else{
			return redirect('/cms/login');
		}
    }

    public function login(){
    	if(Auth::check()){
    		return redirect('/cms');
    	}else{
    		return view('Admin.User.Login');
    	}
    }

    public function makeLogin(Request $request){
    	$validator = Validator::make($request->all(), [
            
            'username'=> 'required',
            'password'=> 'required|min:6'
        ]); 

        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
        else{
            $remember = $request->get('remember');

            if(Auth::attempt(['email' => $request->username, 'password' => $request->password], $remember))
            {
                if(Auth::user()->status == User::INACTIVE){
                    auth()->logout(); 
                    return redirect('login')->with('message', 'Your account status is inactive, please contact to your admin.');
                    
                }
                return redirect('/cms')->with('success', 'Login Successfully.');
            } 
            return back()->withInput()->with('message', 'Failed | Incorrect Login Credentials');
        }   
    }

    public function logout(){
        auth()->logout(); 
        return redirect('/cms/login')->with('message', 'Success | Logout Successfully.');
    }



    /** 
	* Create Admin module
    **/
	public function addAdmin(){
    	$is_admin = User::where('user_type', User::ADMIN_USER)->first();
    	if(!empty($is_admin)){
    		return redirect()->to('/cms')->with('success', 'Admin already exists.');
    	}else{ 
    		return view('Admin.User.AddAdmin');
    	}
    }

    public function createAdmin(Request $request){
    	 
    	$validator = Validator::make($request->all(), [
            
            'username'=> 'required',
            'email'=> 'required|unique:users,email',
            'password'=> 'required|min:6',
            'conf_password'=> 'required_with:password|same:password'
        ],[
        	'conf_password.required_with' => 'The confirm password field is required when password is present.',
        	'conf_password.same' => 'Confirm Password should be same as Password.',
            'email.required'=> 'User Name is required.',
            'email.unique' => 'User Name should be unique.'
        ]); 

        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
        else{

        	$data = [ 
        		'name'=> $request->input('username'),
        		'email'=> $request->input('email'),
        		'password'=> $request->input('password'),
        		'user_type'=>User::ADMIN_USER,
        		'status' => User::ACTIVE
        	];
        	$response = User::create($data);
        	if(!empty($response)){
        		return redirect('/cms')->with('success', 'Admin created successfully.');
        	}else{
        		return redirect()->back()->with('danger', 'Some error occured in admin creation.');
        	}
        }
    }

    /****/

    public function changePassword(){

        if(request('admin_password') !== request('admin_password_c')){
            $returnArr = array("SUCCESS" => 2);
            echo json_encode($returnArr);
            exit;
        }

        if(Auth::user()){
           $response = Auth::user()->update(['password'=> request('admin_password')]);
        }

        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
}

<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\GeneralSetting;
use Mail;
class User extends Authenticatable
{
    use Notifiable;
    const ADMIN_USER = 0;

    const ACTIVE = 1;
    const INACTIVE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user_type','last_login_at','login_by_ip','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public static function sendMail($data){
        $toMail = [];
        $toMails = GeneralSetting::first();
        $toMail_arr = explode(',', $toMails->contact_us_mail_to);

        foreach ($toMail_arr as $key => $value) {
            array_push($toMail, trim($value));
        }
               
        $data['to'] = $toMail;
        $data['from']='noreply@true-shares.com';
        
        
        try {
            Mail::send('Mail.mail-layout', $data, function($message) use($data){
                $message->to($data['to'])->subject
                ($data['subject']);              
                $message->from($data['from'], $data['from_name']);
            });
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }  
    }
 
}

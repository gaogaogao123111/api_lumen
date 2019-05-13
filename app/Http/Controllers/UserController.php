<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Location;
class UserController extends BaseController
{
   public function regadd(){
   		echo __METHOD__;echo "<hr>";
        $data = file_get_contents("php://input");
        $data = base64_decode($data);
        $key = openssl_pkey_get_public('file://'.storage_path('app/keys/public.pem'));
        openssl_public_decrypt($data,$dec_data,$key);
        var_dump($dec_data);
   }


   //登录
    public function login(){
        return view('User/login');
    }
    public function loginadd(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $a = DB::table("user_api")->where(['email'=>$email])->first();
        if($a){
            if($a){
                if(password_verify($password,$a->pass)){
                    $token = substr(md5($a->id.Str::random(5)),2,5);
                    $res = [
                        'errno'=>'0',
                        'msg'=>'登录成功',
                        'data'=>[
                            'token'=>$token
                        ]
                    ];
                    $key = "feiduichen:ceshi:token:id".$a->id;
                    Redis::set($key,$token);
                    Redis::expire($key,604800);
                    header('Refresh: 10; url=http://api.laravel.com/');
                }else{
                    $res = [
                        'errno'=>'50010',
                        'msg'=>'密码不正确'
                    ];
                }
            }else{
                $res = [
                    'errno'=>'50002',
                    'msg'=>'用户不存在'
                ];
            }
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }

    }
}

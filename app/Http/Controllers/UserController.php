<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Location;
use App\Model\User\User;
class UserController extends BaseController
{
   //注册
   public function regadd(){
   		echo __METHOD__;echo "<hr>";
        $data = file_get_contents("php://input");
        $data = base64_decode($data);
        $key = openssl_pkey_get_public('file://'.storage_path('app/keys/public.pem'));
        openssl_public_decrypt($data,$dec_data,$key);
        var_dump($dec_data);
   }
   //登录
    public function loginadd(Request $request){
        $data = file_get_contents("php://input");
        $data = base64_decode($data);
        $key = openssl_pkey_get_public('file://'.storage_path('app/keys/public.pem'));
        openssl_public_decrypt($data,$dec_data,$key);
        var_dump($dec_data);

    }



    //app开发注册
    public function appregadd(Request $request){
        $data=$request->input();
        $method = 'aes-256-cbc';
        $key = 'aaaa';
        $option = OPENSSL_RAW_DATA;
        $iv = '1809180918091809';
        // 加密
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        $encrypted = openssl_encrypt($data, $method, $key, $option, $iv);
        $b64= base64_encode($encrypted);
        $url = 'http://passport.1809.com/User/appregadd';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$b64);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        //发起请求
        curl_exec($ch);
        //检查错误码
        curl_errno($ch);
        //释放资源
        curl_close($ch);
    }
    //app开发登录
    public function apploginadd(Request $request){
        $data = $request->input();
        $method = 'aes-256-cbc';
        $key = 'aaaa';
        $option = OPENSSL_RAW_DATA;
        $iv = '1809180918091809';
        // 加密
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        $encrypted = openssl_encrypt($data, $method, $key, $option, $iv);
        $b64= base64_encode($encrypted);
        $url = 'http://passport.1809.com/User/apploginadd';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$b64);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        //发起请求
        curl_exec($ch);
        //检查错误码
        curl_errno($ch);
        //释放资源
        curl_close($ch);
    }

    public function user(){
        $id =$_GET['id'];
        $method = 'aes-256-cbc';
        $key = 'aaaa';
        $option = OPENSSL_RAW_DATA;
        $iv = '1809180918091809';
        // 加密
        $data = json_encode($id,JSON_UNESCAPED_UNICODE);
        $encrypted = openssl_encrypt($data, $method, $key, $option, $iv);
        $b64= base64_encode($encrypted);
        $url = 'http://passport.1809.com/User/user';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$b64);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        //发起请求
        curl_exec($ch);
        //检查错误码
        curl_errno($ch);
        //释放资源
        curl_close($ch);
    }
}

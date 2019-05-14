<?php
namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;
class TextController extends BaseController
{
    public function decode(){
        //解密算法
        $method = 'aes-256-cbc';
        $key = 'asdsda';
        $option = OPENSSL_RAW_DATA;
        $iv = 'abcdefghijklmnop';
        //解密
        $data = $_GET['str'];
        $d64 = base64_decode($data);
        $encrypted = openssl_decrypt($d64, $method, $key, $option, $iv);
        echo "解密原文：".$d64;echo "<hr>";
        echo "解密base64：".$encrypted;
    }
    //对称
    public function efg(){
        echo __METHOD__;echo "<hr>";
        $data = file_get_contents("php://input");
        $method = 'aes-256-cbc';
        $key = 'aaaa';
        $option = OPENSSL_RAW_DATA;
        $iv = '1809180918091809';
        $data = base64_decode($data);
        $decrypted = openssl_decrypt($data, $method, $key, $option, $iv);
        var_dump($decrypted);
    }
    //非对称
    public function feiefg(){
        echo __METHOD__;echo "<hr>";
        $data = file_get_contents("php://input");
        $data = base64_decode($data);
        $key = openssl_pkey_get_public('file://'.storage_path('app/keys/public.pem'));
        openssl_public_decrypt($data,$dec_data,$key);
        var_dump($dec_data);
    }

    //验证签名
    public function yansign(){
        $data = file_get_contents("php://input");
        $sign = $_GET['sign_'];
        $key = openssl_get_publickey('file://'.storage_path('app/keys/public.pem'));
        $sign = base64_decode($sign);
        $res = openssl_verify($data,$sign,$key);
//        var_dump($res);die;
        if($res==1){
            die('验证成功');
        }else{
            die('验证错误');
        }

    }


    public function a(){
        header('Access-Control-Allow-Origin:http://api.laravel.com');
        print_r($_GET['callback']);
    
        return view('/User/a');
    }

}

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
        $name=$request->input('name');
//        echo $name;die;
        $email = $request->input('email');
        $password = $request->input('password');
        $a = DB::table('user_api')->where(['email'=>$email])->first();
        if($a){
            $res = [
                'errno'=>'50002',
                'msg'=>'email已存在'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }
        $password = password_hash($password,PASSWORD_BCRYPT);
        $info = [
            'name'=>$name,
            'email'=>$email,
            'pass'=>$password,
            'create_time'=>time()
        ];
        $res = DB::table('user_api')->insert($info);
        if($res){
            $res = [
                'errno'=>'0',
                'msg'=>'OK'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }else{
            $res = [
                'errno'=>'40002',
                'msg'=>'注册失败'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }
    }
    //app开发登录
    public function apploginadd(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $a = DB::table('user_api')->where(['email'=>$email])->first();
//        var_dump($res);
        if($a){
            if(password_verify($password,$a->pass)){
                $token = substr(md5($a->id.Str::random(5)),2,5);
                $res = [
                    'errno'=>'0',
                    'msg'=>'登录成功',
                    'data'=>[
                        'token'=>$token,
                        'id'=>$a->id
                    ]
                ];
                $key = "login:ceshiapp:token:id".$a->id;
                Redis::set($key,$token);
                Redis::expire($key,604800);
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

    public function user(){
        $id =$_GET['id'];
        $a = User::where(['id'=>$id])->first();
        $a=json_encode($a);
        if($a){
            $res = [
                'errno'=>'0',
                'msg'=>'OK',
                'data'=>$a
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }else{
            $res = [
                'errno'=>'50006',
                'msg'=>'数据异常',
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }

    }
}

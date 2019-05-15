<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Redis;
class TokenMiddleware
{

    public function handle($request, Closure $next)
    {
        $id =$_GET['id'];
        $token=$_GET['token'];
        if(empty($id)||empty($token)){
            $res = [
                'errno'=>'40010',
                'msg'=>'参数不完整'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }
        $key = "login:ceshiapp:token:id".$id;
        $r_token = Redis::get($key);
        if($token==$r_token){
            //记录日志
            $content = json_encode($_GET);
            $str = date('Y-m-d H:i:s') . $content . "\n";
            file_put_contents("logs/token.log", $str, FILE_APPEND);
        }else{
            $res = [
                'errno'=>'50005',
                'msg'=>'无效的token'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }
        return $next($request);
    }
}

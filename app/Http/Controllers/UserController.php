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
        $url = 'http://gxd.chenyys.com/User/appregadd';
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
        $url = 'http://gxd.chenyys.com/User/apploginadd';
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
    //app开发个人中心
    public function user(){
        $data =$_GET;
        $method = 'aes-256-cbc';
        $key = 'aaaa';
        $option = OPENSSL_RAW_DATA;
        $iv = '1809180918091809';
        // 加密
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        $encrypted = openssl_encrypt($data, $method, $key, $option, $iv);
        $b64= base64_encode($encrypted);
        $url = 'http://gxd.chenyys.com/User/user';
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



    //商品列表
    public function goodslist(Request $request){
        $res = DB::table('user_goods')->where(['is_up'=>1])->get();
        $data = json_encode($res,JSON_UNESCAPED_UNICODE);
        return $data;
    }
    //商品详情
    public function goodsdetail(Request $request){
        $goods_id = $_GET['goods_id'];
        $goods_id = DB::table('user_goods')->where(['goods_id'=>$goods_id])->first();
        $data = json_encode($goods_id,JSON_UNESCAPED_UNICODE);
        echo $data;
    }
    //添加购物车
    public function goodscart(Request $request){
        $goods_id = $request->input('goods_id');
        $buy_number = $request->input('buy_number');
        $id= $request->input('id');
        $goods = DB::table('user_goods')->where(['goods_id'=>$goods_id])->first();
        if(empty($id)){
            $res = [
                'errno'=>'50010',
                'msg'=>'请先登录'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }else if(empty($goods)){
            $res = [
                'errno'=>'40011',
                'msg'=>'商品已下架'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }else if(empty($buy_number)){
            $res = [
                'errno'=>'40012',
                'msg'=>'请选择商品数量'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }
        $cartWhere=[
            'user_id'=>$id,
            'goods_id'=>$goods_id,
            'cart_status'=>1
        ];
        $info = [
            'user_id'=>$id,
            'goods_id'=>$goods_id,
            'buy_number'=>$buy_number,
            'create_time'=>time()
        ];
        $cart = DB::table('user_cart')->insert($info);
        if($cart){
            $res = [
                'errno'=>'0',
                'msg'=>'加入购物车成功'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }else {
            $res = [
                'errno' => '50001',
                'msg' => '加入购物车失败'
            ];
            die(json_encode($res, JSON_UNESCAPED_UNICODE));
        }
    }
    //购物车列表
    public function cartlist(Request $request){
        $user_id = $request->input('id');
        $where['user_id']=$user_id;
        $where['cart_status']=1;
        //var_dump($where);die;
        $res=DB::table('user_cart')
            ->join('user_goods', 'user_goods.goods_id', '=', 'user_cart.goods_id')
            ->where($where)
            ->get();
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }
    //购物车加入订单
    public function cartadd(Request $request){
        $id =$request->input('id');
        $text =$request->input('text');
        if(empty($id)){
            $res = [
                'errno'=>'50010',
                'msg'=>'请先登录'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }
        $where['user_id']=$id;
        $data=DB::table('user_cart')
            ->join('user_goods', 'user_goods.goods_id', '=', 'user_cart.goods_id')
            ->where($where)
            ->get();
        $order_no = time();
        $amount = 0;
        foreach($data as $k=>$v){
            $amount+=$v->self_price;
        }
        $info = [
            'order_no'=>$order_no,
            'user_id'=>$id,
            'order_amount'=>$amount,
            'order_text'=>$text,
            'create_time'=>time()
        ];
        $order = DB::table('user_order')->insert($info);
        if($order){
            $res = [
                'errno'=>'0',
                'msg'=>'加入订单成功'
            ];
            die(json_encode($res,JSON_UNESCAPED_UNICODE));
        }else {
            $res = [
                'errno' => '50001',
                'msg' => '加入订单失败'
            ];
            die(json_encode($res, JSON_UNESCAPED_UNICODE));
        }
    }
    //订单列表
    public function orderlist(){
        $res = DB::table('user_order')->where(['order_status'=>1])->get();
        $data = json_encode($res,JSON_UNESCAPED_UNICODE);
        return $data;
    }
}

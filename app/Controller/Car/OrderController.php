<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 10:37
 */

namespace App\Controller\Car;


use App\Center;
use App\Model\CarProduct;
use App\Model\CarProductSpec;
use App\Model\CarRent;
use System\Lib\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
    }

    

    public function confirm(Request $request,CarProduct $product,CarProductSpec $spec)
    {
        $id=$request->get('id');
        $spec_id=$request->get('spec_id');
        $product=$product->findOrFail($id);
        $spec=$spec->findOrFail($spec_id);
        if($spec->product_id!=$id){
            redirect()->back()->with('error','参数异常');
        }

        $center=new Center();
        $account=$center->getUserFunc($this->user->openid);
        if($_POST){
            if($account->funds_available<5000){
                redirect()->back()->with('error',"帐户余额不足5000元，请充值后再试！");
            }
            $carRent=new CarRent();
            $carRent->user_id=$this->user_id;
            $carRent->contacts=$request->post('contacts');
            $carRent->tel=$request->post('tel');
            //$carRent->area=$request->post('province').'-'.$request->post('city').'-'.$request->post('county');
            $carRent->address=$request->post('address');

            $carRent->car_id=$id;
            $carRent->car_name=$product->name;
            $carRent->car_picture=$product->picture;
            $carRent->first_payment_scale=0;
            $carRent->first_payment_money=$spec->first_payment;
            $carRent->last_payment_scale=0;
            $carRent->last_payment_money=$spec->last_payment;
            $carRent->time_limit=$spec->time_limit;
            $carRent->month_payment_money=$spec->month_payment;
            $carRent->month_payment_day=1;
            $carRent->status=0;
            $inser_id=$carRent->save(true);
            redirect("rent/editUpload/?id={$inser_id}")->with('msg','己保存，请上传资料！');
        }else{
            $data['account']=$account;
            $data['product']=$product;
            $data['spec']=$spec;
            $this->title='我要订车';
            $this->view('order_confirm',$data);
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 11:36
 */

namespace App\Controller\Car;


use App\Model\CarRent;
use App\Model\CarRentImage;
use System\Lib\DB;
use System\Lib\Request;
class RentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
    }

    public function index(Request $request)
    {
        $user_id=$this->user_id;
        $this->title='我的订单';
        $data['result']=(new CarRent())->where("user_id=?")->bindValues($user_id)->orderBy('id desc')->pager($request->get('page'),3);
        $this->view('rent_index',$data);
    }
    
    public function editContacts(Request $request,CarRent $rent)
    {
        $id=$request->get('id');
        $rent=$rent->findOrFail($id);
        if($rent->user_id!=$this->user_id){
            redirect()->back()->with('error','异常');
        }
        if($rent->status!=0){
            redirect()->back()->with('error','禁止修改');
        }
        if($_POST){
            $rent->contacts=$request->post('contacts');
            $rent->tel=$request->post('tel');
            $rent->address=$request->post('address');
            $rent->save();
            redirect("rent/editContacts/?id={$id}")->with('msg','己保存');
        }else{
            $this->title='填写申请人信息';
            $data['rent']=$rent;
            $this->view('rent_form',$data);
        }
    }
    
    public function editUpload(Request $request,CarRent $rent,CarRentImage $rentImg)
    {
        $id=$request->get('id');
        $rent=$rent->findOrFail($id);
        if($rent->user_id!=$this->user_id){
            redirect()->back()->with('error','异常');
        }
        if($rent->status!=0){
            redirect()->back()->with('error','禁止修改');
        }
        if($_POST){
            $array_ids=$request->post('img_id');
            if(empty($array_ids)){
                $array_ids=array(0);//不能为空，默认添加一个
            }
            $imgs=$request->post('card_img');
            if(is_array($imgs)){
                foreach ($imgs as $img){
                    $rentImg->user_id=$this->user_id;
                    $rentImg->typeid='card';
                    $rentImg->rent_id=$id;
                    $rentImg->image_url=$img;
                    $rentImg->status=1;
                    $inser_id=$rentImg->save(true);
                    array_push($array_ids,$inser_id);
                }
            }
            $imgs=$request->post('drive_img');
            if(is_array($imgs)){
                foreach ($imgs as $img){
                    $rentImg->user_id=$this->user_id;
                    $rentImg->typeid='drive';
                    $rentImg->rent_id=$id;
                    $rentImg->image_url=$img;
                    $rentImg->status=1;
                    $inser_id=$rentImg->save(true);
                    array_push($array_ids,$inser_id);
                }
            }
            $imgs=$request->post('credit_img');
            if(is_array($imgs)){
                foreach ($imgs as $img){
                    $rentImg->user_id=$this->user_id;
                    $rentImg->typeid='credit';
                    $rentImg->rent_id=$id;
                    $rentImg->image_url=$img;
                    $rentImg->status=1;
                    $inser_id=$rentImg->save(true);
                    array_push($array_ids,$inser_id);
                }
            }
            $imgs=$request->post('other_img');
            if(is_array($imgs)){
                foreach ($imgs as $img){
                    $rentImg->user_id=$this->user_id;
                    $rentImg->typeid='other';
                    $rentImg->rent_id=$id;
                    $rentImg->image_url=$img;
                    $rentImg->status=1;
                    $inser_id=$rentImg->save(true);
                    array_push($array_ids,$inser_id);
                }
            }
            DB::table('car_rent_image')->where("rent_id={$id} and id not in(".implode(',',$array_ids).")")->delete();
            redirect("rent/editUpload/?id={$id}")->with('msg','己保存');
        }else{
            $this->title='上传资料';
            $data['rent']=$rent;
            $data['rentImages']=$rent->CarRentImage();
            $this->view('rent_form',$data);
        }
    }

    public function repayment(CarRent $carRent,Request $request)
    {
        $carRent=$carRent->findOrFail($request->get('id'));
        if($carRent->user_id!=$this->user_id){
            redirect()->back()->with('msg','异常！');
        }
        $repayments=$carRent->Repayments();
        $data['carRent']=$carRent;
        $data['repayments']=$repayments;
        $this->view('carRent',$data);
    }

    public function pay(CarRentRepayment $repayment,Request $request,CarRent $carRent,System $system)
    {
        $repayment=$repayment->findOrFail($request->get('repay_id'));
        if($repayment->user_id!=$this->user_id){
            redirect()->back()->with('error','权限异常！');
        }
        if($repayment->status!=1){
            redirect()->back()->with('error','状态异常！');
        }
        $carRent=$carRent->findOrFail($repayment->car_rent_id);
        $account=$this->user->Account();
        $convert_rate=(float)$system->getCode('convert_rate');
        if(empty($convert_rate)){
            $convert_rate=2.52;
        }
        if($_POST){
            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            $integral=(float)$request->post('integral');
            if($integral > $account->integral_available){
                redirect()->back()->with('error','可用积分不足！');
            }
            $_money=math($integral,$convert_rate,'/',3);
            $_money=round_money($_money,1,2);
            $money=math($repayment->money,$_money,'-',2);
            if($money > $account->funds_available){
                redirect()->back()->with('error','可用资金不足！');
            }
            try {
                DB::beginTransaction();

                $log = array(
                    'user_id' => $this->user_id,
                    'type' => 'car_repayment',
                    'funds_available' =>'-'.$money,
                    'integral_available' =>'-'.$integral,
                    'funds_available_now'=>$account->funds_available,
                    'integral_available_now'=>$account->integral_available,
                    'label'=>"car_rent:{$carRent->id}",
                    'remark' => "{$carRent->car_name}：{$repayment->title},编号:{$repayment->id}"
                );
                $account->addLog($log);

                $repayment->status=2;
                $repayment->money_yes=$repayment->money;
                $repayment->repayment_yestime=time();
                $repayment->save();

                DB::commit();
                redirect("carRent/repayment/?id={$carRent->id}")->with('msg','付款完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $data['convert_rate']=$convert_rate;
            $data['account']=$account;
            $data['carRent']=$carRent;
            $data['repayment']=$repayment;
            $this->view('rent_form',$data);
        }
    }
}
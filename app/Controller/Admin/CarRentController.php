<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;


use App\Center;
use App\Model\CarRent;
use App\Model\CarRentRepayment;
use App\Model\System;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class CarRentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(CarRent $carRent,Request $request)
    {
        $arr=array(
            'user_id'		=>(int)$_GET['user_id'],
            'contacts'		=>(int)$_GET['contacts'],
            'money'		=>(int)$_GET['money'],
            'group_id'		=>(int)$_GET['group_id'],
        );
        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['contacts'])) {
            $where .= " and contacts like '%{$arr['contacts']}%'";
        }
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$carRent->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('carRent_list',$data);
    }

    public function add(CarRent $carRent,Request $request)
    {
        if($_POST){
            $carRent->user_id=(int)$request->post('user_id');
            $carRent->contacts=$request->post('contacts');
            $carRent->tel=$request->post('tel');
            $carRent->area=$request->post('province').'-'.$request->post('city').'-'.$request->post('county');
            $carRent->address=$request->post('address');

            $carRent->car_name=$request->post('car_name');
            $carRent->money_linedown=(float)$request->post('money_linedown');
            $carRent->first_payment_scale=$request->post('first_payment_scale');
            $carRent->first_payment_money=(float)$request->post('first_payment_money');
            $carRent->last_payment_scale=$request->post('last_payment_scale');
            $carRent->last_payment_money=(float)$request->post('last_payment_money');
            $carRent->time_limit=(int)$request->post('time_limit');
            $carRent->month_payment_money=(float)$request->post('month_payment_money');
            $carRent->month_payment_day=(int)$request->post('month_payment_day');
            $carRent->status=0;
            $carRent->save();
            redirect('carRent')->with('msg','添加成功！');
        }else{
            $data=array();
            $this->view('carRent_form',$data);
        }
    }

    public function edit(CarRent $carRent,Request $request)
    {
        $carRent=$carRent->find($request->get('id'));
        if($_POST){
/*            if($carRent->status==5){
                redirect()->back()->with('error','状态异常！');
            }*/
            $carRent->user_id=(int)$request->post('user_id');
            $carRent->contacts=$request->post('contacts');
            $carRent->tel=$request->post('tel');
            $carRent->area=$request->post('province').'-'.$request->post('city').'-'.$request->post('area');
            $carRent->address=$request->post('address');
            
            $carRent->car_name=$request->post('car_name');

            $carRent->money_linedown=(float)$request->post('money_linedown');
            $carRent->first_payment_scale=$request->post('first_payment_scale');
            $carRent->first_payment_money=(float)$request->post('first_payment_money');
            $carRent->last_payment_scale=$request->post('last_payment_scale');
            $carRent->last_payment_money=(float)$request->post('last_payment_money');
            $carRent->time_limit=(int)$request->post('time_limit');
            $carRent->month_payment_money=(float)$request->post('month_payment_money');
            $carRent->month_payment_day=(int)$request->post('month_payment_day');
            $carRent->save();
            redirect('carRent')->with('msg','保存成功！');
        }else{
            $data['row']=$carRent;
            $this->view('carRent_form',$data);
        }
    }

    //信审
    public function checked(Request $request,CarRent $carRent)
    {
        $carRent=$carRent->findOrFail($request->get('id'));
        $carRentImgs=$carRent->CarRentImage();
        if($_POST){
            if($carRent->status==5){
                redirect()->back()->with('error','状态异常！');
            }
            $checked=$request->post('checked');
            if(! in_array($checked,array(1,2))){
                redirect()->back()->with('error','数据异常！');
            }
            $carRent->status=$checked;
            $carRent->verify_userid=$this->user_id;
            $carRent->verify_remark=time();
            $carRent->verify_remark=$request->post('verify_remark');
            $carRent->save();
            redirect('carRent')->with('msg','保存成功！');
        }else{
            $this->title='信审';
            $center=new Center();
            $user=(new User())->find($carRent->user_id);
            $account=$center->getUserFunc($user->openid);
            $data['user']=$user;
            $data['account']=$account;
            $data['carRent']=$carRent;
            $data['carRentImgs']=$carRentImgs;
            $this->view('carRent_other',$data);
        }
    }

    //扣除车款
    public function deductMoney(Request $request,CarRent $carRent,System $system)
    {
        $carRent=$carRent->findOrFail($request->get('id'));
        $convert_rate=(float)$system->getCode('convert_rate');
        if(empty($convert_rate)){
            $convert_rate=2.52;
        }
        $center=new Center();
        $user=$carRent->User();
        $account=$center->getUserFunc($user->openid);
        if($_POST){
            if($carRent->status==5){
                redirect()->back()->with('error','状态异常！');
            }
            $integral=(float)$request->post('integral');
            if($integral<0){
                redirect()->back()->with('error','不能为负数！');
            }
            if($integral > $account->integral_available){
                redirect()->back()->with('error','可用积分不足！');
            }
            $_money=math($integral,$convert_rate,'/',3);
            $_money=round_money($_money,1,2);//积分对应的现金

            $funds=(float)$request->post('funds');
            if($funds<0){
                redirect()->back()->with('error','不能为负数！');
            }
            if($funds > $account->funds_available){
                redirect()->back()->with('error','可用资金不足！');
            }
            $money_yes=math($_money,$funds,'+',2);//扣除总价值
            try {
                DB::beginTransaction();
                if($funds!=0 || $integral!=0) {
                    $remark = "[{$carRent->contacts}]扣除车款,{$carRent->car_name}";
                    $params = array(
                        'openid' => $user->openid,
                        'body' => '',
                        'type' => 'car_money',
                        'remark' => $remark,
                        'label' => "car_rent:{$carRent->id}",
                        'data' => array(
                            array(
                                'openid' => $user->openid,
                                'type' => 'booked_money',
                                'remark' => $remark,
                                'funds_available' => '-' . $funds,
                                'integral_available' => '-' . $integral,
                                'funds_available_now' => $account->funds_available,
                                'integral_available_now' => $account->integral_available,
                            )
                        )
                    );
                    $return = $center->receivables($params);
                }else{
                    $return=true;
                }
                if($return===true){
                    $carRent->money_yes=math($carRent->money_yes,$money_yes,'+',2);
                    $carRent->money_yes_at=time();
                    $carRent->save();
                    DB::commit();
                    redirect("carRent")->with('msg','扣款完成');
                }else{
                    throw new \Exception($return);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $this->title='扣除车款';
            $data['convert_rate']=$convert_rate;
            $data['carRent']=$carRent;
            $data['user']=$user;
            $data['account']=$account;
            $this->view('carRent_other',$data);
        }
    }

    public function repaymentPay(CarRentRepayment $repayment,Request $request,CarRent $carRent,System $system)
    {
        $repayment=$repayment->findOrFail($request->get('repay_id'));
        if($repayment->status!=1){
            redirect()->back()->with('error','状态异常！');
        }
        $carRent=$carRent->findOrFail($repayment->car_rent_id);
        $center=new Center();
        $user=$carRent->User();
        $account=$center->getUserFunc($user->openid);
        $convert_rate=(float)$system->getCode('convert_rate');
        if(empty($convert_rate)){
            $convert_rate=2.52;
        }
        if($_POST){
            $verify_remark=$request->post('verify_remark');
            if(empty($verify_remark)){
                redirect()->back()->with('error','备注不能为空！');
            }
/*            $integral=(float)$request->post('integral');
            if($integral<0){
                redirect()->back()->with('error','不能为负数！');
            }
            if($integral > $account->integral_available){
                redirect()->back()->with('error','可用积分不足！');
            }

            $_money=math($integral,$convert_rate,'/',3);
            $_money=round_money($_money,1,2);//积分对应的现金
            $funds=math($repayment->money,$_money,'-',2);
            if($funds > $account->funds_available){
                redirect()->back()->with('error','可用资金不足！');
            }*/
            $integral=(float)$request->post('integral');
            if($integral<0){
                redirect()->back()->with('error','不能为负数！');
            }
            if($integral > $account->integral_available){
                redirect()->back()->with('error','可用积分不足！');
            }
            $_money=math($integral,$convert_rate,'/',3);
            $_money=round_money($_money,1,2);//积分对应的现金

            $funds=(float)$request->post('funds');
            if($funds<0){
                redirect()->back()->with('error','不能为负数！');
            }
            if($funds > $account->funds_available){
                redirect()->back()->with('error','可用资金不足！');
            }
            $money_yes=math($_money,$funds,'+',2);//扣除总价值
            try {
                DB::beginTransaction();
                if($funds!=0 || $integral!=0){
                    $remark="[{$carRent->contacts}]扣除车款,{$carRent->car_name}：{$repayment->title},编号:{$repayment->id}";
                    $params=array(
                        'openid'=>$user->openid,
                        'body'=>'',
                        'type'=>'car_repayment',
                        'remark'=>$remark,
                        'label'=>"car_rent:{$carRent->id}",
                        'data'=>array(
                            array(
                                'openid'=>$user->openid,
                                'type'=>'car_repayment',
                                'remark'=>$remark,
                                'funds_available' =>'-'.$funds,
                                'integral_available' =>'-'.$integral,
                                'funds_available_now'=>$account->funds_available,
                                'integral_available_now'=>$account->integral_available,
                            )
                        )
                    );
                    $return=$center->receivables($params);
                }else{
                    $return=true;
                }
                if($return===true){
                    $repayment->money_yes=$money_yes;
                    $repayment->repaymented_at=time();
                    $repayment->verify_userid=$this->user_id;
                    $repayment->verify_at=time();
                    $repayment->verify_remark=$verify_remark;
                    $repayment->status=2;
                    $repayment->save();
                    DB::commit();
                    redirect("carRent/repayment/?id={$carRent->id}")->with('msg','扣款完成');
                }else{
                    throw new \Exception($return);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $this->title='扣除月租';
            $data['convert_rate']=$convert_rate;
            $data['carRent']=$carRent;
            $data['user']=$user;
            $data['account']=$account;
            $data['repayment']=$repayment;
            $this->view('carRent_other',$data);
        }
    }

    //还款列表
    public function repayment(CarRent $carRent,Request $request,CarRentRepayment $rentRepayment)
    {
        $carRent=$carRent->findOrFail($request->get('id'));
        $repayments=$carRent->Repayments();
        $center=new Center();
        $user=$carRent->User();
        $account=$center->getUserFunc($user->openid);
        if(count($repayments)==0){
            if($carRent->status!=1){
                redirect()->back()->with('error','状态异常！');
            }
            $month_payment_day=$carRent->month_payment_day;
            if($month_payment_day<10){
                $month_payment_day='0'.$month_payment_day;
            }
            for($i=1;$i<=$carRent->time_limit;$i++){
                $repayment_date=date("Y-m-{$month_payment_day}", strtotime("+{$i} month"));
                $money=(float)$carRent->month_payment_money;
                if($money!=0){
                    $rentRepayment->user_id=$carRent->user_id;
                    $rentRepayment->car_rent_id=$carRent->id;
                    $rentRepayment->title="第{$i}期";
                    $rentRepayment->money=$money;
                    $rentRepayment->money_yes=0;
                    $rentRepayment->repayment_time=strtotime($repayment_date);
                    $rentRepayment->repaymented_at=0;
                    $rentRepayment->last_days=0;
                    $rentRepayment->last_interest=0;
                    $rentRepayment->status=1;
                    $rentRepayment->save();
                }
            }
            $lastMoney=(float)$carRent->last_payment_money;
            if($lastMoney!=0){
                $rentRepayment->user_id=$carRent->user_id;
                $rentRepayment->car_rent_id=$carRent->id;
                $rentRepayment->title="尾付款";
                $rentRepayment->money=$lastMoney;
                $rentRepayment->money_yes=0;
                $rentRepayment->repayment_time=strtotime($repayment_date);
                $rentRepayment->repayment_yestime=0;
                $rentRepayment->last_days=0;
                $rentRepayment->last_interest=0;
                $rentRepayment->status=1;
                $rentRepayment->save();
            }
            $carRent->status=5;
            $carRent->save();
            $repayments=$carRent->Repayments();
        }
        $this->title='分期列表';
        $data['user']=$user;
        $data['account']=$account;
        $data['carRent']=$carRent;
        $data['repayments']=$repayments;
        $this->view('carRent_list',$data);
    }
}
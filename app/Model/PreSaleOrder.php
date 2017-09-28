<?php

namespace App\Model;


use App\Center;
use App\Helper;

class PreSaleOrder extends Model
{
    protected $table='presale_order';
    public function __construct()
    {
        parent::__construct();
    }
    public function showStatusName()
    {
        $arr=array(1=>'待支付定金',2=>'己付定金，等待商家备货',3=>'商家己到货，待支付尾款',4=>'己付尾款，待发货',5=>'己发货，待确认收货',6=>'己确认收货');
        return $arr[$this->status];
    }

    public function success($operatorOpenId='')
    {
        if($this->status!=5){
            throw new \Exception("异常，请勿重复确认收货！");
        }
        $convert_rate=Helper::getSystemParam('convert_rate');
        $remark="订单号：{$this->order_sn}";
        $params=array(
            'openid'=>$operatorOpenId,
            'body'=>'',
            'type'=>'order_success',
            'remark'=>$remark,
            'label'=>"order_sn:{$this->order_sn}",
            'data'=>array()
        );
        $center=new Center();
        $buyer=(new User())->find($this->buyer_id);
        $seller=(new User())->find($this->seller_id);
        //开始处理卖家资金
        $seller_remark=$remark;
        $seller_money=$this->order_money;

        $seller_award_fee=math($seller_money,0.21,'*',2);
        $seller_remark.="，计划奖励：{$seller_money}积分";
        $seller_order_money=math($seller_money,$seller_award_fee,'-',2);

        if($seller_order_money>0){
            $sell_log=array(
                'openid'=>$seller->openid,
                'type'=>'order_success',
                'remark'=>$seller_remark,
                'funds_available' =>$seller_order_money,
                'funds_available_now'=>$center->getUserFunc($seller->openid)->funds_available
            );
            array_push($params['data'],$sell_log);
        }

        //买家推荐人奖励
        if($buyer->invite_userid!=0){
            $buyer_parent_money=math($this->order_money,'0.001','*',2);
            $buyer_parent_integral=math($buyer_parent_money,$convert_rate,'*',2);
            if($buyer_parent_integral>0){
                $buyer_parent=(new User())->find($buyer->invite_userid);
                if($buyer_parent->is_shop==1){//店铺是店铺才有奖励
                    $buyer_parent_log=array(
                        'openid'=>$buyer_parent->openid,
                        'type'=>'invite_award',
                        'remark'=>"您推荐的用户:{$this->buyer_name} 购买商品，您获取奖励。",
                        'integral_available' =>$buyer_parent_integral,
                        'integral_available_now'=>$center->getUserFunc($buyer_parent->openid)->integral_available_now
                    );
                    array_push($params['data'],$buyer_parent_log);
                }
            }
        }
        //商家推荐人奖励
        if($seller->invite_userid!=0){
            $seller_parent_money=math($this->order_money,'0.02','*',2);
            $seller_parent_integral=math($seller_parent_money,$convert_rate,'*',2);
            if($seller_parent_integral>0){
                $seller_parent=(new User())->find($seller->invite_userid);
                $seller_parentAccount=$center->getUserFunc($seller_parent->openid);
                $seller_parent_log=array(
                    'openid'=>$seller_parent->openid,
                    'type'=>'invite_award',
                    'remark'=>"您推荐的商家:{$seller->username} 购出商品，您获取奖励。",
                    'integral_available' =>$seller_parent_integral,
                    'integral_available_now'=>$seller_parentAccount->integral_available_now
                );
                array_push($params['data'],$seller_parent_log);
            }
        }
        $return=$center->receivables($params);
        if($return===true){
            $this->status=6;
            $this->finished_at=time();
            $this->save();
            //商家积分奖励
            $rebate_sell=new RebateList();
            $rebate_sell->site_id=$this->site_id;
            $rebate_sell->user_id=$seller->id;
            $rebate_sell->money=$seller_money;
            $rebate_sell->typeid=1;
            $rebate_sell->label=$params['label'];
            $rebate_sell->status=0;
            $rebate_sell->remark=$remark;
            $rebate_sell->save();
        }else{
            throw new \Exception($return);
        }
    }

    /**
     * @return OrderShipping
     */
    public function OrderShipping()
    {
        return $this->hasOne('\App\Model\OrderShipping','order_sn','order_sn');
    }

    /**
     * @return Shop
     */
    public function Shop()
    {
        return $this->hasOne('\App\Model\Shop','user_id','seller_id');
    }

    /**
     * @return User
     */
    public function Buyer()
    {
        return $this->hasOne('\App\Model\User','id','buyer_id');
    }

    /**
     * @return CashierLog
     */
    public function preCashierLog()
    {
        return (new CashierLog())->where("order_id='{$this->id}' and typeid='preSaleOrder_pre'")->first();
    }

    /**
     * @return CashierLog
     */
    public function endCashierLog()
    {
        return (new CashierLog())->where("order_id='{$this->id}' and typeid='preSaleOrder_end'")->first();
    }
}
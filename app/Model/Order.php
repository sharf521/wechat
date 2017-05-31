<?php

namespace App\Model;

use App\Center;
use App\Helper;

class Order extends Model
{
    protected $table='order';
    protected $dates=array('created_at','payed_at','shipping_at','canceled_at','finished_at');
    public function __construct()
    {
        parent::__construct();
    }

    //退回库存
    private function backStock()
    {
        $orderGoods=$this->OrderGoods();
        foreach ($orderGoods as $oGoods){
            //添加库存
            $goods=(new Goods())->find($oGoods->goods_id);
            $goods->setStockCount($oGoods->quantity,$oGoods->spec_id);
            /*if($goods->is_exist){
                $num=$oGoods->quantity;
                $goods->stock_count=$goods->stock_count+$num;
                $goods->sale_count=$goods->sale_count-$num;
                $goods->save();
                if($goods->is_have_spec){
                    $spec=(new GoodsSpec())->find($oGoods->spec_id);
                    $spec->stock_count=$spec->stock_count+$num;
                    $spec->save();
                }
            }*/
        }
    }

    public function success($operatorOpenId='')
    {
        if($this->status==3){
            throw new \Exception("异常，请勿重复确认收货！");
        }
        $convert_rate=(new System())->getCode('convert_rate');
        if(empty($convert_rate)){
            $convert_rate=2.52;
        }
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
        if($this->supply_user_id!=0){
            $supplyer=(new User())->find($this->supply_user_id);
        }
        //开始处理卖家资金
        $seller_remark=$remark;
        $seller_money=$this->order_money;
        if($this->supply_user_id==0){
            //自卖商品
            if($this->shipping_fee>0){
                $shipping_award_fee=math($this->shipping_fee,0.21,'*',2);
                $seller_money=math($seller_money,$shipping_award_fee,'-',2);
                $seller_remark.="，运费奖励支出：{$shipping_award_fee}元";
            }
            $seller_award_fee=math($seller_money,0.21,'*',2);
            $seller_remark.="，计划奖励：{$seller_money}积分";
            $seller_order_money=math($seller_money,$seller_award_fee,'-',2);
        }else {
            //采购的商品
            $supplyer_goods_money=math($this->supply_goods_money,1.31,'*',2);
            $seller_order_money = math($this->goods_money, $supplyer_goods_money, '-', 2);
        }
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

        //开始处理供货商资金
        if($this->supply_user_id>0){
            $supplyer_remark=$remark;
            $supplyer_money=math($this->supply_goods_money,$this->shipping_fee,'+',2);
            if($this->fulldown_money>0){
                //满减由供货商出
                $supplyer_money=math($supplyer_money,$this->fulldown_money,'-',2);
                $supplyer_remark.="，满减支出：{$this->fulldown_money}元";
            }
            if($this->shipping_fee>0){
                $shipping_award_fee=math($this->shipping_fee,0.21,'*',2);
                $supplyer_money=math($supplyer_money,$shipping_award_fee,'-',2);
                $supplyer_remark.="，运费奖励支出：{$shipping_award_fee}元";
            }
            $supplyer_award_fee=math($supplyer_money,0.21,'*',2);
            $supplyer_remark.="，计划奖励：{$supplyer_money}积分";
            $supply_log=array(
                'openid'=>$supplyer->openid,
                'type'=>'order_success_supply',
                'remark'=>$supplyer_remark,
                'funds_available' =>math($supplyer_money,$supplyer_award_fee,'-',2),
                'funds_available_now'=>$center->getUserFunc($supplyer->openid)->funds_available
            );
            array_push($params['data'],$supply_log);
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
        //供货商推荐人奖励
        if($this->supply_user_id!=0 && $supplyer->invite_userid!=0){
            $supplyer_parent_money=math($supplyer_money,'0.02','*',2);
            $supplyer_parent_integral=math($supplyer_parent_money,$convert_rate,'*',2);
            if($supplyer_parent_integral>0){
                $supplyer_parent=(new User())->find($supplyer->invite_userid);
                $supplyer_parentAccount=$center->getUserFunc($supplyer_parent->openid);
                $supplyer_parent_log=array(
                    'openid'=>$supplyer_parent->openid,
                    'type'=>'invite_award',
                    'remark'=>"您推荐的供应商:{$supplyer->username} 购出商品，您获取奖励。",
                    'integral_available' =>$supplyer_parent_integral,
                    'integral_available_now'=>$supplyer_parentAccount->integral_available_now
                );
                array_push($params['data'],$supplyer_parent_log);
            }
        }
        $return=$center->receivables($params);
        if($return===true){
            $this->status=5;
            $this->finished_at=time();
            $this->save();
            //运费积分奖励给消费者,发货发提供费用
            if($this->shipping_fee>0){
                $rebate_buyer=new RebateList();
                $rebate_buyer->site_id=$this->site_id;
                $rebate_buyer->user_id=$buyer->id;
                $rebate_buyer->money=$this->shipping_fee;
                $rebate_buyer->typeid=1;
                $rebate_buyer->label=$params['label'];
                $rebate_buyer->status=0;
                $rebate_buyer->remark=$remark;
                $rebate_buyer->save();
            }
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
            if($this->supply_user_id!=0){
                //积分奖励
                $rebate_supply=new RebateList();
                $rebate_supply->site_id=$this->site_id;
                $rebate_supply->user_id=$supplyer->id;
                $rebate_supply->money=$supplyer_money;
                $rebate_supply->typeid=1;
                $rebate_supply->label=$params['label'];
                $rebate_supply->status=0;
                $rebate_supply->remark=$remark;
                $rebate_supply->save();
            }
        }else{
            throw new \Exception($return);
        }
    }

    /**
     * //更新订单产品状态
     * @param $status
     */
    public function updateOrderGoodsStatus($status){
        (new OrderGoods())->where("order_sn='{$this->order_sn}'")->update(array('status'=>$status));
    }

    public function cancel($user)
    {
        if($this->status==1){    //未支付
            if($user->id==$this->buyer_id){
                $this->backStock();//添加库存
                $this->status=2;
                $this->save();
            }else{
                throw new \Exception('异常');
            }
        }elseif($this->status==3){  //己支付
            if($user->id==$this->seller_id){
                $this->backStock();
                //退款
                $center=new Center();
                $buyer=(new User())->find($this->buyer_id);
                $buyerAccount=$center->getUserFunc($buyer->openid);
                $remark="订单号：{$this->order_sn}";
                $params=array(
                    'openid'=>$user->openid,
                    'body'=>'',
                    'type'=>'order_cancel',
                    'remark'=>$remark,
                    'label'=>"order_sn:{$this->order_sn}",
                    'data'=>array(
                        array(
                            'openid'=>$buyer->openid,
                            'type'=>'order_cancel',
                            'remark'=>$remark,
                            'funds_available' =>'-'.$this->payed_funds,
                            'integral_available' =>'-'.$this->payed_integral,
                            'funds_available_now'=>$buyerAccount->funds_available,
                            'integral_available_now'=>$buyerAccount->integral_available,
                        )
                    )
                );
                $return=$center->receivables($params);
                if($return===true){
                    $this->canceled_at=time();
                    $this->status=2;
                    $this->save();
                }else{
                    throw new \Exception($return);
                }
            }else{
                throw new \Exception('异常');
            }
        }
    }

    public function OrderGoods()
    {
        return $this->hasMany('\App\Model\OrderGoods','order_sn','order_sn');
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
     * @return Supply
     */
    public function Supply()
    {
        if($this->supply_user_id!=0){
            return $this->hasOne('\App\Model\Shop','user_id','supply_user_id');
        }
    }

    /**
     * @return User
     */
    public function Buyer()
    {
        return $this->hasOne('\App\Model\User','id','buyer_id');
    }
}
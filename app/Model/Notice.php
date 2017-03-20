<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:56
 */

namespace App\Model;


class Notice extends Model
{
    protected $table='notice';
    public function __construct()
    {
        parent::__construct();
    }

    public function send($user_id,$send_uid=0,$content)
    {
        $this->user_id=(int)$user_id;
        $this->send_uid=(int)$send_uid;
        $this->content=$content;
        $this->status=1;//未读
        $this->is_push=0;
        $this->save();
    }
}
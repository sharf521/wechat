<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/23
 * Time: 11:56
 */

namespace App\Controller\Api;

use System\Lib\DB;

class OrderController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function detail()
    {
        $data=$this->data;
        if ($data['typeid'] == 'mall') {
            $row = DB::table('order')->where('order_sn=?')->bindValues($data['sn'])->row();
            if ($row) {
                return $this->returnSuccess($row);
            }
        }
        return $this->error('error');
    }
}
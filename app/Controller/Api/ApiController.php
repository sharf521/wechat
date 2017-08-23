<?php
namespace App\Controller\Api;

use App\Helper;
use System\Lib\Controller as BaseController;

class ApiController extends BaseController
{
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = json_decode($_POST['data'], true);
        $msg = $this->checkSign($this->data);
        if ($msg !== true) {
            $this->returnError($msg);
            exit;
        }
    }

    public function error()
    {
        echo 'not find page';
    }

    //签名
    protected function checkSign($data)
    {
        if (abs(time() - $data['time']) > 600) {
            return 'time over';
        }
        if ($data['sign'] != $this->getSign($data)) {
            return 'check sign error';
        }
        return true;
    }

    protected function getSign($data)
    {
        if (isset($data['sign'])) {
            unset($data['sign']);
        }
        if (isset($data['data'])) {
            foreach ($data['data'] as $i => $v) {
                if (is_array($v)) {
                    ksort($data['data'][$i]);
                }
            }
        }
        ksort($data);
        $jsonStr = json_encode($data);
        $str = strtoupper(md5($jsonStr . Helper::getSystemParam('md5key')));
        return $str;
    }

    protected function returnSuccess($data = array())
    {
        $data['return_code'] = 'success';
        echo json_encode($data);
    }

    protected function returnError($msg)
    {
        $data = array(
            'return_code' => 'fail',
            'return_msg' => $msg
        );
        echo json_encode($data);
    }
}
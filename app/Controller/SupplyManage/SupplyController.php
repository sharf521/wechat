<?php
namespace App\Controller\SupplyManage;

use App\Model\User;
use System\Lib\Controller as BaseController;

class SupplyController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $host = strtolower($_SERVER['HTTP_HOST']);
        if (strpos($host, 'wap.') === false) {
            $this->is_wap = false;
            $this->template = 'supply';
        } else {
            $this->is_wap = true;
            $this->template = 'supply_wap';
        }
        $this->is_wap = true;
        $this->template = 'supply_wap';
        if ($this->control != 'login' && $this->control != 'logout') {
            if (empty($this->user_id)) {
                $url = urlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
                //redirect("/login?url={$url}");
                redirect("/wxOpen/oauth/?url={$url}");
                exit;
            }
        }
        $this->user = (new User())->findOrFail($this->user_id);
        if (trim($this->user->headimgurl) == '') {
            $this->user->headimgurl = '/themes/member/images/no-img.jpg';
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}
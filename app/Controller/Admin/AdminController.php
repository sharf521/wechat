<?php
namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Model\User;
use System\Lib\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        $this->template='admin';
        //$this->control	=application('control');

/*        $this->user_typeid	=session('usertype');
        $this->permission_id=session('permission_id');
        $this->user=(new User())->findOrFail($this->user_id);
        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id) || empty($this->permission_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                $this->redirect("login?url={$url}");
                exit;
            }
        }*/

        if($this->control !='login' && $this->control !='logout'){
            $usertype = DB::table('usertype')->select('id,permission_id,is_admin')->where("id='{$this->user->type_id}'")->row();
            if ($usertype['is_admin'] != 1) {
                echo  '会员禁止登陆！';exit;
            }
            $this->user_typeid	=$usertype['id'];
            $this->permission_id= $usertype['permission_id'];
        }
        ////主界面不验证权限
        if(!in_array($this->control,array('index','login','logout','changepwd'))){
            /*if(! check_cmvalue($class.'_'.$func)){
                echo 'no permission_id';
                exit;
            }*/
            $permission_id=$this->permission_id;
            if($permission_id!='ALL'){
                $permission_id=unserialize($permission_id);
                if(empty($permission_id['func'])){
                    $permission_id['func']=array();
                }
                if(!in_array($this->control.'_'.$this->func,$permission_id['func'])){
                    echo '无权限';
                    exit;
                }
            }
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}
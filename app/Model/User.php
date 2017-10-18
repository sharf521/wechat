<?php
namespace App\Model;

use App\WeChat;
use System\Lib\DB;

class User extends Model
{
    protected $table = 'user';

    public function __construct()
    {
        parent::__construct();
        //$this->fields = array('name', 'username', 'password', 'addtime', 'status', 'lastip', 'portrait', 'times', 'zf_password', 'email', 'tel', 'qq', 'address');
    }

    function logout()
    {
        session()->remove('user_id');
        session()->remove('username');
        session()->remove('usertype');
        session()->remove('permission_id');
    }

    function login($data)
    {
        if ($data['direct'] == '1') {
            if (isset($data['id'])) {
                $user=$this->find($data['id']);
            } elseif (isset($data['openid'])) {
                $user =$this->where("openid=?")->bindValues($data['openid'])->first();
            }elseif (isset($data['unionid'])) {
                $user =$this->where("unionid=?")->bindValues($data['unionid'])->first();
            }
        } else {
            $user =$this->where("username=?")->bindValues($data['username'])->first();
            if($user->is_exist){
                if($user->password != md5(md5($data['password']) . $user->salt)){
                    return '用户名或密码错误！';
                }
            }else{
                return '该用户不存在';
            }
        }
        if ($user->is_exist) {
            if ($data['admin'] == true) {
                $usertype = DB::table('usertype')->select('id,permission_id,is_admin')->where("id={$user->type_id}")->row();
                if ($usertype['is_admin'] != 1) {
                    return '会员禁止登陆！';
                }
                session()->set('usertype', $usertype['id']);
                session()->set('permission_id', $usertype['permission_id']);
            } else {
                session()->set('usertype', 0);
                session()->set('permission_id', '');
            }
            session()->set('user_id', $user->id);
            session()->set('username', $user->username);

            (new Cart())->refresh($user->id);//更新Cart里未登陆时添加的商品

            return true;
        } else {
            return '未知错误!';
        }
    }


    public function addWeChatUser($openid,$invite_userid=0)
    {
        $return_arr=array();
        $weChat=new WeChat();
        $app=$weChat->app;
        $userServer=$app->user;
        $userInfo=$userServer->get($openid);
        $user_wx=(new UserWx())->where("openid=?")->bindValues($userInfo->openid)->first();
        $user_wx->subscribe = $userInfo->subscribe;
        $user_wx->openid = $userInfo->openid;
        $user_wx->nickname = addslashes($userInfo->nickname);
        $user_wx->sex = $userInfo->sex;
        $user_wx->city = $userInfo->city;
        $user_wx->country = $userInfo->country;
        $user_wx->province = $userInfo->province;
        $user_wx->language = $userInfo->language;
        $user_wx->headimgurl = $userInfo->headimgurl;
        $user_wx->subscribe_time = $userInfo->subscribe_time;
        $user_wx->unionid = $userInfo->unionid;
        $user_wx->remark = $userInfo->remark;
        $user_wx->groupid = $userInfo->groupid;
        $user_wx->tagid_list =json_encode($userInfo->tagid_list);
        $user_wx->save();

        $user=$this->where("openid=?")->bindValues($userInfo->openid)->first();
        $user->openid=$userInfo->openid;
        $user->headimgurl=$userInfo->headimgurl;
        $user->nickname=addslashes($userInfo->nickname);
        if($invite_userid!=0 && intval($user->id)==0){
            $invite=(new User())->find($invite_userid);
            if(!empty($invite)){
                $user->invite_userid=$invite->id;
                $user->invite_path=$invite->invite_path.$invite_userid.',';
                //更新邀请人的邀请数量
                $invite->invite_count=$invite->invite_count+1;
                $invite->save();

//                $return_arr['nickname']=$user->nickname;
//                $return_arr['openid']=$user->openid;
                $return_arr['invite_nickname']=$invite->nickname;
                $return_arr['invite_openid']=$invite->openid;
                $return_arr['invite_invite_count']=$invite->invite_count;
            }
        }
        if(intval($user->type_id)==0){
            $user->type_id=1;
        }
        $user->save();
        $user->invite_nickname=$return_arr['invite_nickname'];
        $user->invite_openid=$return_arr['invite_openid'];
        $user->invite_invite_count=$return_arr['invite_invite_count'];
        return $user;
    }




    //用户管理编辑
    function edit($data = array())
    {
        $id = (int)$data['id'];
        unset($data['id']);
        $data = $this->filterFields($data, $this->fields);
        return DB::table('user')->where('id=?')->bindValues($id)->limit(1)->update($data);
    }

    public function checkEmail($email)
    {
        if (empty($email)) {
            return '电子邮件不能为空';
        }
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,5}(\\.[a-z]{2})?)$/i";
        if (preg_match($pattern, $email)) {
            $id = DB::table('user')->where("email=?")->bindValues($email)->value('id', 'int');
            if ($id > 0) {
                return '该 电子邮件 已经被注册';
            }
            return true;
        } else {
            return "电子邮件 格式有误！";
        }
    }

    public function checkUserName($username)
    {
        if (strlen($username) < 5 || strlen($username) > 30) {
            return "用户名长度5位到15位！";
        } else {
            $id = DB::table('user')->where("username=?")->bindValues($username)->value('id', 'int');
            if ($id > 0) {
                return '用户名已经存在';
            }
            return true;
        }
    }

    /**
     * @return \App\Model\UserType
     */
    public function UserType()
    {
        return $this->hasOne('App\Model\UserType', 'id', 'type_id');
    }
    
    public function UserWx()
    {
        return $this->hasOne('App\Model\UserWx', 'openid', 'openid');
    }

    public  function Invite()
    {
        return $this->hasOne('App\Model\User', 'id','invite_userid');
    }
}
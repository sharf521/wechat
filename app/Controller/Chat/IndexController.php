<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/23
 * Time: 17:07
 */

namespace App\Controller\Chat;


use App\Model\ChatLog;
use App\Model\User;
use System\Lib\Request;

class IndexController extends ChatController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->user_id=$request->id;;
        $this->view('index');
    }

    private function socketSend($data=array())
    {
        // 建立连接
        $client = stream_socket_client('tcp://121.41.30.46:7273');
        if(!$client)exit("can not connect");
        // 模拟超级用户，以文本协议发送数据，协议末尾有换行符（发送的数据中最好有能识别超级用户的字段），
        //这样在Event.php中的onMessage方法中便能收到这个数据，然后做相应的处理即可
        fwrite($client,json_encode($data)."\n");
    }

    public function init(User $user,Request $request)
    {
        $id=$request->id;
        $user=$user->findOrFail($id);
        $data=array(
            'type'=>'init',
            'id'=>$id,
            'username'=>$user->username,
            'avatar'=>$user->headimgurl,
            'sign'=>$user->sign
        );
        $this->socketSend($data);
        echo json_encode(array('code'=>0));
    }

//init
    public function getList(User $user,Request $request)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $user_id=(int)$request->get('uid');
        $user=$user->find($user_id);
        $array=array(
            'code'=>0,
            'msg'=>'',
            'data'=>array()
        );
        $array['data']['mine'] = array(
            "username" =>$user->nickname,
            "id" => $user->id,
            "sign" => $user->sign,
            "avatar" =>$user->headimgurl,
            "status" => "online"
        );
        //好友
        $friendGroup=array(
            "groupname"=>"我的好友",
            "id"=> 1,
            "online"=> 2,
            "list"=>array()
        );
        $users=$user->where('id!=?')->bindValues($user_id)->get();
        $arr_online=array();
        $arr_hide=array();
        foreach ($users as $i=>$item){
            $u=array(
                "username" => $item->nickname,
                "id" => $item->id,
                "sign" => $item->sign,
                "avatar" =>$item->headimgurl,
                "status"=>"hide"
            );
            if (in_array($item->id, $redis->hKeys('chat_room:101'))) {
                $u['status'] = 'online';
                array_push($arr_online,$u);
            }else{
                array_push($arr_hide,$u);
            }
        }
        $friendGroup['list']=$arr_online+$arr_hide;
        $array['data']['friend']=array();
        array_push($array['data']['friend'],$friendGroup);

        //群组
        $group=array(
            "groupname"=>"我的群",
            "id"=> "101",
            "avatar"=> "http://tp2.sinaimg.cn/2211874245/180/40050524279/0"
        );
        $array['data']['group']=array();
        array_push($array['data']['group'],$group);
        $json=json_encode($array);
        echo $json;
    }

    ////查看群员接口
    public function getMembers(User $user,Request $request)
    {
        $id=(int)$request->get('id');
        $user_id=(int)$request->get('uid');
        $user=$user->find($user_id);
        $array=array(
            'code'=>0,
            'msg'=>'',
            'data'=>array()
        );
        $array['data']['owner']=array(
            "username" => $user->nickname,
            "id" => $user->id,
            "sign" => $user->sign,
            "avatar" =>$user->headimgurl
        );
        $array['data']['list']=array();
        $users=$user->where('id!=?')->bindValues($user_id)->get();
        foreach ($users as $i=>$item){
            $u=array(
                "username" => $item->nickname,
                "id" => $item->id,
                "sign" => $item->sign,
                "avatar" =>$item->headimgurl
            );
            array_push($array['data']['list'],$u);
        }
        $json=json_encode($array);
        echo $json;
    }

    //保存发送的消息
    public function post_message(ChatLog $chatLog)
    {
        $data=$_POST['data'];
        $chatLog->type=$data['to']['type'];
        $chatLog->mine_id=$data['mine']['id'];
        $chatLog->mine_username=$data['mine']['username'];
        $chatLog->mine_avatar=$data['mine']['avatar'];
        $chatLog->content=$data['mine']['content'];
        $chatLog->to_id=$data['to']['id'];
        $chatLog->to_username=$data['to']['username'];
        $chatLog->to_avatar=$data['to']['avatar'];
        $chatLog->save();
    }

    public function history(ChatLog $chatLog,Request $request)
    {
        $id=(int)$request->get('id');
        $type=$request->get('type');
        $user_id=(int)$request->get(2);
        if($type=='group'){
            $result=$chatLog->where("type='{$type}' and to_id='{$id}'")->orderBy('id desc')->pager($_GET['page'],10);
        }else{
            $where="type='{$type}' and ((mine_id='{$user_id}' and to_id='{$id}')||(mine_id='{$id}' and to_id='{$user_id}'))";
            $result=$chatLog->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        }
        $arr_arr=array();
        krsort($result['list']);
        foreach ($result['list'] as $row){
            $arr=array(
                'id'=>$row->mine_id,
                'username'=>$row->mine_username,
                'avatar'=>$row->mine_avatar,
                'type'=>$row->type,
                'content'=>$row->content,
                'created_at'=>$row->created_at
            );
            array_push($arr_arr,$arr);
        }
        $data['uid']=$user_id;
        $data['data']=json_encode($arr_arr);
        $data['page']=$result['page'];
        $this->view('history',$data);
    }

}
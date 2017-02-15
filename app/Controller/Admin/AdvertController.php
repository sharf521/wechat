<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 14:57
 */

namespace App\Controller\Admin;


use App\Model\Advert;
use App\Model\LinkPage;
use System\Lib\Request;

class AdvertController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Advert $advert,Request $request)
    {
        if (isset($_POST['showorder'])) {
            $id = $request->id;
            $showorder = $request->showorder;
            foreach ($id as $key => $val) {
                $advert=$advert->find($val);
                $advert->showorder=intval($showorder[$key]);
                $advert->save();
            }
            redirect('Advert')->with('msg','操作成功！');
        }else{
            $data['list']=$advert->orderBy('`showorder`,id')->get();
            $this->view('advert',$data);
        }
    }

    public function add(Request $request,Advert $advert,LinkPage $linkPage)
    {
        if($_POST){
            $order = (int)$request->post('showorder');
            if ($order == 0) {
                $order = 10;
            }
            $advert->name=$request->post('name');
            $advert->typeid=$request->post('typeid');
            $advert->url=$request->post('url');
            $advert->picture=$request->post('picture');
            $advert->content=$request->post('content');
            $advert->showorder=$order;
            $advert->status=1;
            $advert->save();
            redirect('Advert')->with('msg','添加成功！');
        }else{
            $data['typeid']=$linkPage->echoLink('advert_type','',array('name'=>'typeid','attr'=>" class='layui-select'"));
            $this->view('advert',$data);
        }
    }
    public function edit(Request $request,Advert $advert,LinkPage $linkPage)
    {
        $advert=$advert->findOrFail($request->id);
        if($_POST){
            $advert->name=$request->post('name');
            $advert->typeid=$request->post('typeid');
            $advert->url=$request->post('url');
            $advert->picture=$request->post('picture');
            $advert->content=$request->post('content');
            $advert->showorder=(int)$request->post('showorder');
            $advert->save();
            redirect('Advert')->with('msg','保存成功！');
        }else{
            $data['row']=$advert;
            $data['typeid']=$linkPage->echoLink('advert_type',$advert->typeid,array('name'=>'typeid','attr'=>" class='layui-select'"));
            $this->view('advert',$data);
        }
    }

    //状态切换
    public function change(Advert $advert, Request $request)
    {
        $id = (int)$request->get('id');
        $page = (int)$request->get('page');
        $advert = $advert->findOrFail($id);
        if ($advert->status == '1') {
            $advert->status = 0;
        } else {
            $advert->status = 1;
        }
        $advert->save();
        redirect('advert/?page=' . $page)->with('msg', '操作成功！');
    }
    public function delete(Request $request,Advert $advert)
    {
        $advert=$advert->findOrFail($request->id);
        $advert->delete();
        redirect('advert')->with('msg','删除成功！');
    }
}
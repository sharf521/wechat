<?php
namespace App\Model;


class ShopCategory extends Model
{
    protected $table='shop_category';
    public function __construct()
    {
        parent::__construct();
    }

    public function getListTree($user_id)
    {
        $result=$this->where("user_id=?")->bindValues($user_id)->orderBy("`showorder`,id")->get(\PDO::FETCH_ASSOC);
        //结果转换为特定格式
        $items = array();
        foreach ($result as $row) {
            $items[$row['id']] = $row;
        }
        $array=$this->genTree5($items);
        $cates=array();
        foreach ($array as $item){
            array_push($cates,$item);
            if(isset($item['son']) && is_array($item['son'])){
                $num=1;
                foreach ($item['son'] as $son){
                    if ($num == count($item['son'])){
                        $son['name']='&nbsp;&nbsp;└ '.$son['name'];
                    }else{
                        $son['name']='&nbsp;&nbsp;├ '.$son['name'];
                    }
                    array_push($cates,$son);
                    $num++;
                }
            }
        }
        return $cates;
    }

    /**
    <? if(isset($cate['son']) && is_array($cate['son'])) :
    foreach($cate['son'] as $son) : ?>
    <tr>
    <td>| ---- <?=$son['name']?></td>
    <td><?=date('Y-m-d H:i:s',$son['created_at'])?></td>
    <td>
    <a href="<?=url("category/edit/?id={$cate['id']}")?>" class="layui-btn layui-btn-mini">编辑</a>
    <a href="javascript:cateDel(<?=$cate['id']?>)" class="layui-btn layui-btn-mini">删除</a></td>
    </td>
    </tr>
    <?
    endforeach;
    endif; ?>
     */

    private function genTree5($items)
    {
        $tree = array(); //格式化好的树
        foreach ($items as $item)
            if (isset($items[$item['pid']]))
                $items[$item['pid']]['son'][] = &$items[$item['id']];
            else
                $tree[] = &$items[$item['id']];
        return $tree;
    }
}
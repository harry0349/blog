<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table        = 'category';
    protected $primaryKey   = 'cate_id';
    public $timestamps      = false;
    protected $guarded      = [];

    public function tree($title = '')
    {   
        if($title !== ''){

            $title_item = $this->select('cate_id')->where('cate_name', $title)->first();
            $pid = $title_item->cate_id;
        } else {
            $pid = 0;
        }
        $categorys = $this->orderBy('cate_order','asc')->get();

        return $this->nested($categorys, $pid);
    }

    /**
     * 递归,使用无限极分类
     * @Author   [harry]
     * @param    [array]  分类对象数组
     * @param    integer  $pid 父id
     * @param    integer  $deep 树深度
     * @return   [array]  分类对象数组
     */
    public function nested($categorys, $pid = 0, $deep = 0)
    {
        ++$deep;
        static $list = [];
        foreach ($categorys as $key => $category) {
            if($category->cate_pid == $pid) {
                $category['deep'] = $deep;
                $list[] = $category;
                $this->nested($categorys, $category->cate_id, $deep);
            }
        }
        return $list;
    }


    /*废弃不用*/
    public function getTree($data, $pid = 0)
    {
        $arr = [];
        $a = 0;
        foreach ($data as $k => $v){
            if($v->cate_pid == $pid){
                $data[$k]["_".'cate_name'] = $data[$k]['cate_name'];
                $arr[] = $data[$k];
                foreach ($data as $m => $n){
                    if($n->cate_pid == $v->cate_id){
                        $data[$m]["_".'cate_name'] = '├─ '.$data[$m]['cate_name'];
                        $arr[] = $data[$m];
                    }
                }
            }
        }

        return $arr;
    }
}

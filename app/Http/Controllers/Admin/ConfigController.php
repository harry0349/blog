<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Config;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    //get.admin/config  全部配置项列表
    public function index()
    {
        $data = Config::orderBy('conf_order','asc')->get();
        foreach ($data as $key => $value) {
            switch ($value->field_type) {
                case 'input':
                    $data[$key]->_html = '<textarea class="lg" name="conf_content">'.$value->conf_content.'</textarea>';
                    break;
                case 'textarea':
                    $data[$key]->_html = '<input type="text" class="lg" name="conf_content" value="'.$value->conf_content.'">';
                    break;
                case 'radio':
                    $states = explode(',', $value->field_value);
                    $html = '';
                    foreach ($states as $key => $state) {
                        $arr = explode('|', $state);

                        $check = ($value->conf_content == $arr[0]) ? ' checked' : '';
                        $html .= '<input type="radio" name="conf_content" value="'.$arr[0].'"'.$check.'>'.$arr[1].'&nbsp;&nbsp;&nbsp;';
                    }
                    $data[$key]->_html = $html;
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        return view('admin.config.index',compact('data'));
    }

    public function changeOrder()
    {
        $input = Input::all();
        $config = Config::find($input['conf_id']);
        $config->conf_order = $input['conf_order'];
        $re = $config->update();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '配置项排序更新成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '配置项排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //get.admin/config/create   添加配置项
    public function create()
    {
        return view('admin/config/add');
    }

    //post.admin/config  添加配置项提交
    public function store()
    {
        $input = Input::except('_token');
        $rules = [
            'conf_name'=>'required',
            'conf_title'=>'required',
        ];

        $message = [
            'conf_name.required'=>'配置项名称不能为空！',
            'conf_title.required'=>'配置项标题不能为空！',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            $re = Config::create($input);
            if($re){
                return redirect('admin/config');
            }else{
                return back()->with('errors','配置项失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get.admin/config/{config}/edit  编辑配置项
    public function edit($conf_id)
    {
        $field = Config::find($conf_id);
        return view('admin.config.edit',compact('field'));
    }

    //put.admin/config/{config}    更新配置项
    public function update($conf_id)
    {
        $input = Input::except('_token','_method');
        $re = Config::where('conf_id',$conf_id)->update($input);
        if($re){
            return redirect('admin/config');
        }else{
            return back()->with('errors','配置项更新失败，请稍后重试！');
        }
    }

    //delete.admin/config/{config}   删除配置项
    public function destroy($conf_id)
    {
        $re = Config::where('conf_id',$conf_id)->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '配置项删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '配置项删除失败，请稍后重试！',
            ];
        }
        return $data;
    }


    //get.admin/category/{category}  显示单个分类信息
    public function show()
    {

    }

}

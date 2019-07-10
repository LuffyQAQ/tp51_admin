<?php

namespace app\admin\controller;

use think\Request;
use think\Db;


class Rule extends Base
{
    /**
     * 分配菜单列表
     *
     * @return \think\Response
     */
    public function index($id)
    {
        
        $model = new \app\admin\model\AuthRule;
       
        $rows = Db::table('auth_group')->where('id',$id)->field('rules')->find();
        $arr = explode(',',$rows['rules']);
        $this->assign('arr',$arr);
        // $this->assign('rowsss',$rowsss);
        $rows = $model->getLevelData();
        $this->assign('arr',$arr);
        $this->assign('rows',$rows);
        return $this->fetch();
    }
    /**
     * 菜单列表
     * @return [type] [description]
     */
    public function indexList(){
        $admin_rule = Db::table('auth_rule')->order('id')->select();
        $nav = new \org\Leftnav;
        $rowset = $nav::rule($admin_rule);
        $this->assign('rowset',$rowset);
        return $this->fetch();
    }

    /**
     * 菜单状态禁用
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function checked($id)
    {
        $suc = Db::table('auth_rule')->where('id',$id)->setField('status',0);
        if($suc){
            return $id;  
        }
        
    }

    /**
     * 菜单状态启用
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function unchecked($id)
    {
        $suc = Db::table('auth_rule')->where('id',$id)->setField('status',1);
        if($suc){
            return $id;  
        }
    }

    /**
     * 显示添加菜单页面
     *
     * @return \think\Response
     */
    public function create()
    {
        $admin_rule = Db::table('auth_rule')->order('id asc')->select();

        $nav = new \org\Leftnav;
        $arrs = $nav::rule($admin_rule);
        $this->assign('arrs',$arrs);
        
        return $this->fetch();

    }

    /**
     * 保存新添加的菜单
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if($request->isPost())
        {
            $suc = Db::table('auth_rule')->insert($request->param());
            if($suc){
                $this->success('添加菜单成功',url('admin/rule/indexList'),'',1);
            }
            $this->error('添加菜单失败');
        }else{
            $this->error('请求失败');
        }

    }

    /**
     * 更新菜单编辑
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function usave(Request $request, $id)
    {
        //
        if($request->isPost())
        {
            $suc = Db::name('auth_rule')->where('id',$id)->update($request->post());
            if($suc){
                $this->success('更新成功',url('admin/rule/indexList'));
            }
            $this->error('更新失败');

        }else{
            $this->error('请求错误');
        }

    }

    /**
     * 显示菜单编辑单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $rows = Db::table('auth_rule')->where('id',$id)->field('id,title,name')->find();
        $this->assign('rows',$rows);
        return $this->fetch();
    }

    /**
     * 保存菜单
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if($request->isPost())
        {
            $rules = $request->post();
            $rules = implode(',', $rules['rules']);
            $data['rules'] = $rules;
            $suc = Db::table('auth_group')->where('id',$id)->update($data);
            if($suc){
                $this->success('更新成功',url('admin/group/index'));
            }
            $this->error('更新失败');

        }else{
            $this->error('请求错误');
        }

        
    }

    /**
     * 删除菜单
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $suc = Db::name('auth_rule')->where('id',$id)->delete();
        if($suc){
            $this->success('删除成功',url('admin/rule/indexList'),'',1);
        }
        $this->error('删除失败');
    }
    
}

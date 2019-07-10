<?php

namespace app\admin\controller;

use think\Request;
use think\Db;

class Role extends Base
{
    /**
     * 显示角色列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $rows = Db::table('auth_admin')
                ->join('auth_group_access','id=uid','LEFT')
                ->join('auth_group','auth_group_access.group_id=auth_group.id','LEFT')
                ->field('auth_admin.id,user,title')
                ->select();
        $this->assign('rows',$rows);
        return $this->fetch();
    }

    /**
     * 显示添加角色表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $rows = Db::table('auth_group')->select();
        $this->assign('rows',$rows);
        return $this->fetch();
    }

    /**
     * 保存角色
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if($request->isPost())
        {
            $data['user'] = $request->param('user');
            $data['pwd'] = md5($request->param('pwd'));
            $data['addtime'] = time();
            $arr['uid'] = Db::table('auth_admin')->insertGetId($data);
            if($arr['uid']){
                $arr['group_id'] = $request->param('group_id');
                $suc = Db::table('auth_group_access')->insert($arr);
                if($suc){
                    $this->success('添加用户成功',url('admin/role/index'));
                }
            }
            $this->error('添加失败');

        }else{
            $this->error('请求失败');
        }


        
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑角色表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $rows = Db::table('auth_group')->select();
        $arr = Db::table('auth_admin')->where('id',$id)->field('id,user')->find();
        $group_id = Db::table('auth_group_access')->where('uid',$arr['id'])->field('group_id')->find();
        $this->assign('arr',$arr);
        $this->assign('group_id',$group_id);
        $this->assign('rows',$rows);
        return $this->fetch();
    }

    /**
     * 保存更新的角色
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if($request->isPost())
        {
            $suc = Db::table('auth_group_access')->where('uid',$id)->update($request->post());
            if($suc){
                $this->success('更新成功',url('admin/role/index'));
            }
            $this->error('更新失败');
        }else{
            $this->error('请求错误');
        }

    }

    /**
     * 删除指定角色
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $suc = Db::table('auth_admin')->where('id',$id)->delete();
        if($suc){
            $this->success('删除成功',url('admin/role/index'),'',1);
        }
        $this->error('删除失败');
    }
}

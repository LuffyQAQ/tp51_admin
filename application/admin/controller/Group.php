<?php
namespace app\admin\controller;
use think\Request;
use think\Db;

class Group extends Base
{
    /**
     * 显示用户组列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $rows = Db::table('auth_group')->select();

        $this->assign('rows',$rows);
        return $this->fetch();
    }

    /**
     * 显示添加用户组.
     *
     * @return \think\Response
     */
    public function create()
    {
        return $this->fetch();
    }

    /**
     * 保存添加用户组
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if($request->isPost())
        {
            $data=$request->param();
            $data['status']=1;

            $suc = Db::table('auth_group')->insert($data);
            if($suc){
                $this->success('添加成功',url('admin/group/index'));
            }
            $this->error('添加失败');
        }else{
            $this->error('请求错误');
        }

    }

    /**
     * 用户组禁用
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function checked($id)
    {
        $suc = Db::table('auth_group')->where('id',$id)->setField('status',0);
        if($suc){
            return $id;  
        }
        
    }
    /**
     * 用户组正常
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function unchecked($id)
    {
        $suc = Db::table('auth_group')->where('id',$id)->setField('status',1);
        if($suc){
            return $id;  
        }
    }

    /**
     * 显示编辑用户组单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        $rows = Db::table('auth_group')->where('id',$id)->field('title,describe')->find();
        $this->assign('rows',$rows);
        return $this->fetch();
    }

    /**
     * 更新用户组的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if($request->isPost())
        {
            $suc = Db::table('auth_group')->where('id',$id)->update($request->post());
            if($suc){
                $this->success('修改成功',url('admin/group/index'));
            }
            $this->error('修改失败');

        }else{
            $this->error('请求错误');
        }

    }

    /**
     * 删除用户组
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $suc = Db::table('auth_group')->where('id',$id)->delete();
        if($suc){
           $this->success('删除成功',url('admin/group/index'));
        }
        $this->error('删除失败');
    }
}

<?php

namespace app\admin\model;

use think\Model;
use think\Db;
use think\auth\Auth;
use think\Facade\Session;

class AuthRule extends Model
{
   	 /**
     * @return [array] 返回主页三层数组嵌套
     */
    public function getLevelData(){

        //返回数据集/需要用collection进行数据转换，方便数组追加字段child
        $data = $this->select()->toArray();

        //var_dump($data);die;
        $catData = array();
        foreach($data as $k => $v){
            if($v['pid'] == 0){
                foreach($data as $k1 => $v1){
                    if($v1['pid'] == $v['id']){
                        foreach($data as $k2 => $v2){
                            if($v2['pid'] == $v1['id']){
                                $v1['child'][] = $v2;
                            }
                        }$v['child'][] = $v1;   
                    } 
                }$catData[] = $v;   
            }
        }
        //dump($catData);die;
        return $catData;
    }
    public function getMenu(){
        $uid = session('userid');
        if($uid == 1){

            $pri = $this->select()->toArray();
            
        }else{
            $pri = $this->getRuleList($uid,1);


        }
            //var_dump($pri);
            $new_pri = array();
            foreach($pri as $k => $v){
                if($v['pid'] == '0'){
                    foreach($pri as $k1 => $v1){
                        if($v1['pid'] == $v['id']){
                            $v['children'][] = $v1;
                        }
                    }
                    $new_pri[] = $v;
                }
            }

            //print_r($new_pri);die;
            return $new_pri;

        
    }

    /**
     * 获取用户权限数组
     * @param  [type] $uid  [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function getRuleList($uid, $type)
    {

        static $_authList = []; //保存用户验证通过的权限列表
        $t = implode(',', (array)$type);

        if (isset($_authList[$uid . $t])) {
            return $_authList[$uid . $t];
        }
        if (2 == 1 && Session::has('_auth_list_' . $uid . $t)) {
            return Session::get('_auth_list_' . $uid . $t);
        }


        //读取用户所属用户组
        $auth = new Auth();
        $groups = $auth->getGroups($uid);

        $ids    = [0]; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }

        $ids = array_unique($ids);

        if (empty($ids)) {
            $_authList[$uid . $t] = [];

            return [];
        }
        $map = [
            'id'   => $ids,
            'type' => $type
        ];

        //读取用户组所有权限规则
        $rules = Db::table('auth_rule')->where($map)->field('id,pid,title,condition,name')->select();


        return $rules;
    }
}

<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\facade\Env;
use geetest\lib\GeetestLib;


class Admin extends Controller
{
    public function geetest()
    {
        $GtSdk = new GeetestLib(config('captcha_id'), config('private_key'));

        $data = array(
            "user_id" => session('userid'), # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $GtSdk->pre_process($data, 1);
        session('gtserver',$status);
        session('user_id', $data['user_id']);
        echo $GtSdk->get_response_str();
    }
    public function login()
    {
        return $this->fetch();
    }
    //验证管理员
    public function logging(Request $request)
    {
        if($request->isPost())
        {
            if(config('captcha_type') == 1)
            {
                $GtSdk = new GeetestLib(config('captcha_id'), config('private_key'));

                $data = array(
                    "user_id" => session('userid'), # 网站用户id
                    "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
                    "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
                );


                if (session('gtserver') == 1) {   //服务器正常
                    $result = $GtSdk->success_validate($request->param('geetest_challenge'), $request->param('geetest_validate'), $request->param('geetest_seccode'), $data);
                    if (!$result) {
                        $this->error('获取验证失败');
                    }
                }else{  //服务器宕机,走failback模式
                    if (!$GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
                        $this->error('服务器宕机,获取验证失败');
                    }
                }
            }else{
                 if(!captcha_check($request->param('captcha'))){
                     // 验证失败
                     $this->error('验证码不正确');
                 };
            }

            $user = $request->param('user');
            $pwd  = md5($request->param('pwd'));
            $suc = Db::table('auth_admin')->where('user',$user)->where('pwd',$pwd)->find();

            if($suc){
                session('userid',$suc['id']);
                session('username',$suc['user']);
                return redirect(url('/admin/index'));
            }
            return $this->redirect(url('/admin/login'));

        }else{
            $this->error('请求错误');
        }

    }
    //管理员退出
    public function logout()
    {
        session('userid',null);
        session('username',null);
        return redirect(url('/admin/login'));
    }
    /**
     * 清除缓存
     */
    public function clear() {
        if (delete_dir_file(Env::get('runtime_path') . 'cache') && delete_dir_file(Env::get('runtime_path'). 'temp')) {
            return json(['code' => 1, 'msg' => '清除缓存成功']);
        } else {
            return json(['code' => 0, 'msg' => '清除缓存失败']);
        }
    }
    public  function err()
    {
        $this->error('暂无操作权限');
    }
}

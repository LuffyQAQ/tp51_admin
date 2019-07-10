<?php

namespace app\http\middleware;
use think\auth\Auth;


class CheckAuth
{
    public function handle($request, \Closure $next)
    {


    	/**
         * 权限验证
         */
         //获取添加规则；换句话说获取当前模块、控制器、方法
         $rule = $request->module() . '/' . $request->controller() . '/' . $request->action();


         //实例化Auth类调用check方法来执行验证 注意Auth的命名空间
         $res = (new Auth())->check($rule,session('userid'));


         //权限校检，系统管理员直接返回
         if(session('userid') == 1){

             return $next($request);
         }
         if(!$res){
            //不通过的时候做出判断
             return  redirect('/admin/err');

         }
        return $next($request);

           

    }
}
 
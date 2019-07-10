<?php

namespace app\admin\controller;

use think\Controller;

class Base extends Controller
{

    public function initialize()
    {
        $model = new \app\admin\model\AuthRule;

        $menu = $model->getMenu();

        $this->assign('menu', $menu);
    }

}

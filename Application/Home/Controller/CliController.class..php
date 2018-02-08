<?php

namespace Home\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class CliController extends HomeController {

    //系统首页
    public function index(){

        echo 234242;exit;
        $this->getSeoMeta();
        $this->display();
    }

}
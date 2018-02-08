<?php

namespace Mobile\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends MobileController {

	//系统首页
    public function index(){
        $this->display();       
    }
       
}
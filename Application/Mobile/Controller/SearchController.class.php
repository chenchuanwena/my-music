<?php

namespace Mobile\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class SearchController extends MobileController {

	//系统首页
    public function index($k=""){
		$k = text_filter($k);
    	if (!empty($k)){
			$map['status']=1;
			$map['name'] = array('like',"%{$k}%");
			$list = M('Songs')->where($map)->field('id,name,up_uid,up_uname,listens,add_time')->order('add_time DESC')->limit(100)->select();
			$this->assign('k',$k);
			$this->assign('list',$list);
			$this->display();			
		
		}else{
			$this->error('请填写关键字！');	
		}		       
    }
	
}
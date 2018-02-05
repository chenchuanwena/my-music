<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2029 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Article\Controller;
use Think\Controller;
/**
 * 文档模型控制器
 */
class SiteController extends  ArticleController {
	public function index(){
		$this->display();
	}

	/* 文档分类检测 */
	public function detail($name = ''){
		/* 标识正确性检测 */
		$model	= M('Site');
		
		if (is_numeric($name)){
			$data	= $model->find($name);
		}else{
			$data	= $model->where(array('name'=>$name))->find();
		}

		/* 获取分类信息 */
		
		if($data){
			$this->assign('info',$data);
			$this->display('detail');
		} else {
			$this->error('你查看的文档不存在！');
		}
	}
	
	public function _empty($method) {
		
		if (intval($method) || ctype_alpha($method)){
			$this->detail($method);
		}else{
    		$this->error('你查看的文档不存在！');
    	}
	}

}

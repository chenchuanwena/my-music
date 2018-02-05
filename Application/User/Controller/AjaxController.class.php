<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace User\Controller;
use Think\Controller;
class AjaxController extends UserController {
    public function index(){
		$this->show('非法操作');
	}
	
	public function findData(){
		 if (IS_AJAX){
		 	//sleep(3);
		 	$table 	= ucfirst(I('post.table'));
		 	$sort 	= I('post.sort');	
		 	if ($sort){
		 		$map['sort'] = $sort;
		 	}else{
		 		$map['sort'] = '0';
		 	} 	
		 	if($table == "Artist" || $table == "Album"){
		 		$data = M($table)->field('id,name')->where($map)->select();
				$this->ajaxReturn($data);
			}
		 }else{
		 	$this->error('非法请求');
		 }
		
	}
	
}
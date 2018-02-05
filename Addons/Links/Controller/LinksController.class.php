<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Addons\Links\Controller;
use Admin\Controller\AddonsController; 

class LinksController extends AddonsController{
	/* 添加友情连接 */
	public function add(){
		$this->meta_title ="添加友情连接";
		$current = U('/addons/adminlist',array('name'=>'Links'));
		$this->assign('current',$current);
		$this->display(T('Addons://Links@Links/edit'));
	}
	
	/* 编辑友情连接 */
	public function mod(){
		$this->meta_title ="修改友情连接";
		$id     =   I('get.id','');
		$current = U('/addons/adminlist',array('name'=>'Links'));
		$detail = D('Addons://Links/Links')->detail($id);
		$this->assign('info',$detail);
		$this->assign('current',$current);
		$this->display(T('Addons://Links@Links/edit'));
	}
	
	/* 禁用友情连接 */
	public function forbidden(){
		$id     =   I('get.id','');
		if(D('Addons://Links/Links')->forbidden($id)){
			$this->success('成功禁用该友情连接', U('/addons/adminlist',array('name'=>'Links')));
		}else{
			$this->error(D('Addons://Links/Links')->getError());
		}
	}
	
	/* 启用友情连接 */
	public function off(){
		$id     =   I('get.id','');
		if(D('Addons://Links/Links')->off($id)){
			$this->success('成功启用该友情连接', U('/addons/adminlist',array('name'=>'Links')));
		}else{
			$this->error(D('Addons://Links/Links')->getError());
		}
	}
	
	/* 删除友情连接 */
	public function remove(){	
		$id     =   I('get.id','');
		if(D('Addons://Links/Links')->del($id)){
			S('linkList',null);
			$this->success('删除成功', U('/addons/adminlist',array('name'=>'Links')));
		}else{
			$this->error(D('Addons://Links/Links')->getError());
		}
	}
	
	/* 更新友情连接 */
	public function update(){
		$res = D('Addons://Links/Links')->update();
		if(!$res){
			$this->error(D('Addons://Links/Links')->getError());
		}else{
			S('linkList',null);
			if($res['id']){
				$this->success('更新成功', U('/addons/adminlist',array('name'=>'Links')));
			}else{
				$this->success('新增成功', U('/addons/adminlist',array('name'=>'Links')));
			}
		}
	}
}

<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Addons\Slider\Controller;
use Admin\Controller\AddonsController; 

class SliderController extends AddonsController{
	/* 添加幻灯片 */
	public function add(){
		$this->meta_title = "新增幻灯片";
		$current = U('/addons/adminlist',array('name'=>'Slider'));
		$this->assign('current',$current);
		$this->display(T('Addons://Slider@Slider/edit'));
	}
	
	/* 编辑幻灯片 */
	public function mod(){
		$this->meta_title = "修改幻灯片";
		$id     =   I('get.id','');
		$current = U('/addons/adminlist',array('name'=>'Slider'));
		$detail = D('Addons://Slider/Slider')->detail($id);
		$this->assign('info',$detail);
		$this->assign('current',$current);
		$this->display(T('Addons://Slider@Slider/edit'));
	}
	
	/* 禁用幻灯片 */
	public function forbidden(){
		$id     =   I('get.id','');
		if(D('Addons://Slider/Slider')->forbidden($id)){
			$this->success('成功禁用该幻灯片', U('/addons/adminlist',array('name'=>'Slider')));
		}else{
			$this->error(D('Addons://Slider/Slider')->getError());
		}
	}
	
	/* 启用幻灯片 */
	public function off(){
		$id     =   I('get.id','');
		if(D('Addons://Slider/Slider')->off($id)){
			$this->success('成功启用该幻灯片', U('/addons/adminlist',array('name'=>'Slider')));
		}else{
			$this->error(D('Addons://Slider/Slider')->getError());
		}
	}
	
	/* 删除幻灯片 */
	public function remove(){
		$id     =   I('get.id','');
		if(D('Addons://Slider/Slider')->del($id)){
			S('SliderList', null);
			$this->success('删除成功', U('/addons/adminlist',array('name'=>'Slider')));
		}else{
			$this->error(D('Addons://Slider/Slider')->getError());
		}
	}
	
	/* 更新幻灯片 */
	public function update(){
		$res = D('Addons://Slider/Slider')->update();
		if(!$res){
			$this->error(D('Addons://Slider/Slider')->getError());
		}else{
			S('SliderList', null);
			if($res['id']){
				$this->success('更新成功', U('/addons/adminlist',array('name'=>'Slider')));
			}else{
				$this->success('新增成功', U('/addons/adminlist',array('name'=>'Slider')));
			}
		}
	}
}

<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;
use Think\Controller;
class  SiteController extends AdminController {
    public function index($type="about"){
		A('Article')->getMenu();
    	
		$map['appname']   = $type;  	
		$list = $this->lists('Site',$map,'id desc');

        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
		$this->assign('type', $type);
        $this->assign('list', $list);
        $this->meta_title = '关于网站';
        $this->display();
	}
    
    public function add($type="about"){

        if(IS_POST){ //提交表单
			$model	= D('Site');
			if($data	= $model->create()) {
				if($model->add($data)){
					$this->success('新增成功！', U('index?type='.$type));
				}else{
					$this->success('新增失败！', U('index?type='.$type));
				}
			}else{			
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			A('Article')->getMenu();
		
			$this->assign('type', $type);
            /* 获取分类信息 */
            $this->assign('data',       null);
            $this->meta_title = '新增网站文档';
            $this->display('edit');
        }
    }
		
	public function mod($type="about" ,$id=0){
		$model	= D('Site');
        if(IS_POST){ //提交表单		
			if($data	= $model->create()) {
				if($model->save($data)){
					$this->success('修改成功！', U('index?type='.$type));
				}else{
					$this->success('修改失败！', U('index?type='.$type));
				}
			}else{			
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			A('Article')->getMenu();
			$this->assign('type', $type);
            /* 获取分类信息 */
			$data = array();
            /* 获取数据 */
            $data = $model->field(true)->find($id);
            if(false === $data){
                $this->error('获取后台数据信息错误');
            }
            $this->assign('data', $data);
            $this->meta_title = '编辑网站文档';
            $this->display('edit');
        }
    }
		
			

}
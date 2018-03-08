<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Admin\Controller;
use Think\Controller;
class  SeoController extends AdminController {
    public function index(){ 	
		$list = $this->lists('SeoRule',$map,'id desc',true);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('list', $list);
        $this->meta_title = 'SEO管理';
        $this->display();
	}
	
	//新增SEO规则
	public function add(){
		if(IS_POST){
            $model	= D('SeoRule');
            $data = $model->create();
            if($data){
                $id = $model->add();
                if($id){
                    $this->success('新增成功');
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($model->getError());
            }
        } else {
			$this->meta_title = '新增SEO规则';
			$this->display();
        }						
	}
	public function mod($id = 0){
        if(IS_POST){
            $model	= D('SeoRule');
            $data = $model->create();
            if($data){
                if($model->save()!== false){
                    $this->success('更新成功',Cookie('__forward__'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($model->getError());
            }
        } else {
            $data = array();
            /* 获取数据 */
            $data = M('SeoRule')->field(true)->find($id);
            if(false === $data){
                $this->error('获取后台数据信息错误');
            }
            $this->assign('data', $data);
			$this->meta_title = '修改专辑类型';
			$this->display('add');
        }
	}
	
	/**
     * 删除
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('SeoRule')->where($map)->delete()){
            //记录行为
            //action_log('update_channel', 'channel', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}
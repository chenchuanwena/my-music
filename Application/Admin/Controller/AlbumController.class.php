<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2029 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;
use Think\Controller;
class AlbumController extends AdminController {
    public function index($status = null,$title = null,$pos=null){
		$Album  =  D('Album');

        if(isset($title)){
            $map['name']   =   array('like', '%'.$title.'%');
        }
        if(isset($status)){
            $map['status']  =   $status;
        }else{
            $map['status']  =   array('in', '0,1,2');
        }
        if ( isset($_GET['time-start']) ) {
            $map['update_time'][] = array('egt',strtotime(I('time-start')));
        }
        if ( isset($_GET['time-end']) ) {
            $map['update_time'][] = array('elt',24*60*60 + strtotime(I('time-end')));
        }
		if(!empty($pos)){
            $map[] = "position & {$pos} = {$pos}";
        }
        $list = $this->lists($Album,$map,'id desc','id,name,type_name,artist_name,genre_name,hits,sort,recommend,position,rater,add_time,status');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('positions', C('ALBUM_POSITION')); 
        $this->assign('list', $list);
        $this->meta_title = '专辑管理';
        $this->display();
	}
	public function add(){
		if(IS_POST){
            $model= D('Album');	
            if(false !== $model->update()){
				$map['uid'] =I('post.add_uid');
				M("Member")->where($map)->setInc('albums',1);//增加专辑数量
                $this->success('新增成功！', U('index'));				
            } else {
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }	
        } else {
			$this->assign('positions', C('ALBUM_POSITION')); 
			$this->meta_title = '添加专辑';
			$this->display();
        }
	}
	
	public function mod($id = 0){
        if(IS_POST){
            $model= D('Album');
			$res = $model->update();
            if(false !== $res['status']){
				//判读是否更新了名称
				$name=  M('Songs')->where(array('album_id' =>$res['id']))->field('album_name')->find();
				if (!empty($name) && $name !== $res['name']){
					//更新全部歌曲
					M('Songs')->where(array('album_id'=>$res['id']))->setField('album_name',$res['name']);
				}	
                $this->success('编辑成功！',Cookie('__forward__'));
            } else {
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $data = array();
            /* 获取数据 */
            $data = M('Album')->field(true)->find($id);
            if(false === $data){
                $this->error('获取后台数据信息错误');
            }
			$this->assign('positions', C('ALBUM_POSITION')); 
            $this->assign('data', $data);
			$this->meta_title = '修改专辑';
			$this->display('add');
        }
	}	
    
   	public function setpos () {
   		if (IS_AJAX){
   			$ids=I('id');
   			$data['position'] =I('position');
   			$data['update_time'] = NOW_TIME;
   			$res = M('Album')-> where(array('id'=>array('in',$ids)))->save($data);	
			if ($res){
				$this->success('推荐成功');
			}else{
				$this->error('推荐失败');
			}
		}else{
		 	$this->error('非法请求');
		}  		
    }
	
    public function setStatus () {
    	$ids    =   I('request.id');
        $status =   I('request.status');
        if(empty($ids)){
            $this->error('请选择要操作的数据');
        }
        $map['id'] = array('in',$ids);
		$res  = D('album')->where($map)->setField('status',$status);
		if ($res){ // 设置对应专辑歌曲的状态
			foreach ($ids as $v){			
				D('Songs')->where(array('album_id'=>$v))->setField('status',$status);
			}
		}
		$text = $status? '启用' : '禁用';
		
		$this->success($text.'成功',Cookie('__forward__'));   	
    }
	
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Album')->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }    
}
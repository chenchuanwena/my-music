<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Admin\Controller;
use Think\Controller;
class ArtistController extends AdminController {
    public function index($status = null,$title = null,$pos=null){
		$Artist	=   D('Artist');
        /* 查询条件初始化 */
        $map = null;
        if(isset($title)){
            $map['name']   =   array('like', '%'.$title.'%');
        }
		if(!empty($pos)){
            $map[] = "position & {$pos} = {$pos}";
        }
        $list = $this->lists($Artist,$map,'add_time desc','id,name,type_name,region,hits,sort,position,add_time,status');
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
		$this->assign('positions', C('ARTIST_POSITION')); 
        $this->assign('list', $list);
        $this->meta_title = '艺术家管理';
        $this->display();
	}
	public function add(){
		if(IS_POST){
            $model	= D('Artist');
            if(false !== $model->update()){
                $this->success('新增成功！', U('index'));				
            } else {
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }	
        } else {
            $this->assign('positions', C('ARTIST_POSITION'));
			$this->meta_title = '添加歌手';
			$this->display();
        }

	}
	
	public function mod($id = 0){
        if(IS_POST){
            $model	= D('Artist');
			$res = $model->update();
            if(false !== $res['status']){
				//判读是否更新了名称
				$name=  M('Songs')->where(array('artist_id' =>$res['id']))->field('artist_name')->find();
				if (!empty($name) && $name !== $res['name']){
					//更新全部歌曲
					M('Songs')->where(array('artist_id'=>$res['id']))->setField('artist_name',$res['name']);					
					//更新全部专辑
					M('Album')->where(array('artist_id'=>$res['id']))->setField('artist_name',$res['name']);
				}				
								
                $this->success('编辑成功！',Cookie('__forward__'));
            
			} else {
                $error = $model->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $data = array();
            /* 获取数据 */
            $data = M('Artist')->field(true)->find($id);
            if(false === $data){
                $this->error('获取后台数据信息错误');
            }
			$this->assign('positions', C('ARTIST_POSITION'));
            $this->assign('data', $data);
			$this->meta_title = '修改艺术家';
			$this->display('add');
        }
	}
	
	public function setpos () {
   		if (IS_AJAX){
   			$ids=I('id');
   			$data['position'] =I('position');
   			$data['update_time'] = NOW_TIME;
   			$res = M('Artist')-> where(array('id'=>array('in',$ids)))->save($data);	
			if ($res){
				$this->success('推荐成功');
			}else{
				$this->error('推荐失败');
			}
		}else{
		 	$this->error('非法请求');
		}  		
    }
	
	/**
     * 删除
     */
    public function del(){
        $id = array_unique((array)I('ids',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Artist')->where($map)->delete()){
            //记录行为
            //action_log('update_channel', 'channel', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}
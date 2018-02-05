<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;
use Think\Controller;
/**
 * 公共操作处理控制器
 */
class HandleController extends HomeController {
	protected $view = null;
	
	protected function _initialize(){
		if (!IS_AJAX) $this->error('请求类型错误，访问失败！');
		if (!$this->uid = is_login()) {
			 $data['info']   =   '请登录后再操作！'; // 提示信息内容
	         $data['status'] =   -1;  // 状态 如果是success是1 error 是0 ,-1没有登录
	         $data['url']    =   U('Member/login'); //跳转地址
	         $this->ajaxReturn($data);			
		}
	}	
	
	//收藏
    public function fav ($id = 0,$type=1){
		$this->todo(M('UserFav'),$id,$type,'favtimes','收藏');
	}
		
	//喜欢
    public function like ($id = 0,$type=1){
		$this->todo(M('UserLike'),$id,$type,'likes','喜欢');
	}	

	//推荐
    public function recommend ($id = 0,$type=1){
		$this->todo(M('UserRecommend'),$id,$type,'likes','自荐');
	}	
	
	//顶
    public function digg ($id = 0){
		if (is_numeric($id)){
			$key	= get_client_ip(1);
			$key 	= 'digg_'.$key;
			$diggs = F($key);
			if ( empty($diggs[$id]) ||  $diggs[$id]+60*60*24  < time() ){
				$diggs[$id] =  time();
				$diggs = F($key,$diggs);
				M('Songs')->where(array('id'=>$id))->setInc('digg');
				$this->success('成功点赞！');
			}else{
				$this->error('你已经点过赞了！');
			}
			
		}else{
			$this->error('请求失败，参数错误！');
		}
	}	

	//添加关注
    public function follow ($id = 0){
		!offSpite($handle.$field) && $this->error('亲，累了吧，休息下再来！');
		
		!(int)$id && $this->error('请求失败，参数错误！');				
		
		if ($this->uid == $id )$this->error('自己不能关注自己哦！');	
		
		$model				= M('UserFollow');
		$memeber			= M('Member');
		$map['follow_uid']	= $id;
		$map['uid']			= $this->uid;
		$return	=	array(
			'status' => 1,
			'remove' => 0,
			'action' => 1
		);		

				
		if ($model->where($map)->find()){
			if($model->where($map)->delete()){
				$memeber->where(array('uid'=>$id))->setDec('fans'); //减少次数
				$memeber->where(array('uid'=>$this->uid))->setDec('follows'); 
				$return['info']		= '成功移除关注';
				$return['remove']	= 1;
			}else{
				$return['status']	= 0;
				$return['info']		= '移除关注失败，请重试';
			}
			$return['action']	= 0;
		}else{
			$map['create_time'] = NOW_TIME;	
			if($model->add($map)){
				$memeber->where(array('uid'=>$id))->setInc('fans'); //增加次数
				$memeber->where(array('uid'=>$this->uid))->setInc('follows');
				$return['info']		= '成功添加关注';
			}else{
				$return['status']	= 0;
				$return['info']		= '添加关注失败，请重试'; 				
			}
		}
		$this->ajaxReturn($return);
	}

	//顶踩 下次在做
	
	//公共添加	
	protected function todo ($model,$id,$type,$field,$info,$handle){
		if (!offSpite($handle.$field))$this->error('亲，累了吧，休息下再来！');
  
		$type = (int)$type;
		if ($type && (int)$id){
			switch ($type) {
				case 1:
					$table = M('Songs');
					break;						
				case 2:
					$table = M('Artist');
					break;
				case 3:
					$table = M('Album');
					break;			
				case 4:
					$table = M('Video');
					break;				
					
				default:
					$this->error('你请求的类型不存在！');
			}				
			//验证是否已经收藏
			$map['uid']  		= $this->uid;
			$map['music_id'] 	= $id ;
			$map['type'] 		= $type;
			$return	=	array(
				'status' => 1,
				'remove' => 0,
				'action' => 1
			);
			
			if (!$name = $table->where(array('id'=>$id))->getField('name')){
				$this->error('你请求的数据不存在！');
			}		
			
			if( !$model->where($map)->find()) {
				$map['create_time'] = NOW_TIME;	
				if($model->add($map)){							
					$table ->where(array('id'=>$id))->setInc($field); //增加次数
					$return['info']		= '成功添加'.$info.'['.$name.']';
				}else{
					$return['status']	= 0;
					$return['info']		= '添加'.$info.'失败，请重试'; 
				}
			}else{        		
				if($model->where($map)->delete()){
					$table ->where(array('id'=>$id))->setDec($field); //减少次数
					$return['info']		= '成功移除'.$info.'['.$name.']';
					$return['remove']	= 1;
				}else{
					$return['status']	= 0;
					$return['info']		= '移除'.$info.'失败，请重试'; 
				}
				$return['action']	= 0;
			}
			$this->ajaxReturn($return);
		}else{
			$this->error('请求失败，参数错误！');
		}
	
    } 

}
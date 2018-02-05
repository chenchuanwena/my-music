<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Admin\Model;
use Think\Model;


class TagModel extends Model {
	protected $tname;
	
    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
		array('name','getName', self::MODEL_BOTH, 'callback'),
		array('alias','getAlias', self::MODEL_BOTH, 'callback'),
		array('add_time', NOW_TIME, self::MODEL_INSERT),
        array('status', 1, self::MODEL_INSERT),
    );
	
	protected function getName($name) { 
		$name = trim($name);
		$this->tname = $name;
		return $name;
	}	
	
	protected function getAlias($sort) { 	
		if (empty($sort)){			
			//汉字获取拼音
			import('JYmusic.PinYin');
			$pinYin = new \PinYin();
			$sort = $pinYin->Pinyin($this->tname);
		}
		return $sort;	
	}
	/*	添加音乐标签
	*	$tags 传过来的是字符串
	*/
	public function addMusicTags ($tags,$id,$type=1){
		$tags 		= 	trim($tags);
		if (!empty($tags)){		
			//获取标签ids
			$tags 		=	$this->where(array('name'=>array('in',$tags )))->getField('id',true);
			$dataList 	= 	array();
			for ($i=0; $i < count($tags); $i++){
				$dataList[] = array(
					'music_id'	=>	$id, 
					'tag_id'	=>	$tags[$i],
					'type_id'	=>$type
				);
			}
			M('MusicTag')->addAll($dataList);
		}
	}
	
	
	/*	更新音乐标签
	*	$tags 传过来的是字符串
	*/
	public function updateMusicTags ($tags,$id,$type=1){
		$tags 			= trim($tags);
		//获取标签ids
		if(!empty($tags)){
			$tags 		= $this->where(array('name'=>array('in',$tags )))->getField('id',true);
		}
		$musicTagModel 	= M('MusicTag');		
		//判断是否更新标签		
		$oldTags 		= $musicTagModel->where(array('music_id'=>$id,'type_id'=>$type))->getField('tag_id',true);
		//不存在标签记录
		if (empty($tags) && !empty($oldTags)){//删除标签					
			
			$musicTagModel->where(array('music_id'	=>	$id))->delete();
		
		
		}elseif(!empty($tags) && empty($oldTags)){
			foreach ($tags as $v){
				$dataList[] = array(
					'music_id'	=>	$id, 
					'tag_id'	=>	$v,
					'type_id'	=>	$type
				);					

			}
			$musicTagModel->addAll($dataList);
		}else{//存在  检测是否更新
			//获取新增id
			$addIds = array_diff($tags, $oldTags);
			//获取移除id	
			$delIds	= array_diff($oldTags,$tags);

			if ( !empty($addIds )){
				foreach ($addIds as $v){
					$dataList[] = array(
						'music_id'	=>	$id, 
						'tag_id'	=>	$v,
						'type_id'	=>	$type
					);					

				}
				$musicTagModel->addAll($dataList);
			}	
			if (!empty($delIds)){
				foreach ($delIds as $v){
					$map = array(
						'music_id'	=>	$id, 
						'tag_id'	=>	$v,
						'type_id'	=>	$type
					);					
					$musicTagModel->where($map)->delete();
				}
							
			}
		}
		
	}

}

<?php
// +----------------------------------------------------------------------
// | Author: 战神巴蒂<31435391@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;


class ArtistModel extends Model {
    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
		array('position', 'getPosition', self::MODEL_BOTH, 'callback'),
		array('sort', 'getSort', self::MODEL_BOTH, 'callback'),
		array('type_id', 'getTypeId', self::MODEL_BOTH, 'callback'),
		array('type_name', 'getType', self::MODEL_BOTH, 'callback'),
		array('region_id', 'getRegionId', self::MODEL_BOTH, 'callback'),
		array('region', 'getRegion', self::MODEL_BOTH, 'callback'),
        array('add_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', 1, self::MODEL_INSERT),		
		//array('cover_url', 'getcover', self::MODEL_BOTH,'callback'),
    );
	protected function getRegionId($id ) { 
		if (!empty($id)){
			return $id;
		}else{
			return 1;
		}
    }
	
	protected function getRegion($region) { 
		if (!empty($region)){
			return $region;
		}else{
			return '内地';
		}
    }
	
	protected function getTypeId($id) {
		if (!empty($id)){
			return $id;
		}else{
			return 0;
		}
    }
	
	protected function getType($type) { 
		if (!empty($type)){
			return $type;
		}else{
			return '其它';
		}
    }

    
	protected function getSort($sort) { 
		if (!empty($sort) && ctype_alnum($sort)){
			return $sort;
		}else{
			$name = I('post.name');
			return get_str_sort ($name);
		}
    }
	
	protected function getPosition($position){
        if(!is_array($position)){
            return 0;
        }else{
            $pos = 0;
            foreach ($position as $key=>$value){
                $pos += $value;		//将各个推荐位的值相加
            }
            return $pos;
        }
    }

	protected function getcover($url) {
   		if(!empty($url)) {
			return $url;
		}else{
			return '';
		}
    }
    
	/**
     * 更新专辑信息
     * @return boolean 更新状态
     * @author 战神~~巴蒂
     */
    public function update($post=null){
		$post = empty($post)? I('post.'): $post;	
		$data = $this->create($post);
        if(!$data){ //数据对象创建错误
            return false;
        }
			
		/* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add();
        }else{
			$res['name'] = $data['name'];		
            $res['id'] = $data['id'];
			$res['status'] = $this->save();
        }
        return $res;
	}

}

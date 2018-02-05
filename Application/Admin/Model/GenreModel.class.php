<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;


class GenreModel extends Model {
    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('add_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', 1, self::MODEL_BOTH),
		array('cover_url', 'getcover', self::MODEL_BOTH,'callback'),
    );


	function getcover($url) {
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

<?php
// +----------------------------------------------------------------------
// | Author: 战神~~巴蒂
// +----------------------------------------------------------------------

namespace User\Model;
use Think\Model\RelationModel;


class MemberModel extends RelationModel {
	
    protected $_validate = array(
        array('nickname', 'require', '昵称不能为空', 0 ,'regex', 1),
        //array('nickname','','昵称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
      	array('nickname', '4,32', '昵称长度4-32字符', 0 ,'length', 3),
      	array('nickname','checknickname','昵称已经存在！',0,'function'), // 自定义函数验证密码格式
      	array('qq', 'number', 'qq号只能为数字', 2 ,'regex', 3),
      	array('signature', '5,500', '签名长度5-500字符', 2 ,'length', 3),
		array('province', 'number', '请正确选择所在省', 2 ,'regex', 3),
		array('city', 'number', '请正确选择所在市', 2 ,'regex', 3),
		array('district', 'number', '请正确选择所在区', 2 ,'regex', 3),
		array('year', 'number', '请正确选择所在省', 2 ,'regex', 3),
		array('city', 'number', '请正确选择所在市', 2 ,'regex', 3),
		array('district', 'number', '请正确选择所在区', 2 ,'regex', 3)
    );

    protected $_auto = array(
		array('location','getLocation',3,'callback'), //获取省市字符串
		array('birthday','getBirthday',3,'callback'),
		array('signature','text_filter',3,'function'),
        array('status', 1, self::MODEL_BOTH)
    );
    
	protected function getLocation (){
		$loca = "";
		$province 	= I('post.province');
		$city 		= I('post.city');
		$district 	= I('post.district');
		if (!empty($province) ||!empty($city) || !empty($district) ){
		$model 		= M('District');
			if ($province){
				$loca .= $model->getFieldById($province,'name');			
			}
			if ($city){
				$loca .= $model->getFieldById($city,'name');			
			}
			if ($district){
				$loca .= $model->getFieldById($district,'name');			
			}
		}
		return $loca;
	}
	
	protected function getBirthday (){
		$birthday 	= "";
		$year 		= intval(I('post.year'));
		$month 		= intval(I('post.month'));
		$day 		= intval(I('post.day'));
		if (!empty($year) ||!empty($month) || !empty($month) ){
			$birthday	= $year .'-'. $month .'-'.$day;
		}else{
			$birthday = '1000-01-01';
		}
		return $birthday;
	}
	
	protected $_link = array(
	    'Dept' => array(    
	    	'mapping_type'  => self::HAS_MANY,    
	    	'class_name'    => 'Songs',    
	    	'foreign_key'   => 'up_uid',
	    	'parent_key'    => 'uid',
	    	'mapping_name'  => 'music', 
	    	'mapping_fields'=> 'id,name',
	    	'mapping_limit' => 3,
	    	'mapping_order'=> 'add_time DESC',
	    ),
    );    

}

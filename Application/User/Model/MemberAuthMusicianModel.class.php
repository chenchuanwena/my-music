<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace User\Model;
use Think\Model;

class MemberAuthMusicianModel extends Model {
	protected $_map = array(         
		'fileid' =>'attach_id',    
	);
	
    protected $_validate = array(
        array('realname', 'require', '真实姓名不能为空', 0 ,'regex', 1),
        array('realname','','真实姓名已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
      	array('realname', '2,32', '真实姓名长度4-32字符', 0 ,'length', 3),
		array('idcard', 'require', '身份证号码不能为空', 0 ,'regex', 1),
      	array('idcard','checkIdcard','请填写有效的身份证号码！',0,'callback'), // 自定义函数验证密码格式
		array('phone', 'require', '手机号不能为空', 0 ,'regex', 1),
		array('phone', '/^1[34578]\d{9}$/', '请填写正确的手机号', 2 ,'regex', 3),
		array('attach_id', 'require', '请上传证件照片', 0 ,'regex', 1),
		array('attach_id', 'number', '请上传证件照片', 2 ,'regex', 3),
		array('reason', 'require', '认证理由不能为空', 0 ,'regex', 1),
		array('reason', '20,255', '认证理由20-255字符', 2 ,'length', 3)		
    );

    protected $_auto = array(
		array('uid',UID, self::MODEL_BOTH),	
		array('group_id',3, self::MODEL_BOTH),	
		array('realname','text_filter',3,'function'),
		array('reason','text_filter',3,'function'),
        array('create_time', NOW_TIME, self::MODEL_BOTH),
		array('status', 2, self::MODEL_BOTH)
    );
	
	protected function checkIdcard ($idcard){
		
		if(strlen($idcard) == 15){
            //身份证正则表达式(15位)  
			return preg_match("/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/", $idcard);
        }
		if(strlen($idcard) == 18){
            //身份证正则表达式(18位) 
			return preg_match("/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/", $idcard);
        }
		return false;
	}
    
}

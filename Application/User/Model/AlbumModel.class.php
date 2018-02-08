<?php
// +----------------------------------------------------------------------
// | Author: 战神~~巴蒂
// +----------------------------------------------------------------------

namespace User\Model;
use Think\Model;


class AlbumModel extends Model {
	protected $_map = array(         
		'alb' 		=>'name', 
		'type'  	=>'type_id',		
		'img_url'  	=>'cover_url',
		'intr'		=>'introduce'
	);
	
    protected $_validate = array(
        array('name', 'require', '专辑名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
		array('name', '2,40', '专辑名称长度2-40个字符', 0 ,'length', 3),
		array('type_id', 'require', '请选着所属类型', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('type_id', 'number', '请选着所属类型', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('cover_url', 'require', '请选择封面', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH)
    );

    protected $_auto = array(
		array('name','text_filter',3,'function'),
		array('type_name','typeName',3,'callback'), 
        array('add_uid', UID, self::MODEL_INSERT),
        array('add_uname','getName',3,'callback'), 
		array('cover_id',0, self::MODEL_BOTH),
		array('cover_url','text_filter',3,'function'),
		array('introduce','text_filter',3,'function'),
		array('add_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', 1, self::MODEL_BOTH),
    );
    protected  function getName () {
     	return get_userName(UID);
    }
	protected  function typeName () {	
		$id 	= intval(I('post.type'));
		$name	='';
		if ($id){
			$name	= M('AlbumType')->getFieldById($id,'name');
		}
     	return $name;
    }

}

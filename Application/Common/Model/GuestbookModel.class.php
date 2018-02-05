<?php

namespace Common\Model;
use Think\Model;

/**
 * Comment模型
 */
class GuestbookModel extends Model{
	    protected $_validate = array(
        array('content', 'require', '内容不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
      	array('content', '2,300', '内容长度2-300字符', self::MUST_VALIDATE ,'length', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('content','text_filter',3,'function'),        
        array('user_ip','get_client_ip',3,'function'),
        array('status', 1, self::MODEL_BOTH),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );
        
    protected  function getName () {
     	return get_nickname(UID);
    }
           
    public $model = array(
        'title'=>'',//新增[title]、编辑[title]、删除[title]的提示
        'template_add'=>'',//自定义新增模板自定义html edit.html 会读取插件根目录的模板
        'template_edit'=>'',//自定义编辑模板html
        'search_key'=>'',// 搜索的字段名，默认是title
        'extend'=>1,
    );

    public $_fields = array(
        'id'=>array(
            'name'=>'id',//字段名
            'title'=>'ID',//显示标题
            'type'=>'num',//字段类型
            'remark'=>'',// 备注，相当于配置里的tip
            'is_show'=>3,// 1-始终显示 2-新增显示 3-编辑显示 0-不显示
            'value'=>0,//默认值
        ),
        'title'=>array(
            'name'=>'content',
            'title'=>'评论内容',
            'type'=>'string',
            'remark'=>'',
            'is_show'=>1,
            'value'=>0,
            'is_must'=>1,
        ),
    );
}

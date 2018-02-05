<?php

return array(
    'type'=>array(
        'title'=>'开启同步登录：',
        'type'=>'checkbox',
        'options'=>array(
            'Qq'=>'腾讯',
            'Sina'=>'新浪',
			'Douban'=>'豆瓣',
        ),
    ),
    'meta'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'接口验证代码：',//表单的文字
        'type'=>'textarea',         //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',             //表单的默认值
        'tip'=>'需要在Meta标签中写入验证信息时，拷贝代码到这里。'
    ),

	'QqKEY'=>array(
		'title'=>'QQ互联KEY：',
		'type'=>'text',
		'value'=>'',
		'tip'=>'申请地址：http://connect.qq.com',
	),
	'QqSecret'=>array(
		'title'=>'QQ互联密匙：',
		'type'=>'text',
		'value'=>'',
		'tip'=>'',
	),

	'SinaKEY'=>array(
		'title'=>'新浪KEY：',
		'type'=>'text',
		'value'=>'',
		'tip'=>'申请地址：http://open.weibo.com/',
	),
	'SinaSecret'=>array(
		'title'=>'新浪密匙：',
		'type'=>'text',
		'value'=>'',
		'tip'=>'',
	),
	
	'DoubanKEY'=>array(
		'title'=>'豆瓣KEY：',
		'type'=>'text',
		'value'=>'',
		'tip'=>'申请地址：http://developers.douban.com/',
	),
	'DoubanSecret'=>array(
		'title'=>'豆瓣密匙：',
		'type'=>'text',
		'value'=>'',
		'tip'=>'',
	),
	


);

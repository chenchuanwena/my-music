<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

return array(
	'comment_type'=>array(//配置在表单中的键名 ,这个会是config[random]
		'title'=>'使用类型:',	 //表单的文字
		'type'=>'radio',		 //表单的类型：text、textarea、checkbox、radio、select等
		'options'=>array(		 //select 和radio、checkbox的子选项
			'1'=>'畅言',		 //值=>文字
			'2'=>'有言',
			'3'=>'多说',
		),
		'value'=>'1',			 //表单的默认值
	),
	'group'=>array(
		'type'=>'group',
		'options'=>array(
			'changyan'=>array(
				'title'=>'畅言配置',
				'options'=>array(
					'comment_appid_changyan'=>array(
						'title'=>'畅言APPID:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'你的畅言APPID <a href="http://bbs.jyuu.cn/forum.php?mod=viewthread&tid=73&extra=page%3D1" target="_blank"> 点击此处配置同步用户登录</a>'
					),
					'comment_appkey_changyan'=>array(
						'title'=>'畅言APPKEY:',
						'type'=>'long_text',
						'value'=>'',
						'tip'=>'你的畅言APPKEY'
					),
					'comment_css_changyan'=>array(
						'title'=>'自定义畅言样式:',
						'type'=>'textarea',
						'value'=>'',
						'tip'=>'你也可以在畅言设置！'
					),
				)
			),
			'youyan'=>array(
				'title'=>'友言配置',
				'options'=>array(
					'comment_uid_youyan'=>array(
						'title'=>'账号id:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'填写自己登录友言后的uid,填写后可进相应官方后台'
					),
					'comment_key_youyan'=>array(
						'title'=>'同步登录认证密钥',
						'type'=>'text',
						'value'=>'',
						'tip'=>'用于cookie加密的认证密钥,<a href="http://bbs.jyuu.cn/forum.php?mod=viewthread&tid=74&extra=page%3D1" target="_blank"> 点击此处配置同步用户登录</a>'
					),
					'comment_css_youyan'=>array(
						'title'=>'自定义畅言样式:',
						'type'=>'textarea',
						'value'=>'',
						'tip'=>'你也可以在有言设置！ 最新版风格不支持自定义'
					),
				)
			),
			'duoshuo'=>array(
				'title'=>'多说配置',
				'options'=>array(
					'comment_short_name_duoshuo'=>array(
						'title'=>'短域名',
						'type'=>'text',
						'value'=>'',
						'tip'=>'每个站点一个域名,只填写名称即可'
					),
					/*'comment_token_duoshuo'=>array(
						'title'=>'密钥',
						'type'=>'long_text',
						'value'=>'',
						'tip'=>'多说分配的站点密钥'
					),*/
					
					'comment_form_pos_duoshuo'=>array(
						'title'=>'表单位置:',
						'type'=>'radio',
						'options'=>array(
							'top'=>'顶部',
							'buttom'=>'底部'
						),
						'value'=>'buttom'
					),
					'comment_data_list_duoshuo'=>array(
						'title'=>'单页显示评论数',
						'type'=>'text',
						'value'=>'10'
					),
					'comment_data_order_duoshuo'=>array(
						'title'=>'评论显示顺序',
						'type'=>'radio',
						'options'=>array(
							'asc'=>'从旧到新',
							'desc'=>'从新到旧'
						),
						'value'=>'asc'
					),
					'comment_css_duoshuo'=>array(
						'title'=>'自定义多说样式:',
						'type'=>'textarea',
						'value'=>'',
						'tip'=>'你也可以在多说设置！'
					),
				)
			)
		)
	)
);

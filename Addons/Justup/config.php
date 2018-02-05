<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
return array(
	'up_on'=>array(
		'title'=>'是否开启:',	 //表单的文字
		'type'=>'radio',		 //表单的类型：text、textarea、checkbox、radio、select等
		'options'=>array(
			'0'=>'关闭',		 //值=>文字
			'1'=>'开启',
		),
		'value'=>'0',
	),
	'up_type'=>array(//配置在表单中的键名 ,这个会是config[random]
		'value'=>'1',
		'title'=>'使用类型:',	 //表单的文字
		'type'=>'radio',		 //表单的类型：text、textarea、checkbox、radio、select等
		'options'=>array(
			/*'1'=>'远程FTP',*/		 //值=>文字
			'2'=>'阿里云OSS',
			'3'=>'七牛云存储',
		),
		'value'=>'1',			 //表单的默认值
	),
	'group'=>array(
		'type'=>'group',
		'options'=>array(
			/*'FTP'=>array(
				'title'=>'FTP配置',
				'options'=>array(
					'ftp_host'=>array(
						'title'=>'FTP服务器:',
						'type'=>'long_text',
						'value'=>'',
						'tip'=>'FTP服务器的IP或绑定的域名'
					),
					'ftp_user'=>array(
						'title'=>'FTP用户名:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'FTP服务器的用户名'
					),
					'ftp_pwd'=>array(
						'title'=>'FTP密码:',
						'type'=>'password',
						'value'=>'',
						'tip'=>'FTP服务器的用户密码'
					),
					'ftp_port'=>array(
						'title'=>'FTP端口:',
						'type'=>'num',
						'value'=>'21',
						'tip'=>'FTP服务器一般默认端口为21'
					),
				)
			),*/
			'alioss'=>array(
				'title'=>'阿里云OSS',
				'options'=>array(
					'alioss_id'=>array(
						'title'=>'Access Key ID:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'填写阿里云oss的Access Key ID&nbsp;<a href="http://bbs.jyuu.cn/forum.php?mod=viewthread&tid=306" target="_blank">查看设置教程</a>'
					),
					'alioss_key'=>array(
						'title'=>'Access Key Secret',
						'type'=>'long_text',
						'value'=>'',
						'tip'=>'填写阿里云oss加密的认证密钥'
					),
					'alioss_host'=>array(
						'title'=>'OSS域名:',
						'type'=>'long_text',
						'value'=>'',
						'tip'=>'OSS外网域名或绑定的域名'
					),
					'alioss_bucket'=>array(
						'title'=>'Bucket Name',
						'type'=>'text',
						'value'=>'',
						'tip'	=> 'OSS存储空间名称'
					),
					'alioss_dir'=>array(
						'title'=>'上传目录:',
						'type'=>'text',
						'value'=>'music/',
						'tip'=>'如果没有将会自动创建，注意以一定要 / 结尾'
					)
				)
			),
			'qiniu'=>array(
				'title'=>'七牛云存储',
				'options'=>array(
					'qiniu_ak'=>array(
						'title'=>'七牛AK',
						'type'=>'long_text',
						'value'=>'',
						'tip'=>'七牛云存储AK'
					),
					
					'qiniu_sk'=>array(
						'title'=>'七牛SK',
						'type'=>'long_text',
						'value'=>'',
						'tip'	=> '七牛云存储SK'
					),
					'qiniu_bucket'=>array(
						'title'=>'Bucket名称',
						'type'=>'text',
						'value'=>'',
						'tip'	=> '七牛云存储空间名称'
					),
					'qiniu_domain'=>array(
						'title'=>'域名',
						'type'=>'long_text',
						'value'=>'',
						'tip'	=> '七牛分配测试域名或自定义域名'
					),
					'qiniu_timeout'=>array(
						'title'=>'超时时间',
						'type'=>'num',
						'value'=>'3600',
						'tip'	=> ''
					),
				)
			)
		)
	)
);

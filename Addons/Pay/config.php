<?php
return array(
	'pay_type'=>array(//配置在表单中的键名 ,这个会是config[random]
		'title'=>'支付类型:',	 //表单的文字
		'type'=>'checkbox',		 //表单的类型：text、textarea、checkbox、radio、select等
		'options'=>array(		 //select 和radio、checkbox的子选项
			'alipay'=>'支付宝',		 //值=>文字
		),
		'value'=>'alipay',			 //表单的默认值
	),
	'pay_ratio'=>array(
		'title'=>'交易比例',
		'type'=>'num',		 
		'value'=>'1',
		'tip'=>'设置金币交易比例,填写10表示1元兑换10个金币，默认1:1'
	),
	'value'=>'1',//表单的默认值
	'group'=>array(
		'type'=>'group',
		'options'=>array(
			'Alipay'=>array(
				'title'=>'支付宝',
				'options'=>array(
					'alipay_partner'=>array(
						'title'=>'合作身份者id:',
						'type'=>'text',		 
						'value'=>'',
						'tip'=>'合作身份者id，以2088开头的16位纯数字'
					),
					'alipay_key'=>array(
						'title'=>'安全检验码:',
						'type'=>'long_text',
						'value'=>'',	
						'tip'=>'安全检验码，以数字和字母组成的32位字符'
					),
					'alipay_seller_email'=>array(
						'title'=>'收款帐户:',
						'type'=>'text',
						'value'=>'',
						'tip'=>'你的支付宝帐户'
					),
				)
			)
		)
	)
);	
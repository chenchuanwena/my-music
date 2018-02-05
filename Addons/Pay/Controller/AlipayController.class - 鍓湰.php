<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Addons\Pay\Controller;
use Common\Controller\Addon;
use Home\Controller\AddonsController;


class AlipayController extends AddonsController {

	protected  $config;
	protected  $payRatio;

	public function __construct(){
		
		parent::__construct();
		header("Content-type: text/html; charset=utf-8"); 
		$conf = get_addon_config('Pay');
		//支付比例
		$this->payRatio = intval($conf['pay_ratio']);
		
		$this->config = array(
			'partner'		=> trim($conf['alipay_partner']),
			'seller_email' 	=> trim($conf['alipay_seller_email']),
			'seller_id'		=> trim($conf['alipay_partner']),
			'key'			=> trim($conf['alipay_key']),
			'notify_url'	=> 'http://'.$_SERVER['HTTP_HOST'].U('/addons/pay/alipay/donotify'),
			'return_url'	=> 'http://'.$_SERVER['HTTP_HOST'].U('/addons/pay/alipay/doreturn'),
			'sign_type'    	=> strtoupper('MD5'),
			'input_charset' => strtolower('utf-8'),
			'cacert'    	=> dirname(dirname(__FILE__)).'\\Alipay\cacert.pem',
			'transport'    	=> 'http',
			'payment_type' 	=> "1",
			'service' 		=> "create_direct_pay_by_user",
		);
	}

	public function index(){
		require_once(dirname(dirname(__FILE__))."/Alipay/alipay_submit.class.php");
		
		$post 	= I('post.');
		$ratio	= $this->payRatio;
		$price  = intval($post['price']);		
		$post['subject'] 	= '账户金币充值';
		$post['body'] 		= $post['nickname'].'充值'.($ratio*$price).'金币';	
		$model 	= D('UserPayHistory');	
		
		if ($data = $model->create($post)){
			$map['out_trade_no'] = $data['out_trade_no'];			
			if (!$model->where($map)->find()){
				$data['price'] = (double)$post['price'];
				if (!$model->add($data)){
					$this->error('支付失败请重试！');
				}
			}
			$post['price'] = 0.01;
			$parameter = array(
				"service"       => $this->config['service'],
				"partner"       => $this->config['partner'],
				"seller_id"  	=> $this->config['seller_id'],
				"payment_type"	=> $this->config['payment_type'],
				"notify_url"	=> $this->config['notify_url'],
				"return_url"	=> $this->config['return_url'],	
				
				'total_fee'		=> (double)$post['price'],	//订单总金额
				'out_trade_no' 	=> $post['out_trade_no'],	//商户订单ID
				'subject'		=> $post['subject'],	//订单商品标题
				'body' 			=> $data['body'],	//订单商品描述
				"_input_charset"=> $this->config['input_charset']
			);
	
			$alipaySubmit	= new \AlipaySubmit($this->config);
			$html_text		= $alipaySubmit->buildRequestForm($parameter,"post", "确认");
			echo $html_text;
		}else{
			$this->error($model->getError());
		}
		
	}

	//异步通知
	public function donotify(){
		require_once(dirname(dirname(__FILE__))."/Alipay/alipay_notify.class.php");
		//计算得出通知验证结果
		$alipayNotify = new \AlipayNotify($this->config);
		$verify_result = $alipayNotify->verifyNotify();
		if($verify_result) {//验证成功
			//商户订单号
			$out_trade_no 	= I('post.out_trade_no');
			//支付宝交易号
			$trade_no 		= I('post.trade_no');
			//交易状态
			$trade_status 	= I('post.trade_status');
			
			if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				if (I('post.seller_id') == $this->config['seller_id']){				
					$this->orderhandle($out_trade_no,$trade_no,1,I('post.total_fee'));			
				}
			}
			echo "success";
		}else{
			$this->orderhandle($out_trade_no,$trade_no,0);
			echo "fail";
		}

	}

	//同步通知
	public function doreturn(){
		require_once(dirname(dirname(__FILE__))."/Alipay/alipay_notify.class.php");		
		$alipayNotify = new \AlipayNotify($this->config);
		$verify_result = $alipayNotify->verifyReturn();
		if ($verify_result){//验证成功
			//商户订单号
			$out_trade_no 	= I('get.out_trade_no');
			//支付宝交易号
			$trade_no 		= I('get.trade_no');
			//判断该笔订单是否已经做过处理
			$trade_status = $_GET['trade_status'];
			if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
				$this->orderhandle($out_trade_no,$trade_no,1,I('get.total_fee'));	
				$this->success('支付成功',U('/user/account'));
			}
		}
		
		dump(I('get.')); die;
		$this->error('支付失败');

	}

	//更新支付记录 并执行对应操作
	protected function orderhandle($out_trade_no,$trade_no,$status=0,$total_fee=0){
	    $model 					= D('UserPayHistory');
		$map['out_trade_no'] 	= $out_trade_no;
		
		$trade	= $model->where($map)->find();
		
		if (empty($trade)){		
			$this->error('交易不存在');
		}
		$data['total_fee']		= $total_fee;
		$data['trade_no']		= $trade_no;
		//更新订单状态
		if (intval($trade['status']) == 2 && $total_fee == $trade['price']){			
			//更新订单状态
					
			$data['status']	= $status;			
			$model->where($map)->save($data);
			
			if ( $status == 1){
				$price 	= intval($trade['price']);
				$ratio	= $this->payRatio;
				$uid	= $trade['uid'];
				
				//更新用户金币
				$coin	= $price*$ratio;
				$User	= M('Member');
				$userCoin	= $User->where(array('uid'=>$uid))->getField('coin');				
				$coin = $coin+$userCoin;				
				$User->where(array('uid'=>$uid))->setField('coin',$coin);

				//发送提醒
				$title 		= '金币充值成功';
				$content 	= '你在【'.time_format($trade['create_time']).'】,充值金币支付成功，金币增加['.$coin.']';									
				D('Notice')->send($uid,$title,$content);
			}
		}else{
			$data['status']		= 0;
			$data['total_fee']	= $total_fee;
			$model->where($map)->save($data);
		}

	} 	

}

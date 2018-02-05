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

	private $alipay_gateway 	= 'https://mapi.alipay.com/gateway.do?';
	private $https_verify_url 	= 'http://notify.alipay.com/trade/notify_query.do?';
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
		);
	}

	public function index(){		
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
			$parameter = array(				
				'total_fee'		=> (double)$post['price'],	//订单总金额
				'out_trade_no' 	=> $post['out_trade_no'],	//商户订单ID
				'subject'		=> $post['subject'],	//订单商品标题
				'body' 			=> $data['body'],	//订单商品描述
			);
	
			$this->toAlipay($parameter);
		}else{
			$this->error($model->getError());
		}
		
	}

	//异步通知
	public function donotify(){
		if ( empty($_POST) ) {
			$this->error('您查看的页面不存在');
		}
		if($this->isAlipay($_POST)) {//验证成功
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
		if ( empty($_GET) ) {
			$this->error('您查看的页面不存在');
		}
		unset($_GET['alipay']);
		
		if ( !$this->isAlipay($_GET) ) {						
			$this->error('验证失败！');
		}				
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
	
	/**
	 * 到支付宝付款
	 * @param  [array] $para [商品请求参数]
	 * @return []       []
	 */
	public function toAlipay($para){
		$para['service']		= 'create_direct_pay_by_user';
		$para['partner']		= $this->config['partner'];
		$para['payment_type']	= '1';
		$para['seller_email']	= $this->config['seller_email'];
		$para['notify_url']		= 'http://'.$_SERVER['HTTP_HOST'].U('/addons/pay/alipay/donotify');
		$para['return_url']		= 'http://'.$_SERVER['HTTP_HOST'].U('/addons/pay/alipay/doreturn');
		$para['_input_charset'] = 'utf-8';
		$para_filter	= $this->paraFilter($para);//除去空值和签名参数
		$para_sort		= $this->argSort($para_filter);//对待签名参数数组排序
		$mysign			= $this->createSign($para_sort);//生成签名结果
		$para_sort['sign'] 		= $mysign;
		$para_sort['sign_type'] = 'MD5';		
		$linkpara = $this->createLinkstring($para_sort);//生成url参数
        $url = $this->alipay_gateway.$linkpara;
       	redirect($url);//跳转到支付宝付款
	}

	
	/**
     * 验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
	public function isAlipay($data){
		if( empty($data) ) {//判断get来的数组是否为空			
			return false;
		} else {
			//验证签名是否正确
			$isTruesign = $this->getSignVeryfy($data);						
			//return $isTruesign;
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'false';
			if ( !empty($data["notify_id"]) ) {
				$responseTxt = $this->getResponse($data["notify_id"]);
			}
			if ( preg_match("/true$/i",$responseTxt) && $isTruesign ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @return 签名验证结果
     */
	private function getSignVeryfy($para_temp){
		//除去待签名参数数组中的空值和签名参数 
		$para_filter = $this->paraFilter($para_temp);
		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		if ( md5($prestr.$this->config['key']) ==  $para_temp['sign'] ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	private function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}
	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	private function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}

	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	private function createSign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		return md5($prestr.$this->config['key']);
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	private function createLinkstring($para) {
		$arg  = "";
		foreach ($para as $key => $val) {
			$arg .= $key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		return $arg;
	}

	/**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
	private function getResponse($notify_id){
		$veryfy_url = $this->https_verify_url."partner=" . $this->config['partner'] . "&notify_id=" . $notify_id;
		$curl = curl_init($veryfy_url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,dirname(dirname(__FILE__)).'\\Alipay\cacert.pem');//证书地址
		$responseText = curl_exec($curl);
		curl_close($curl);
		return $responseText;
	}


}

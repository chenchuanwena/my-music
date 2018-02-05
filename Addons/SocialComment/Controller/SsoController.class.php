<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Addons\SocialComment\Controller;
use Home\Controller\AddonsController; 
use User\Api\UserApi;

class SsoController extends AddonsController{
	
	/*多说单点登录*/
	public function dssso (){
		if ($uid = is_login()){	 //先判断是否在本站点登录
			//判断是否与多说绑定
			if (!true){
				
			}else{//没有绑定 把用户发送多说绑定
				$conf	= get_addon_config('SocialComment');				
				$url	= "http://api.duoshuo.com/sites/join.json";	
				$post	= array(
					'short_name' 	=> $conf['comment_short_name_duoshuo'],
					'access_token'	=> $conf['comment_token_duoshuo'],
					'user'			=>array(
						'user_key'		=> $uid,
						'name'			=> get_nickname($uid),
						'url'			=> U('/User/'.$uid),
						'avatar_url'	=> get_user_avatar($uid)
						
					)
				);
							
				$return = $this->send_post($url,$post);
				
				
			}
				
		}else{//在判断是否在多说点登录
			
			
		}		
	}
		
	/*有言单点登录*/
	public function uysso(){
		if ($uid = is_login()){						
			$nickname 	= get_nickname($uid);			
			$avatar 	= get_user_avatar($uid);
			$userurl	= U('/User/'.$uid);
			$conf		= get_addon_config('SocialComment');
			$key		= $conf['comment_key_youyan'];
			$encode_data = array(
				'uid'		=> $uid,
				'uname'		=> get_nickname($uid),
				'email'		=> "",
				'uface'		=> get_user_avatar($uid),
				'ulink'		=> U('/User/'.$uid),
				'expire'	=> 3600
			);
			setcookie('syncuyan', $this->des_encrypt(json_encode($encode_data), $key), time() + 3600, '/', $_SERVER['HTTP_HOST']); 
			echo "已经登录！"; die;
		}else{
			setcookie('syncuyan', 'logout', time() + 3600, '/', 'jyuu.cn');
		}
		
	} 
	


	/* 畅言单点登录用户信息 */
	public function cysso(){
		$uid = is_login();	
        if((int)$uid){
			$nickname 	= get_nickname($uid);			
			$avatar 	= get_user_avatar($uid);
			$userurl	= U('/User/'.$uid);
			$conf		= get_addon_config('SocialComment');
            $ret=array(  
				"is_login"		=>1, //已登录，返回登录的用户信息
				"user"			=>array(
					"user_id"		=>$uid,
					"nickname"		=>$nickname,
					"img_url"		=>$avatar,
					"profile_url"	=>$userurl,
					"sign"			=>$this->cysign($conf['comment_appkey_changyan'],$avatar,$nickname,$userurl,$uid )
				)
			);
        

        }else{
            $ret=array("is_login"=>0);//未登录
        }		
		echo $_GET['callback'].'('.json_encode($ret).');';
		die;
	}
	
	
	/*生成畅言签名*/
	public function cysign($key, $imgUrl, $nickname, $profileUrl, $isvUserId){
		$toSign = "img_url=".$imgUrl."&nickname=".$nickname."&profile_url=".$profileUrl."&user_id=".$isvUserId;
		$signature = base64_encode(hash_hmac("sha1", $toSign, $key, true));
		return $signature;
	}
	
	/*畅言登录*/
	
	public function cylogin (){		
		$cy_uid	= (int)I('get.user_id');	
		if($cy_uid){//判断畅言是否绑定了该用户		
			if(!$uid = is_login()){
				$ret=array(
					'user_id'=>'1',
					'reload_page'=>0
				);
			}else{
				$ret=array(
					'user_id'=>$uid,
					'reload_page'=>0
				);
			}

		}else{ 
			if ($cy_uid  && D('Member')->login($uid)){
				$ret= array(
					'user_id'=>$uid,
					'reload_page'=>0
				);
			}else{
				$ret=array(
					'user_id'=>'1',
					'reload_page'=>0
				);
			}
			
		}
		echo $_GET['callback'].'('.json_encode($ret).')';
	}
	
	public function cylogout (){
		if(!is_login()){
			$return=array(
				'code'=>1,
				'reload_page'=>0
			);
		}else{
			D('Member')->logout();
			$return=array(
				'code'=>1,
				'reload_page'=>0
			);
		}
	}
	
		// 有言加密，注意，加密前需要把数组转换为json格式的字符串 
	protected  function des_encrypt($string, $key) {
		$size = mcrypt_get_block_size('des', 'ecb');
		$string = mb_convert_encoding($string, 'GBK', 'UTF-8');
		$pad = $size - (strlen($string) % $size);
		$string = $string . str_repeat(chr($pad), $pad);
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		@mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $string);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		return $data;
	}
	// 解密，解密后返回的是json格式的字符串
	protected  function des_decrypt($string, $key) {
		$string = base64_decode($string);
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		@mcrypt_generic_init($td, $key, $iv);
		$decrypted = mdecrypt_generic($td, $string);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$pad = ord($decrypted{strlen($decrypted) - 1});
		if($pad > strlen($decrypted)) {
			return false;
		}
		if(strspn($decrypted, chr($pad), strlen($decrypted) - $pad) != $pad) {
			return false;
		}
		$result = substr($decrypted, 0, -1 * $pad);
		$result = mb_convert_encoding($result, 'UTF-8', 'GBK');
		return $result;
	}
	
	
	/*php post请求*/	
	protected function send_post($url, $post_data) {
			header("Content-type: text/html; charset=utf-8");
		  /*$postdata = http_build_query($post_data);
		  $options = array(
			  'http' => array(
				  'method' => 'POST',//or GET
				  'header' => 'Content-type:application/x-www-form-urlencoded',
				  'content' => $postdata,
				  'timeout' => 15 * 60 // 超时时间（单位:s）
			  )
		  );
		  
		  dump( $options);
		  $context = stream_context_create($options);
		  $result = file_get_contents($url, false, $context);
		  return $result;*/

		$ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $url);

        curl_setopt ($ch, CURLOPT_POST, 1);

        if($post_data != ''){

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        }

        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3600);

        curl_setopt($ch, CURLOPT_HEADER, false);

        $file_contents = curl_exec($ch);

        curl_close($ch);
		
		dump($file_contents); die;
        return $file_contents;
	}

}

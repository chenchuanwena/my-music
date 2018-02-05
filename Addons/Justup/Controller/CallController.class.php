<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Addons\Justup\Controller;
use Home\Controller\AddonsController; 
use Think\Upload\Driver\Qiniu\QiniuStorage;

class CallController extends AddonsController{
	public function alioss (){

		$conf		= get_addon_config('Justup');		
		$id			= $conf['alioss_id'];
		$key		= $conf['alioss_key'];
		$host		= $conf['alioss_host'];
		$dir		= trim($conf['alioss_dir'],'/').'/';
		
		$now 		= time();
		$expire 	= 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
		$end 		= $now + $expire;
		$expiration = $this->gmt_iso8601($end);
		
		//最大文件大小.用户可以自己设置
		$condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
		$conditions[] = $condition; 

		//表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
		$start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
		$conditions[] = $start; 

		$arr = array('expiration'=>$expiration,'conditions'=>$conditions);
		//echo json_encode($arr);
		//return;
		$policy 		= json_encode($arr);
		$base64_policy 	= base64_encode($policy);
		$string_to_sign = $base64_policy;
		$signature 		= base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

		$servaname	= '阿里云OSS['.$conf['alioss_bucket'].']';
		
		$response 		= array();
		$response['accessid'] 	= $id;
		$response['host'] 		= $host;
		$response['policy'] 	= $base64_policy;
		$response['signature'] 	= $signature;
		$response['expire'] 	= $end;
		$response['server_name'] 		= $servaname;
		$response['server_id']			= $this->setServer($host,$servaname);
		
		//这个参数是设置用户上传指定的前缀
		$response['dir'] = $dir;
		$this->ajaxReturn($response);
	}
	
	/*七牛云存储上传回调*/	
	public function qiniu(){
		$file = I('post.file');
		$conf	= get_addon_config('Justup');
		$accessKey 	= trim($conf['qiniu_ak']);
		
		$secretKey	= trim($conf['qiniu_sk']);
		
		$bucket		= trim($conf['qiniu_bucket']);
		
		$servaname	= '七牛云['.$bucket.']';
		
		echo  $this->uploadToken($accessKey,$secretKey,$bucket,$file,trim($conf['qiniu_timeout'])); die;	
	}
	
	//回调设置服务器
	public function qiniuServer(){
		$conf	= get_addon_config('Justup');
		$bucket		= trim($conf['qiniu_bucket']);	
		$servaname	= '七牛云['.$bucket.']';
		
		$response['server_name']= $servaname;
		$response['server_id']	= $this->setServer(trim($conf['qiniu_domain']),$servaname);
		$response['status']		= 1;
		
		$this->ajaxReturn($response);
		
	}
	protected function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new \ DateTime($dtStr);
       
		$expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }
	
	//生成七牛Token
	public function uploadToken($ak,$sk,$bucket, $key = null,$expires = 3600) {
        $deadline = time() + $expires;
        $scope = $bucket;
        if ($key !== null) {
            $scope .= ':' . $key;
        }
        $args = array();
       // $args = self::copyPolicy($args, $policy, $strictPolicy);
        $args['scope'] 		= $scope;
        $args['deadline'] 	= $deadline;
        $b = json_encode($args);
		$data = $this->base64_urlSafeEncode($b);

		$hmac = hash_hmac('sha1', $data, $sk, true);
        $hmac = $ak . ':' . $this->base64_urlSafeEncode($hmac);
		
		return $hmac . ':' . $data;
    }
	
	function base64_urlSafeEncode($data){
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }
	
	protected function setServer ($url,$name){
		if ('http://' != substr($url, 0, 7)){
			$url = 'http://'.rtrim($url,'/').'/';
		}		
		$model = M('Server');
		
		$server = $model->where(array('name'=>$name))->find();
		if (empty($server)){			
			$data['name']		= $name;
			$data['listen_url']	= $data['down_url'] = $url;
			$data['create_time']= NOW_TIME;
			$data['status']		= 1;
			$server['id']		= $model->add($data);			
			S('serverList',null);		
		}
		return $server['id'];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	


}

<?php
/**
 * 获取导航URL
 * @param  string $url 导航URL
 */
function get_nav_url($url){
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;        
        default:
			if (strpos($url,'/') === 0){
				$url = U($url);
			}else{
				$url = U('/'.$url);
			}
            //$arr = array('/user.php'=>'/index.php');
			//$url =strtr($url,$arr);
            break;
    }
    return $url;
}
/**
 * @param $url 检测当前url是否被选中
 */
function get_nav_active($url){
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
            if (strtolower($url) === strtolower($_SERVER['HTTP_REFERER'])) {
                return 1;
            }
        case '#' === substr($url, 0, 1):
            return 0;
            break;
        default:
            $url_array = explode('/', $url);
            if ($url_array[0] == '') {
                $CONTROLLER_NAME = $url_array[1];
            } else {
                $CONTROLLER_NAME = $url_array[0]; //发现模块就是当前模块即选中。
            }           
            if (strtolower($CONTROLLER_NAME) === strtolower(CONTROLLER_NAME)) {
                return 1;
            }elseif (strtolower($CONTROLLER_NAME) === strtolower(MODULE_NAME)){
				return 1;
			};
            break;
    }
    return 0;
}

/**
 * 获取回复
 */
function get_reply($id){
	return M('Message')->where(array('reply_msg_id'=>$id))->select();
}

/*获取访客*/
function get_guests($uid=0){
	$visitors = F('visitor_'.$uid);  //访客
	$list= array();
	if ($visitors){
		foreach ($visitors as $key=>$v){			
			$list[]= array_merge(array('uid'=>$key),$v);
		}
		krsort($list);
	}
	//$visitors = array_reverse($visitors);
	return  $list;
}

<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}


/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 */
function get_nav_url($url){
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;        
        default:
            $url = U($url);
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
			if ($url == '/'){
				$CONTROLLER_NAME = 'Index';
			}else{
				$url_array = explode('/', $url);
				if ($url_array[0] == '') {
					$CONTROLLER_NAME = $url_array[1];
				} else {
					$CONTROLLER_NAME = $url_array[0]; //发现模块就是当前模块即选中。
				}  
			}				
            if (strtolower($CONTROLLER_NAME) === strtolower(CONTROLLER_NAME)) {
                return 1;
            };
            break;
    }
    return 0;
}

//获取当前文档的分类链接
function get_category_url($info){
	$category = get_category($info['category_id']);		
	return !empty($category["name"])? U('/Article/Type/'.$category['name']) : U('/Article/Type/'.$category['id']);
}

/**
 *获取评论
 *@param  string $id  指定歌曲评论
 *
*/
function get_sons_comment($id,$num=null) {
	if (isset($id)) $map['infos_id'] = $id;	
	$map['model_id'] = 1;
	$num = isset($num)? $num : 3 ;
	$list= D('Comment')->where($map)->field('user,content')->limit($num)->select();
	return $list;
}

/*按字母索引查询歌手*/
function get_sort_artist (){	
	return M('Artist')->field(true)->group('sort')->select();
}



/**
 *获取曲风
 *
*/
function get_genre($num=null) {
	//if (isset($id)) $map['infos_id'] = $id;	
	//$num = isset($num)? $num : 10 ;
	$list= D('Genre')->field('name,id,pid')->order('id desc')->select();
	return list_to_tree($list);
}

/**
 *获取专辑的所有歌曲
 *
*/
function get_Album_songs($id=null) {
	if (!empty($id)){
		$map['album_id'] = $id;
		$list= D('Songs')->where($map)->field('name,id,artist_id,artist_name,genre_name,genre_id')->order('id desc')->select();
		return $list;
	}else{
		return false;
	}
}

/*获取每日更新数量*/
function updated_daily() {
	$count = array();
	$time = strtotime(date("Y-m-d"));//获取0点的时间戳
	$genre =  M('Genre')->where(array('pid'=>0))->select();//获取歌曲总数
	$map['add_time'] = array('gt',$time);
	$count['songs']  =  M('Songs')->where($map)->count();//获取歌曲总数
	//dump($count);
}



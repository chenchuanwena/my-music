<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1){
    static $count;
    if(!isset($count[$category])){
        $count[$category] = D('Document')->listCount($category, $status);
    }
    return $count[$category];
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id){
    static $count;
    if(!isset($count[$id])){
        $count[$id] = D('Document')->partCount($id);
    }
    return $count[$id];
}

//获取当前分类的顶级分类
function get_best_category($cate){	
	if($cate['pid'] != 0){
		$cate = get_category($cate['pid']);		
		get_best_category($cate);
	}
	return $cate;
}

//获取当前文档的分类链接
function get_category_url($info){
	$category = get_category($info['category_id']);		
	return !empty($category["name"])? U('Article/Type/'.$category['name']) : U('Article/Type/'.$category['id']);
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
			}
            break;
    }
    return 0;
}


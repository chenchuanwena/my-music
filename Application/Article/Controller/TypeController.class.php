<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Article\Controller;


class TypeController extends ArticleController {

	//系统首页
    public function index($id=0){				
		/* 分类信息 */
		$data = $this->category($id);
			
		/* 获取模板 */
		if(!empty($data['template_lists'])){
			$tmpl = $info['template_lists'];
		}else{
			$tmpl = 'Type_index';
		}
				
		$title = $data['title'];
		$this->title = $title;
	    $this->meta_title		= $title.' - '.C('WEB_SITE_TITLE');
		$this->meta_keywords	= !empty($category['keywords'])? $category['keywords'] : $this->bind_category['keywords'];
		$this->meta_description = !empty($category['description'])? $category['description'] :$this->bind_category['description'];
		$this->assign('data',$data);	
		$this->display($tmpl);
	}
	
	public function _empty($method) {
		//eregi("[^\x80-\xff]","$str") //中文判断 暂时不做		
		if (ctype_alnum($method) || is_numeric($method)){
			$this->index($method);
		}else{
    		$this->error('你访问的页面不存在');
    	}
	}

}
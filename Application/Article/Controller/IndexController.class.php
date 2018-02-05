<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Article\Controller;
use OT\DataDictionary;

class IndexController extends ArticleController {

	//系统首页
    public function index(){
	
		$this->display();
    }
	
    public function detail($id = 0, $p = 1){
		
		/* 标识正确性检测 */
		if( !$id && !is_numeric($id) && !ctype_alnum($id)){
			$this->error('文档不存在！');
		}		
		
		/*页码检测*/
		$p = intval($p);
		$p = empty($p) ? 1 : $p;
		/*获取详细信息 */
		$Document = D('Document');
		$info = $Document->detail($id);
		if(!$info){
			$this->error($Document->getError());
		}
		
		/* 分类信息 */
		$category = $this->category($info['category_id']);
		$this->bind_category = get_best_category($category);
		
	
		/* 获取模板 */
		if(!empty($info['template'])){//已定制模板
			$tmpl = $info['template'];
		} else { //使用默认模板
			$tmpl = 'Index_detail';
		}
		
		/* 更新浏览数 */
		$map = array('id' => $id);
		$Document->where($map)->setInc('view');
		$info['cate_url']	=  !empty($category['name'])? U('article/type/'.$category['name']) : U('article/type/'.$category['id']);
		/* 模板赋值并渲染模板 */
		$title = $info['title'];
		$this->title = $title;
	    $this->meta_title = $title .' - '.C('WEB_SITE_TITLE');
		$this->meta_keywords	= !empty($category['keywords'])? $category['keywords'] : $this->bind_category['keywords'];
		$this->meta_description = !empty($category['description'])? $category['description'] :$this->bind_category['description'];	
		
		$this->assign('category', $category);
		$this->assign('data', $info);
		$this->assign('page', $p); //页码
		$this->display($tmpl);
	}

}
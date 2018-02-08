<?php
/**
 * 战神巴蒂.
 */

namespace Common\Model;

use Think\Model;

class SeoRuleModel extends Model{
	
	protected $_map = array(         
		'act' =>'action', // 把表单中name映射到数据表的username字段          
	 );
    protected $_validate = array(
        array('title', 'require', '规则名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
        array('app', 'require', '所属模块不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
        array('controller', 'require', '所属操作不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
        array('action', 'require', '所属控制器不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
    	array('title_rule', 'getTrule',3,'callback'),
        array('keywords_rule', 'getKrule',3,'callback'),
        array('description_rule', 'getDrule',3,'callback'),
        array('status', '1', self::MODEL_BOTH),
    );
    
    protected function getTrule (){
    	$rule = I('post.title_rule');
    	$rule = !empty($rule)? $rule : C('WEB_SITE_NAME');
    	return $rule;
    }
    
    protected function getKrule (){
    	$rule = I('post.keywords_rule');
    	$rule = !empty($rule)? $rule : C('WEB_SITE_KEYWORD');
    	return $rule;
    }
    
   protected function getDrule (){
    	$rule = I('post.description_rule');
    	$rule = !empty($rule)? $rule : C('WEB_SITE_DESCRIPTION');
    	return $rule;
    }
    
    public function getCurrentMeta($action='',$controller='',$module=''){
		$action 	= empty($action)?	ACTION_NAME : $action;
		$controller = empty($controller)?	CONTROLLER_NAME : $controller;
		$module		= empty($module)?	MODULE_NAME : $module;
        $result = $this->getMeta($action, $controller, $module);
        return $result;
    }

    private function getMeta($action='',$controller='',$module=''){
	
        //查询缓存，如果已有，则直接返回
        $cacheKey 	= "{$module}_{$controller}_{$action}";         
        $cache 		= S("seo_meta");
        if($cache !== false && !empty($cache[$cacheKey]) ) {
            return $cache[$cacheKey];
        }
		//获取相关的规则
		$map['status'] = 1;
		$map['app'] = $module;
		$map['controller'] = $controller;
		$map['action'] = $action;
		$rules = $this->where($map)->field('title_rule,keywords_rule,description_rule')->find();
        if (empty($rules)){
        	array_pop($map); 
        	$rules = $this->where($map)->field('title_rule,keywords_rule,description_rule')->find();
        	if (empty($rules)){
	        	$rules['title_rule'] = '{$webtitle}';
	        	$rules['keywords_rule'] = '{$webkeywords}';
	        	$rules['description_rule'] = '{$webdescription}';
        	}
        }
       	$cache[$cacheKey] =  $rules;                
        //写入缓存
        S("seo_meta", $cache);
        //返回结果
        return $rules;
    }

}
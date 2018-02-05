<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace User\Controller;
class SpaceController extends UserController {
    /**
	* 关注用户
	*/    
    public function index(){
		$this->error();
    }
       
    /**
	* 导航
	*/    
    public function channel(){
		$this->error();
    }
       
    /**
	* 导航
	*/    
    public function setbg(){
    	if(IS_POST){    		    		
    	 	$Member =   D('UserSpace');
	        $data   =   $Member->create();
	        if($data){
	        	$res = $Member->where(array('uid'=>UID))->save();
		        if($res){
		            $this->success('修改成功！');
		        }else{
		            $this->error('修改失败！');
		        }	            
	        }else{
	        	$this->error($Member->getError());
	        }        

    	}else{
    		$this->error('非法参数！');
    	}		        
    }
    /**
	* 开通个人空间
	*/ 
   	public function open($uid){
		if (intval($uid) == UID){
			if($this->openspace($uid)){				
				$this->success('成功开通个人空间',U('Home/index/'.$uid));
			}else{
				$this->error('个人空间开通失败');
			}
		}else{
			$this->error('参数错误！');
		}  
    }
        
    /**
	* 开通个人空间
	*/ 
   	public function skin (){
   		$path = __ROOT__.trim(C('USER_SKINS_PATH'),'.');
   		/*$bnxml = @simplexml_load_file($path.'/space_skins/default_banner.xml');
    	if(is_object($bnxml)){
			$bnxml = json_encode($bnxml);
			$bnxml = json_decode($bnxml, true);
			$this->assign('default_banner',$bnxml['theme']);
		}*/
    	$bgxml = @simplexml_load_file('.'.$path.'/default_bg.xml');
    	if(is_object($bgxml)){
			$bgxml = json_encode($bgxml);		
			$bgxml = json_decode($bgxml, true);
			$skins_bg = $bgxml['theme'];
			foreach ($skins_bg as &$v){
				if (strpos($v['imgurl'],'/') == 0){
					$v['imgurl']	= $path.$v['imgurl'];
				}
				if (strpos($v['thumburl'],'/') == 0){
					$v['thumburl']	= $path.$v['thumburl'];
				}				
			}			
			$this->assign('skins_bg',$skins_bg);
		}
		$this->display();   
    }    
    
}
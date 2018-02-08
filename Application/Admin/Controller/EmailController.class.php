<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2015 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+

namespace Admin\Controller;
use Think\Controller;
class  EmailController extends AdminController {
    public function index(){
		$config = unserialize(C('MAIL_CONF'));
		$this->assign('config',$config); 	
        $this->meta_title = '邮件设置';
       	$this->display('Config/email');
	}
	
    //
    public function mod(){
		if(IS_POST){
			$config  = serialize(I('post.'));	
			if (M('Config')->where(array('name'=> 'MAIL_CONF'))->setField('value',$config)){
				S('DB_CONFIG_DATA',null);
				$this->success('配置修改成功');
			}else{
				$this->error('配置修改失败');
			}
		}else{			
			$this->error('参数错误');				
		}
    }
    
    public function test(){
    	$data=I('post.');
    	if(empty($data['sendto_email']))$this->error('请填写发送邮箱');
    	$regex = '/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[-_a-z0-9][-_a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,})$/i';
		if (!preg_match($regex, $data['sendto_email']))$this->error('电子邮件格式不正确');
    	$email = D('Mail')->test_email($data);
    	if ($email){
    		$this->success('测试邮件发送成功！');
    	}else{
    		$this->error('测试邮件发送失败！');
    	}
    }
}
<?php

namespace Home\Controller;
/**
 * 前台标签控制器
 */
class TaskController extends HomeController {
    public $secret="^&*()67890yuiop";
    public function index(){
    }
    //保存用户
    public function save_member(){
        $member_d=D('Member')->create();
        $member_d->add($member_d);
        $data['username']=$member_d['nickname'];
        $data['password']='f3ff819109883d0e309965755349fe81';
        $data['email']=$member_d['email'];
        $data['reg_time']=$member_d['reg_time'];
        $data['reg_id']='3546391945';
        $data['last_login_time']=NOW_TIME-rand_number(1,9999);
        $data['last_login_ip']='3546391945';
        $data['update_time']=NOW_TIME-rand_number(1,9999);
        $data['status']=1;
        if($member_d['sign']==md5($data['username'].$data['reg_time'].$this->secret)){
            M('Member')->add($member_d);
            M('UcenterMember')->add($data);
        }
    }
    //插入mp3
    public function save_mp3(){
        $data_post=I('post.');
        $sign=$data_post['sign'];
        unset($data_post['sign']);
        $data_str='';
        foreach($data_post as $val){
            $data_str.=$val;
        }
        if($data_post[$sign]==md5($data_str.$this->secret)){
            D('Songs')->update($data_post);
        }
    }






}
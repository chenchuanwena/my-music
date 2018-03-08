<?php

namespace Home\Controller;
/**
 * 前台标签控制器
 */
class TalkController extends HomeController
{
    public function index()
    {
        if(session('user_auth')){
            $userAuth=session('user_auth');
            $userName=$userAuth['username'];
        }else{
            $userName='turist';
        }

        $this->assign('username',$userName);
        $this->display();
    }


}
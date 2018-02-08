<?php

namespace Addons\Pay;
use Common\Controller\Addon;

/**
 * 支付宝插件
 * @author 战神~~巴蒂
 */

    class PayAddon extends Addon{

        public $info = array(
            'name'=>'Pay',
            'title'=>'支付插件',
            'description'=>'支付插件',
            'status'=>1,
            'author'=>'JYmusic',
            'version'=>'0.1'
        );
        public function install(){
			//添加钩子
			$Hooks = M("Hooks");
			$AliPlay = array(array(
				'name' => 'indexPay',
				'description' => '支付钩子',
				'type' => 1,
				'update_time' => NOW_TIME,
				'addons' => 'indexPay'
			));
			$Hooks->addAll($AliPlay,array(),true);
			if ( $Hooks->getDbError() ) {
				session('addons_install_error',$Hooks->getError());
				return false;
			}
            return true;
        }

        public function uninstall(){
			$Hooks = M("Hooks");
			$map['name']  = array('in','indexPay');
			$res = $Hooks->where($map)->delete();
			if($res == false){
				session('addons_install_error',$Hooks->getError());
				return false;
			}
            return true;
        }
		
        //实现的indexAliPlay钩子方法
        public function indexPay(){     	
			$config = $this->getConfig();
			$this->assign('out_trade_no', $this->createOrderNo());
			$this->assign('conf', $config);
			$this->display('pay');	
        }

        //生成订单号
	    public function createOrderNo() {
	        $year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
	        return $year_code[intval(date('Y')) - 2010] .
				strtoupper(dechex(date('m'))) . date('d') .
				substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('d', rand(0, 99));
	    }
	

    }
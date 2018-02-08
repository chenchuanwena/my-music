<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Common\Model;
use Think\Model;


class UserPayHistoryModel extends Model{
	
	protected $_validate = array(
        array('out_trade_no', 'require', '订单号不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
        array('price', 'require', '支付金额不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
		array('price', 'checkPrice', '支付金额不正确', 0,'callback')
   );
    
	protected $_auto = array(
        array('body','text_filter',3,'function'),
		array('uid', 'is_login',3,'function'),
        array('user_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),      
        array('create_time', NOW_TIME, self::MODEL_BOTH),
		array('status', 2, self::MODEL_BOTH)
    );

	protected function checkPrice($price) {
		if(intval($price) > 0) {
			return true;
		}
		return false;
	}
    
}

<?php
// +----------------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;


class VideoTypeModel extends Model {
    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE ,'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('add_time', NOW_TIME,3),
    );


}

<?php
// +-------------------------------------------------------------+
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Model;
use Think\Model;


class TagModel extends Model{

    public function info($id, $field = true){
        /* 获取分类信息 */
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        }elseif(ctype_alpha($id)){ //通过标识查询
            $map['alias'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }

}

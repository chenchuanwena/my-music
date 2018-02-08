<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ����� <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;
use User\Api\UserApi;

/**
 * �û�����ģ��
 */
class PictureModel extends Model{
    /* �û�ģ���Զ���� */
    protected $_auto = array(

    );
    private function getList($where,$field,$pageIndex=1,$pageSize=10,$keyField=false){
        $result=$this->where($where)->field($field)->page($pageIndex,$pageSize)->select();
        return $result;
    }
    public function getPictureByUidAndType($uid,$type,$field='*',$pageindex=1,$pageSize=10,$keyField=false){
           if(is_array($uid)){
               $uid=implode(',',$uid);
           }
           $where=array();
           $where['uid']=array('in',$uid);
           $where['type']=$type;
           return $this->getList($where,$field,$pageindex,$pageSize,$keyField);
    }
}

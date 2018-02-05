<?php
// +-------------------------------------------------------------+
// | Copyright (c) 2014-2016 JYmusic音乐管理系统                 |
// +-------------------------------------------------------------
// | Author: 战神~~巴蒂 <31435391@qq.com> <http://www.my-music.cn>  |
// +-------------------------------------------------------------+
namespace Home\Controller;

use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class BaseTaskController extends Controller
{
    protected $pageUrlList;
    protected $domain;
    public $websitName = "虫虫吉他谱";
    public $drawsoure;
    public $drawPage;
    public $drawDetail;
    public $drawsoureId;
    public $curentPageId;
    public $detailLists;
    protected $setDatas = array();

    protected function getIndexMenu()
    {

    }

    protected function getLastData()
    {
        return $this->drawsoure->find($this->drawsoureId);
    }

    //初始操作
    protected function _initialize()
    {
        $this->drawsoure = D('Drawsource');
        $this->drawDetail = D('DrawsourceDetail');
    }

    public function addPage($val, $url)
    {
        $drawPage['domain'] = $this->domain;
        $drawPage['page'] = $val['page'];
        $drawPage['page_url'] = $url;
        $drawPage['drawsource_id'] = $this->drawsoureId;
        $drawPage['gmt_create'] = NOW_TIME;
        $drawPage['gmt_update'] = NOW_TIME;
        $drawPage['status'] = 1;
        return $this->drawPage->add($drawPage);
    }

    public function urlTODetailLinks($url)
    {

    }

    public $detailSaveList;

    public function getList()
    {
        foreach ($this->pageUrlList as $value) {
            $sql = format("select *  from jy_drawsource_detail where type={0} and status=1", $value['my_music_id']);
            $list = $this->drawDetail->query($sql);
            $this->detailSaveList = array();
            foreach ($list as $val) {
                $this->getOne($val, $val['type']);
                $val['status']=2;
                $this->drawDetail->save($val);
            }
        }

    }

    public function getType($type)
    {
        $text = '';
        switch ($type) {
            case 5:
                $text = '吉他谱';
                break;
            case 4:
                $text = '最新';
                break;
            case 2:
                $text = '翻唱';
                break;
            case  1:
                $text = '原创';
                break;
            default:
                break;
        }
        return $text;
    }

    /* 空操作 */
    public function getPageUrl()
    {
        if ($this->lastData['url']) {
            $this->setDatas = unserialize($this->lastData['url']);
        } else {
            $this->setDatas = $this->pageUrlList;
        }
        foreach ($this->setDatas as &$value) {
            $sql = format("select count(*) as COUNT from jy_drawsource_detail where type={0} and DATE_FORMAT(FROM_UNIXTIME(gmt_create), '%Y-%m-%d')='{1}'", $value['my_music_id'], date('Y-m-d', NOW_TIME));
            $res = $this->drawDetail->query($sql);
            if ($res[0]['count'] > 0) {
                echo '网站名称:' . $this->websitName . "\r\n";
                echo '网站域名:' . $this->websitName . "\r\n";
                echo '类型:' . $this->getType($value['my_music_id']) . "\r\n";
                echo '今天已经抓取过了' . "\r\n";
            } else {
                for ($i = $value['page']; $i <= $value['draw_page_count']; $i++) {
                    $url = format($value['format_url'], $i);
                    $getUrl = $this->domain . $url;
                   // $this->curentPageId = $this->addPage($value, $url);
                    $this->detailLists = array();
                    $this->urlToDetailLinks($getUrl, $value['my_music_id']);
                    $this->drawDetail->addAll($this->detailLists, array(), true);
                    echo '网站名称:' . $this->websitName . "\r\n";
                    echo '网站域名:' . $this->websitName . "\r\n";
                    echo '请求链接:' . $getUrl . "\r\n";
                    echo 'success' . "\r\n";
                }
            }
        }
    }

    protected function saveLocation($content, $fileName, $saveDirectory)
    {
        $date = date('Y-m-d', NOW_TIME);
        echo $_SERVER['DOCUMENT_ROOT'];exit;
        $savePath = BASE_UPLOAD_PATH . $saveDirectory . "/{$date}/" . $fileName;
        echo $savePath;exit;
        file_put_contents($savePath, $content);
        $ossPath = oss_upload($savePath);
        return $ossPath;
    }

//获得上传的云路径地址

    function getUploadPath($path, $saveDirectory)
    {
        $getUrl = $this->domain . $path;
        $content = file_get_contents($getUrl);
        $uid = 0;
        $path = $this->saveLocation($content, basename($path), $saveDirectory);
        $create_time = NOW_TIME;
        $md5 = md5($content);
        $sha1 = sha1($content);
        $status = 1;
        $picture = array(
            'uid' => 0,
            'path' => $path,
            'url' => '',
            'type' => 4,
            'md5' => $md5,
            'sha1' => $sha1,
            'status' => 1,
            'create_time' => $create_time
        );
        return $picture;
    }

    /* 用户登录检测 */
    protected
    function pushDetail($detailUrl, $type)
    {
        $detail['detail_id'] = $this->getDetailId($detailUrl);
        $detail['detail_url'] = $detailUrl;
       // $detail['drawsource_page_id'] = $this->curentPageId;
        $detail['drawsource_id'] = $this->drawsoureId;
        $detail['gmt_create'] = NOW_TIME;
        $detail['gmt_update'] = NOW_TIME;
        $detail['status'] = 1;
        $detail['domain'] = $this->domain;
        $detail['type'] = $type;
        array_push($this->detailLists, $detail);

    }

    public function addPicture($picture)
    {
        $pid = D('Picture')->add($picture, array(), true);
        return $pid ? $pid : 0;
    }

    public function addMember($member)
    {
        $uid = D('Member')->add($member, array(), true);
        return $uid ? $uid : 0;
    }

    public function addSong($song)
    {
        $songId = D('Song')->add($song, array(), true);
        return $songId ? $songId : 0;
    }
    public function addSongExtend($songExtend)
    {
        $songExtendId = D('SongsExtend')->add($songExtend, array(), true);
        return $songExtendId ? $songExtendId : 0;
    }

    public function addUcenterMember($ucenterMember){
        $ucenterMemberId = D('UcenterMember')->add($ucenterMember, array(), true);
        return $ucenterMemberId ? $ucenterMemberId : 0;
    }
    public function updatePicture($data){
        D('Picture')->save($data);
    }

    /* 解析SEO规则 */
    protected
    function getSeoMeta($action = '', $name = '')
    {

    }


    /* 规则替换字符 */
    protected
    function replace_meta($str, $name)
    {

    }

    /**
     * 前台音乐数据通用分页列表数据集获取方法
     * @return array|false
     * 返回数据集
     */
    protected
    function lists($model, $where = array(), $order = "", $field = "", $status = '1', $listRows = 20, $total = null)
    {


    }



}

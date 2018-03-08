<?php

namespace Home\Controller;
use Admin\Controller\AuthController;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController
{

    public function export($data=array()){
        # code...
        include_once(DIR_FS_DOCUMENT_ROOT . 'classes/PHPExcel/Writer/IWriter.php');
        include_once(DIR_FS_DOCUMENT_ROOT . 'classes/PHPExcel/Writer/Excel5.php');
        include_once(DIR_FS_DOCUMENT_ROOT . 'classes/PHPExcel.php');
        include_once(DIR_FS_DOCUMENT_ROOT . 'classes/PHPExcel/IOFactory.php');
        $obj_phpexcel = new PHPExcel();
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('a1', '维度1');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('b1', '维度2');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('c1', '维度3');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('d1', '毕业生人数');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('e1', '就业率');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('f1', '签约率');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('g1', '升学率');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('h1', '创业率');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('i1', '暂不就业率');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('j1', '金额');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('k1', '完税价格');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('l1', '海关税率');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('m1', '商品税费');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('n1', '一番街商品税');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('o1', '海关商品税率');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('p1', '总运费');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('q1', 'HS');
        $obj_phpexcel->getActiveSheet()->setCellValueExplicit('r1', '完税计算工式');

        if ($data) {
            $i = 2;
            foreach ($data as $key => $row) {
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('a' . $i, $row['order_number'], PHPExcel_Cell_DataType::TYPE_STRING2);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('b' . $i, $row['pay_sn'], PHPExcel_Cell_DataType::TYPE_STRING2);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('c' . $i, '');
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('d' . $i, $row['goods_serial']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('e' . $i, $row['goods_name']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('f' . $i, $row['gc_id']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('g' . $i, '个');
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('h' . $i, $row['goods_num']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('i' . $i, $row['final_price']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('j' . $i, $row['final_price'] * $row['goods_num']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('k' . $i, $row['complete_price']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('l' . $i, $row['customs_tax']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('m' . $i, $row['customs_goods_tax']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('n' . $i, $row['tax']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('o' . $i, $row['customs_tax_percent']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('p' . $i, $row['shipping_fee']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('q' . $i, $row['HS']);
                $obj_phpexcel->getActiveSheet()->setCellValueExplicit('r' . $i, $row['complete_price_count']);

                $i++;
            }
        }
        $obj_Writer = PHPExcel_IOFactory::createWriter($obj_phpexcel, 'Excel5');
        $filename = "outexcel.xls";
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $filename . '"');
        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $obj_Writer->save('php://output');
    }
    private function buildData() {
        $rtnData = array(
            array(
                'name'=>'YanCheng_01',
                'age'=>'20',
                'addr'=>array(
                    array(
                        'ab'=>array('country'=>'China', 'province'=>'ShanDong'),
                         'ab'=>array('country'=>'China', 'province'=>'ShanDong'),
                    ),
                    array(
                        'country'=>'China',
                        'province'=>'BeiJing'
                    )
                ),
                '_DIMENSION'=>4
            ),
            array(
                'name'=>'YanCheng_02',
                'age'=>'21',
                'addr'=>array(
                    array(
                        'country'=>'China',
                        'province'=>'LanZhou'
                    ),
                    array(
                        'country'=>'China',
                        'province'=>'NingXia'
                    ), array(
                        'country'=>'China1',
                        'province'=>'NingXia'
                    )
                ),
                '_DIMENSION'=>3
            ),
            array(
                'name'=>'YanCheng_03',
                'age'=>'22',
                'addr'=>array(
                    array(
                        'country'=>'China',
                        'province'=>'JiaYuGuan'
                    )
                ),
                '_DIMENSION'=>1
            )
        );
        return $rtnData;
    }
    public function doExportData($dataArr) {
        $phpObjExcel = new \PHPExcel();
        $worksSheet = $phpObjExcel->setActiveSheetIndex(0);
        //构造表头数据_Begin
        $tmpColTitles = [];
        $firstDataEntry = $dataArr[0];
        //分配列索引
        $colIndex = 0;
        foreach($firstDataEntry as $key => $val) {
            if (preg_match('/^_/', $key)) {
                continue;
            }
            if (is_array($val)) {
                //取array下的列名称
                $val = $val[0];
                $rowNums = count($val);
                foreach ($val as $innerKey => $innerValue) {
                    $tmpColTitles[] = array(
                        'parentKey' => $key,
                        'key' => $innerKey,
                        'colIndex' => $colIndex
                    );
                    $colIndex++;
                }
            } else {
                $tmpColTitles[] = array(
                    'key'=>$key,
                    'colIndex'=>$colIndex
                );
                $colIndex++;
            }
        }
        for($i = 0; $i < count($tmpColTitles); $i++) {
            $tmpObj = $tmpColTitles[$i];
            $key = $tmpObj['key'];
            $colIndex = $tmpObj['colIndex'];
            $worksSheet->setCellValueByColumnAndRow($colIndex,1,$key);
        }
        //构造表头数据_End
        //填充单元格数据
        $currRow = 2;
        foreach ($dataArr as $dataEntry) {
            $mergeRow = $dataEntry['_DIMENSION'];
            foreach ($tmpColTitles as $colEntry) {
                $key = $colEntry['key'];
                $colIndex = $colEntry['colIndex'];
                $parentKey = (isset($colEntry['parentKey']) ? $colEntry['parentKey'] : null);
                if (empty($parentKey)) {
                    $value = $dataEntry[$key];
                    if ($mergeRow == 1) {
                        $worksSheet->setCellValueByColumnAndRow($colIndex, $currRow, $value);
                    } else {
                        $worksSheet->mergeCellsByColumnAndRow($colIndex, $currRow, $colIndex, ($currRow + $mergeRow - 1))->setCellValueByColumnAndRow($colIndex, $currRow, $value);
                    }
                } else {
                    $tmpDataArr = $dataEntry[$parentKey];
                    $innerRow = $currRow;
                    for($index = 0; $index < count($tmpDataArr); $index++) {
                        $innerDataEntry = $tmpDataArr[$index];
                        $value = $innerDataEntry[$key];
                        $worksSheet->setCellValueByColumnAndRow($colIndex, $innerRow, $value);
                        $innerRow++;
                    }
                }
            }
            $currRow += $mergeRow;
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Disposition: attachment;filename="HelloWord.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: public');
        $objWriter = \PHPExcel_IOFactory::createWriter($phpObjExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function exportConvertExcel($expTitle, $expCellName, $expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);
        //文件名称
        $fileName = $xlsTitle . date('_Ymd');
        //or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        import("Org.Util.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ');

        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
        }
        $sameValues=array();
        $begin['i']=2;
        $begin['j']=0;

        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($cellName[$j] . ($i + 2), (string)$expTableData[$i][$expCellName[$j][0]], \PHPExcel_Cell_DataType::TYPE_STRING);
                //需要合并的维度这里是灵活的，我zhi
//                if($j<2){
//                    if((string)$expTableData[$i][$expCellName[$j][0]]==(string)$expTableData[$begin['i']][$expCellName[$begin['j']][0]]){
//                        $end['i']=$i+2;
//                        $end['j']=$j;
//
//                    }
//                    if((string)$expTableData[$i][$expCellName[$j][0]]!=(string)$expTableData[$begin['i']][$expCellName[$begin['j']][0]]||($dataNum+1)==$end['i'])
//                    {
//                        //echo (string)$expTableData[$begin['i']][$expCellName[$begin['j']][0]].$end['j'].'|'.(int)$end['i']/2;exit;setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//                        //$objPHPExcel->getActiveSheet(0)->mergeCellsByColumnAndRow($begin['j'], $begin['i'], $end['j'], $end['i'])->setCellValueByColumnAndRow($end['j'],3,(string)$expTableData[$begin['i']][$expCellName[$begin['j']][0]]);
//                        $objPHPExcel->getActiveSheet(0)->mergeCellsByColumnAndRow($begin['j'], $begin['i'], $end['j'], $end['i'])->getStyle($cellName[$j].$begin['i'])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//                        $end['i']=$end['i']+1;
//                        $begin['i']=$end['i'];
//                    }
//                }
            }
        }
        ////需要合并的维度这里是灵活的，下面的J控制维度
        for ($j = 0; $j <2; $j++) {
            $beginValue=(string)$expTableData[0][$expCellName[$j][0]];
            $beginRow=2;
            for ($i = 1; $i < $dataNum; $i++) {
                    if((string)$expTableData[$i][$expCellName[$j][0]]!=$beginValue ){
                        $objPHPExcel->getActiveSheet(0)->mergeCellsByColumnAndRow($j, $beginRow,$j, $i+1)->getStyle($cellName[$j].$beginRow)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $beginValue=(string)$expTableData[$i][$expCellName[$j][0]];
                        $beginRow=$i+2;

                    }
                    if($i==$dataNum-1){
                        $objPHPExcel->getActiveSheet(0)->mergeCellsByColumnAndRow($j, $beginRow,$j, $i+2)->getStyle($cellName[$j].$beginRow)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    }
            }
        }
        //exit;

        //exit;
       // $sameValues=array_values($sameValues);
       // echo json_encode($sameValues);exit;
       // $objPHPExcel->getActiveSheet(0)->mergeCellsByColumnAndRow(0, 2, 0, 5);
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xlsx"');
        header("Content-Disposition:attachment;filename=$fileName.xlsx");
        //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    public function exportOneShellExcel($expTitle, $expCellName, $expTableData)
    {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);
        //文件名称
        $fileName = $xlsTitle . date('_Ymd');
        //or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        import("Org.Util.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ');

        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($cellName[$j] . ($i + 2), (string)$expTableData[$i][$expCellName[$j][0]], PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xlsx"');
        header("Content-Disposition:attachment;filename=$fileName.xlsx");
        //attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    private function readAllExcelDetail($filename, $file_type)
    {
        if ($file_type == 'xlsx') {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($filename);
            $count = count($objPHPExcel->getAllSheets());
            $excelsData = array();
            for ($i = 0; $i < $count; $i++) {
                array_push($excelsData, $this->getSheet($objPHPExcel, $i));
            }
        } else {
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($filename);
            $count = count($objPHPExcel->getAllSheets());
            $excelsData = array();
            for ($i = 0; $i < $count; $i++) {
                array_push($excelsData, $this->getSheet($objPHPExcel, $i));
            }
        }
        return $excelsData;
    }
    private function get_extension($file)
    {
        $info = pathinfo($file);
        return $info['extension'];
    }
    private function readExcel($filename, $encode = 'utf-8', $file_type)
    {

        return $this->readExcelDetail($filename, $file_type);

    }
    private function readExcelDetail($filename, $file_type)
    {
        if ($file_type == 'xlsx') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($filename);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $hightestrow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            $excelData = array();
        } else {
            $objReader =\ PHPExcel_IOFactory::createReader('Excel5');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($filename);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $hightestrow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            $excelData = array();
        }


        for ($row = 2; $row <= $hightestrow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }

        return $excelData;
    }
    public function exportExcel(){
        $export=I('export',false);
        if($export!==false){
            include_once VENDOR_PATH . 'PHPExcel/PHPExcel.php';
            include_once VENDOR_PATH . 'PHPExcel/PHPExcel/Writer/Excel5.php';
            include VENDOR_PATH . 'PHPExcel/PHPExcel/Reader/Excel2007.php';
            include VENDOR_PATH . 'PHPExcel/PHPExcel/Writer/Excel2007.php';
            $file_extent = $this->get_extension($_FILES['export_excel']);
            move_uploaded_file($_FILES['export_excel']['tmp_name'], 'export_excel.xlsx');
            $return_data = $this->readExcel('export_excel.xlsx', 'utf-8', 'xlsx');
//        $matchs = $this->readAllExcelDetail('match.xlsx', 'xlsx');
//        $this->match_data = $this->convertMatchData($matchs);
            $cellName=array(
                array(
                    0,
                    '维度1',
                ),
                array(
                    1,
                    '维度2',
                ),
                array(
                    2,
                    '维度3',
                ),
                array(
                    3,
                    '毕业生人数',
                ),
                array(
                    4,
                    '就业率',
                ),
                array(
                    5,
                    '签约率',
                ),
                array(
                    6,
                    '升学率',
                ),
                array(
                    7,
                    '创业率',
                ),
                array(
                    8,
                    '暂不就业率',
                ),
                array(
                    9,
                    '待就业率',
                ),
                array(
                    10,
                    '签就业协议形式就业',
                ),
                array(
                    11,
                    '签劳动合同形式就业',
                ),
                array(
                    12,
                    '其他录用形式就业',
                ),
                array(
                    13,
                    '科研助理',
                ),
                array(
                    14,
                    '国家基层项目',
                ),
                array(
                    15,
                    '地方基层项目',
                ),
                array(
                    16,
                    '应征义务兵',
                ),
                array(
                    17,
                    '自主创业',
                ),
                array(
                    18,
                    '自由职业',
                ),
                array(
                    19,
                    '升学',
                ),
                array(
                    20,
                    '出国、出境',
                ),
                array(
                    21,
                    '待就业',
                ),
                array(
                    22,
                    '不就业拟升学',
                ),
                array(
                    23,
                    '其他暂不就业'
                ),
            );

            $return_data=array_values($return_data);
//            $budata=$this->buildData();
//            $this->doExportData($budata);exit;
            $this->exportConvertExcel('exportExcel',$cellName,$return_data);
        }else{
            $this->display();
        }

    }


    public function excel()
    {
        $name = I('name');
        $names = explode('|', $name);
        $allNames = array();
        foreach ($names as $val) {
            $keys = explode(':', $val);
            $key = $keys[0];
            $zys = explode(',', $keys[1]);
            $one = array();
            $one['xy'] = $key;
            $one['zys'] = $zys;
            $allNames[] = $one;
        }
        $this->assign('all', $allNames);
        $this->display();
    }
    public function chatroom(){
        $self=$_SESSION['jy_home_']['user_auth'];
        if(!$self){
            echo "请登录";
            exit;
        }
        $self['nickname']=$self['username'];
        $self['userid']=$self['uid'];
        $self['login_time']=date('Y-m-d H:i:s',$self['last_login_time']);
        $pictures=D('Picture');
        $faceUrls= C('face_url');
        $selfAtore=$pictures->getPictureByUidAndType($self['uid'],5,'uid,path,type',1,10);

        $self['avatar']=$selfAtore?$selfAtore:$faceUrls['avataroffline'];
        $dateObj=Date('Y-m-d H:i:s',NOW_TIME);
        $member=D('Member');
        $result=$member->joinTables("member_auth_musician b",'Left Join',"a.uid","b.uid","a.uid,a.nickname",1,10,'uid');

        $uids=array_column($result['result'],'uid');

        $userPic=$pictures->getPictureByUidAndType($uids,5,'uid,path,type',1,10);
        foreach($userPic as $val){
            $result['result'][$val['uid']]['avator']=$val['path'];
        }
        $userids=array();
        foreach( $result['result'] as &$val){
            $val['avatar']=isset($val['path'])?$val['path']:$faceUrls['avataroffline'];
            $val['userid']=$val['uid'];
            $val['username']=$val['nickname'];
            array_push($userids,$val['uid']);

        }
        $userids=implode(',',$userids);
        $where['uid']=array('in',$userids);
        $auth_access=M('auth_group_access')->where($where)->select();
        $auth_access=array_column($auth_access,'group_id','uid');
        $baseUsers=array();
        $service=array();
        foreach( $result['result'] as &$val){
            $val['avatar']=isset($val['path'])?$val['path']:$faceUrls['avataroffline'];
            $val['userid']=$val['uid'];
            $val['username']=$val['nickname'];
            if(isset($auth_access[$val['uid']])&&$auth_access[$val['uid']]==7){
                array_push($service,$val);
            }else{
                array_push($baseUsers,$val);
            }
        }
        $result['result']=$baseUsers;

        $this->assign('dateobj',$dateObj);
         $result['result']=array_values($result['result']);
         $this->assign('count',$result['count']-1);
        $this->assign('count_service',count($service));
         $this->assign('users',$result['result']);
        $this->assign('services',$service);
         $this->assign('userjson',json_encode($result['result']));
         $this->assign('faceUrl', $faceUrls);
        $this->assign('self', $self);
        $this->assign('session_id', session_id());
        $this->assign('selfjson', json_encode($self));
         $this->assign('debug', 'true');
         $this->display();
    }
    //系统首页
    public function index()
    {

        /*$size = 4047336;
        $bit  = 64;
        $playtime1 = 374;
        $playtime = $size/64/127;
        dump($playtime);
        dump(gmstrftime('%H:%M:%S',$playtime));

        die;*/

        $this->getSeoMeta();
        $this->display();
    }

    public function upload()
    {
        vendor('Swoole.autoload');
        $upload=new \Upload();
        var_dump($upload);exit;
        if ($_FILES)
        {
            // header("Content-type: application/json");
            // 指定允许其他域名访问
            header('Access-Control-Allow-Origin:*');
// 响应类型
            header('Access-Control-Allow-Methods:GET');
// 响应头设置
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
            header("Content-type: application/json");
            $upload->thumb_width = 136;
            $upload->thumb_height = 136;
            $upload->thumb_qulitity = 100;
            $up_pic = $upload->save('Filedata');
            if (empty($up_pic))
            {
                echo '上传失败，请重新上传！ Error:' . $upload->error_msg;
            }
            $baseUrl=$this->get_url();
            $up_pic['thumb']=$baseUrl.$up_pic['thumb'];
            $up_pic['url']=$baseUrl.$up_pic['url'];
            echo json_encode($up_pic);
        }
        else
        {
            echo "Bad Request\n";
        }
    }

}
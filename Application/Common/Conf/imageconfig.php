<?php
$uploadData= array(
    'base_dir' => '/www/web/my-music_cn/public_html/',
    'base_url' => '/Uploads/',
);
$faceUrl = array(
    'avataroffline' => '/Template/default/static/img/avataroffline.png',
    'avatarleadermessage' => '/Template/default/static/img/avatarleadermessage.png',
    'avatarleaderonline' => '/Template/default/static/img/avatarleaderonline.png',
    'avatarworkermessage' => '/Template/default/static/img/avatarworkermessage.png',
    'avatarworkeronline' => '/Template/default/static/img/avatarworkeronline.png',
    'avatarofflinemessage' => '/Template/default/static/img/avatarofflinemessage.png',
    'webim' =>  'ws://im.swoole.com:9503'
);
$connectDatas=array(
    'upload_data'=>$uploadData,
    'face_url'=>$faceUrl
);
return $connectDatas;
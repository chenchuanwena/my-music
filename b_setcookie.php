<?php
header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
setcookie("test1", $_GET['id'], time()+3600, "/", ".my-music.cn");
echo 'set cookie test success';
?>
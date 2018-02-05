<?php
$jack = 1;
$GLOBALS['a']=1213;
$abc=new abc();
class abc
{
    function __construct()
    {
        $this->test();
    }

    private function test()
    {
        global $jack;
        echo $jack;
        echo $GLOBALS['a'];
    }
}

?>
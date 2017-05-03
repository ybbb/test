<?php
class GrandFather{
    static function getClassName(){
     echo __CLASS__.PHP_EOL;
    }
}
class Father extends GrandFather{
    static protected $son='121';
    public $daughter = '女儿';
    private $baby = '宝贝';
    static function getClassName(){
        echo __CLASS__.PHP_EOL;
    }

    static function setProperty($key, $value)
    {
        self::$$key = $value;
    }

    private function test()
    {

    }
}
class Son extends Father{

}

interface a
{
    public function test();
}
interface c
{
    public function myt();
}
interface b extends a{
    public function test111();
}
class t{
    public function test()
    {
        echo 11;
    }

    public function myt()
    {
        echo 22;
    }
}
$fatherObj = new Father();
var_dump(file_get_contents('test.php'));die;

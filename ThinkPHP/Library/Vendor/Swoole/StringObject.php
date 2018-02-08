<?php
namespace Swoole;

class StringObject
{
    protected $string;

    function __construct($string)
    {
        $this->string = $string;
    }

    function __toString()
    {
        return $this->string;
    }

    function pos($find_str)
    {
        return strpos($this->string, $find_str);
    }

    function rpos($find_str)
    {
        return strrpos($this->string, $find_str);
    }

    function ipos($find_str)
    {
        return stripos($this->string, $find_str);
    }

    function lower()
    {
        return new StringObject(strtolower($this->string));
    }

    function upper()
    {
        return new StringObject(strtoupper($this->string));
    }

    function len()
    {
        return strlen($this->string);
    }

    function substr($offset, $length = null)
    {
        return new StringObject(substr($this->string, $offset, $length));
    }

    function replace($search, $replace, &$count = null)
    {
        return new StringObject(str_replace($search, $replace, $this->string, $count));
    }

    function  startWith($needle)
    {
        return $this->pos($needle) === 0;
    }

    function contains($subString)
    {
        return $this->pos($subString) !== false;
    }

    function endWith($needle)
    {
        $length = strlen($needle);
        if ($length == 0)
        {
            return true;
        }
        return (substr($this->string, -$length) === $needle);
    }

    function split($sp, $limit = PHP_INT_MAX)
    {
        return new ArrayObject(explode($sp, $this->string, $limit));
    }

    function char($index)
    {
        return $this->string[$index];
    }

    function toArray($splitLength = 1)
    {
        return new ArrayObject(str_split($this->string, $splitLength));
    }
}

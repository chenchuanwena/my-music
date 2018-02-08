<?php
namespace Swoole;

class ArrayObject implements \ArrayAccess, \Serializable, \Countable, \Iterator
{
    protected $array;
    protected $index = 0;

    function __construct($array = array())
    {
        $this->array = $array;
    }

    function current()
    {
        return current($this->array);
    }

    function key()
    {
        return key($this->array);
    }

    function valid()
    {
        return count($this->array) >= $this->index;
    }

    function rewind()
    {
        $this->index = 0;
        return reset($this->array);
    }

    function next()
    {
        $this->index++;
        return next($this->array);
    }

    function serialize()
    {
        return serialize($this->array);
    }

    function unserialize($str)
    {
        $this->array = unserialize($str);
    }

    function __get($key)
    {
        return $this->array[$key];
    }

    function __set($key, $value)
    {
        $this->array[$key] = $value;
    }

    function offsetGet($k)
    {
        return $this->array[$k];
    }

    function offsetSet($k, $v)
    {
        $this->array[$k] = $v;
    }

    function offsetUnset($k)
    {
        unset($this->array[$k]);
    }

    function offsetExists($k)
    {
        return isset($this->array[$k]);
    }

    function contains($val)
    {
        return in_array($val, $this->array);
    }

    function join($str)
    {
        return new StringObject(implode($str, $this->array));
    }

    function insert($offset, $val)
    {
        if ($offset > count($this->array))
        {
            return false;
        }
        return array_splice($this->array, $offset, 0, $val);
    }

    function search($find)
    {
        return array_search($find, $this->array);
    }

    function count()
    {
        return count($this->array);
    }

    /**
     * 计算数组的合
     */
    function sum()
    {
        return array_sum($this->array);
    }

    function product()
    {
        return array_product($this->array);
    }

    /**
     * 向数组尾部追加元素
     * @return int
     */
    function append($val)
    {
        return array_push($this->array, $val);
    }

    /**
     * 向数组头部追加元素
     * @return int
     */
    function prepend($val)
    {
        return array_unshift($this->array, $val);
    }

    /**
     * 从数组尾部弹出元素
     * @return mixed
     */
    function pop()
    {
        return array_pop($this->array);
    }

    /**
     * 从数组头部弹出元素
     * @return mixed
     */
    function shift()
    {
        return array_shift($this->array);
    }

    /**
     * 数组切片
     * @return ArrayObject
     */
    function slice($offset, $length = null)
    {
        return new ArrayObject(array_slice($this->array, $offset, $length));
    }

    /**
     * 数组随机取值
     * @return mixed
     */
    function rand()
    {
        return $this->array[array_rand($this->array, 1)];
    }

    /**
     * 移除元素
     * @param $value
     */
    function remove($value)
    {
        unset($this->array[$this->search($value)]);
    }

    /**
     * 遍历数组
     * @param $fn callable
     * @return bool
     */
    function each(callable $fn)
    {
        return array_walk($this->array, $fn);
    }

    /**
     * 遍历数组，并构建新数组
     * @param $fn callable
     * @return ArrayObject
     */
    function map(callable $fn)
    {
        return new ArrayObject(array_map($fn, $this->array));
    }

    /**
     * 用回调函数迭代地将数组简化为单一的值
     * @param $fn callable
     * @return mixed
     */
    function reduce(callable $fn)
    {
        return array_reduce($this->array, $fn);
    }

    /**
     * 返回所有元素值
     *  @return ArrayObject
     */
    function values()
    {
        return new ArrayObject(array_values($this->array));
    }

    /**
     * 返回数组的KEY
     */
    function keys($search_value = null, $strict = false)
    {
        return new ArrayObject(array_keys($this->array, $search_value, $strict));
    }

    /**
     * 数组去重
     */
    function unique($sort_flags = SORT_STRING)
    {
        return new ArrayObject(array_unique($this->array, $sort_flags));
    }

    /**
     * 数组反序
     */
    function reverse($preserve_keys = false)
    {
        return new ArrayObject(array_reverse($this->array, $preserve_keys));
    }

    /**
     * 数组元素随机化
     */
    function shuffle()
    {
        return shuffle($this->array);
    }

    /**
     * 将一个数组分割成多个数组
     */
    function chunk($size, $preserve_keys = false)
    {
        return new ArrayObject(array_chunk($this->array, $size, $preserve_keys));
    }

    /**
     * 交换数组中的键和值
     * @return ArrayObject
     */
    function flip()
    {
        return new ArrayObject(array_flip($this->array));
    }

    /**
     * 过滤数组中的元素
     * @param $fn callable
     * @return ArrayObject
     */
    function filter(callable $fn, $flag = 0)
    {
        return new ArrayObject(array_filter($this->array, $fn, $flag));
    }

    /**
     * 转换为一个PHP数组
     * @return array
     */
    function toArray()
    {
        return $this->array;
    }
}

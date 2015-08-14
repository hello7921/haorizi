<?php

// $Id: array.php 2630 2009-07-17 16:43:52Z jerry $

/**
 * 定义 HelpArray 类

  /**
 * HelpArray 类提供了一组简化数组操作的方法
 *
 * @author YuLei Liao <liaoyulei@qeeyuan.com>
 * @version $Id: array.php 2630 2009-07-17 16:43:52Z jerry $
 * @package helper
 */
abstract class HelpArray {

    /**
     * 从数组中删除空白的元素（包括只有空白字符的元素）
     *
     * 用法：
     * @code php
     * $arr = array('', 'test', '   ');
     * HelpArray::removeEmpty($arr);
     *
     * dump($arr);
     *   // 输出结果中将只有 'test'
     * @endcode
     *
     * @param array $arr 要处理的数组
     * @param boolean $trim 是否对数组元素调用 trim 函数
     */
    static function removeEmpty(& $arr, $trim = true) {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                self::removeEmpty($arr[$key]);
            } else {
                $value = trim($value);
                if ($value == '') {
                    unset($arr[$key]);
                } elseif ($trim) {
                    $arr[$key] = $value;
                }
            }
        }
    }

    /**
     * 从一个二维数组中返回指定键的所有值
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $values = HelpArray::cols($rows, 'value');
     *
     * dump($values);
     *   // 输出结果为
     *   // array(
     *   //   '1-1',
     *   //   '2-1',
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $col 要查询的键
     *
     * @return array 包含指定键所有值的数组
     */
    static function getCols($arr, $col) {
        $ret = array();
        foreach ($arr as $row) {
            if (isset($row[$col])) {
                $ret[] = $row[$col];
            }
        }
        return $ret;
    }

    /*
     * 返回 $key;
     */

    static function getColsByKey($arr, $col) {
        $ret = array();
        foreach ($arr as $key => $row) {
            if (isset($row[$col])) {
                $ret[$key] = $row[$col];
            }
        }
        return $ret;
    }

    /**
     * 将一个二维数组转换为 HashMap，并返回结果
     *
     * 用法1：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = HelpArray::hashMap($rows, 'id', 'value');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => '1-1',
     *   //   2 => '2-1',
     *   // )
     * @endcode
     *
     * 如果省略 $value_field 参数，则转换结果每一项为包含该项所有数据的数组。
     *
     * 用法2：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = HelpArray::hashMap($rows, 'id');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => array('id' => 1, 'value' => '1-1'),
     *   //   2 => array('id' => 2, 'value' => '2-1'),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_field 按照什么键的值进行转换
     * @param string $value_field 对应的键值
     *
     * @return array 转换后的 HashMap 样式数组
     */
    static function toHashmap($arr, $key_field, $value_field = null) {
        $ret = array();
        if ($value_field) {
            foreach ($arr as $row) {
                $ret[$row[$key_field]] = $row[$value_field];
            }
        } else {
            foreach ($arr as $row) {
                $ret[$row[$key_field]] = $row;
            }
        }
        return $ret;
    }

    /**
     * 将一个二维数组按照指定字段的值分组
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 0),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     * $values = HelpArray::groupBy($rows, 'parent');
     *
     * dump($values);
     *   // 按照 parent 分组的输出结果为
     *   // array(
     *   //   1 => array(
     *   //        array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *   //        array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *   //        array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *   //   ),
     *   //   2 => array(
     *   //        array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *   //        array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *   //   ),
     *   //   3 => array(
     *   //        array('id' => 6, 'value' => '6-1', 'parent' => 3),
     *   //   ),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_field 作为分组依据的键名
     *
     * @return array 分组后的结果
     */
    static function groupBy($arr, $key_field) {
        $ret = array();
        foreach ($arr as $row) {
            $key = $row[$key_field];
            $ret[$key][] = $row;
        }
        return $ret;
    }

    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 0),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 0),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 0),
     *
     *     array('id' => 7, 'value' => '2-1-1', 'parent' => 2),
     *     array('id' => 8, 'value' => '2-1-2', 'parent' => 2),
     *     array('id' => 9, 'value' => '3-1-1', 'parent' => 3),
     *     array('id' => 10, 'value' => '3-1-1-1', 'parent' => 9),
     * );
     *
     * $tree = HelpArray::tree($rows, 'id', 'parent', 'nodes');
     *
     * dump($tree);
     *   // 输出结果为：
     *   // array(
     *   //   array('id' => 1, ..., 'nodes' => array()),
     *   //   array('id' => 2, ..., 'nodes' => array(
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //   ),
     *   //   array('id' => 3, ..., 'nodes' => array(
     *   //        array('id' => 9, ..., 'parent' => 3, 'nodes' => array(
     *   //             array(..., , 'parent' => 9, 'nodes' => array(),
     *   //        ),
     *   //   ),
     *   // )
     * @endcode
     *
     * 如果要获得任意节点为根的子树，可以使用 $refs 参数：
     * @code php
     * $refs = null;
     * $tree = HelpArray::totree($rows, 'id', 'parent', 'nodes', $refs);
     *
     * // 输出 id 为 3 的节点及其所有子节点
     * $id = 3;
     * dump($refs[$id]);
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_node_id 节点ID字段名
     * @param string $key_parent_id 节点父ID字段名
     * @param string $key_children 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     *
     * return array 树形结构的数组
     */
    static function toTree($arr, $key_node_id, $key_parent_id = 'parent_id', $key_children = 'children', & $refs = null) {
        $refs = array();
        foreach ($arr as $offset => $row) {
            $arr[$offset][$key_children] = array();
            $refs[$row[$key_node_id]] = & $arr[$offset];
        }

        $tree = array();
        foreach ($arr as $offset => $row) {
            $parent_id = $row[$key_parent_id];
            if ($parent_id) {
                if (!isset($refs[$parent_id])) {
                    $tree[] = & $arr[$offset];
                    continue;
                }
                $parent = & $refs[$parent_id];
                $parent[$key_children][] = & $arr[$offset];
            } else {
                $tree[] = & $arr[$offset];
            }
        }

        return $tree;
    }

    /**
     * 将树形数组展开为平面的数组
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $key_children 包含子节点的键名
     *
     * @return array 展开后的数组
     */
    static function treeToArray($tree, $key_children = 'children') {
        $ret = array();
        if (isset($tree[$key_children]) && is_array($tree[$key_children])) {
            $children = $tree[$key_children];
            unset($tree[$key_children]);
            $ret[] = $tree;
            foreach ($children as $node) {
                $ret = array_merge($ret, self::treeToArray($node, $key_children));
            }
        } else {
            unset($tree[$key_children]);
            $ret[] = $tree;
        }
        return $ret;
    }

    /**
     * 根据指定的键对数组排序
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     *
     * $rows = HelpArray::sortByCol($rows, 'id', SORT_DESC);
     * dump($rows);
     * // 输出结果为：
     * // array(
     * //   array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * //   array('id' => 5, 'value' => '5-1', 'parent' => 2),
     * //   array('id' => 4, 'value' => '4-1', 'parent' => 2),
     * //   array('id' => 3, 'value' => '3-1', 'parent' => 1),
     * //   array('id' => 2, 'value' => '2-1', 'parent' => 1),
     * //   array('id' => 1, 'value' => '1-1', 'parent' => 1),
     * // )
     * @endcode
     *
     * @param array $array 要排序的数组
     * @param string $keyname 排序的键
     * @param int $dir 排序方向
     *
     * @return array 排序后的数组
     */
    static function sortByCol($array, $keyname, $dir = SORT_ASC) {
        return self::sortByMultiCols($array, array($keyname => $dir));
    }

    /**
     * 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
     *
     * 用法：
     * @code php
     * $rows = HelpArray::sortByMultiCols($rows, array(
     *     'parent' => SORT_ASC,
     *     'name' => SORT_DESC,
     * ));
     * @endcode
     *
     * @param array $rowset 要排序的数组
     * @param array $args 排序的键
     *
     * @return array 排序后的数组
     */
    static function sortByMultiCols($rowset, $args) {
        $sortArray = array();
        $sortRule = '';
        foreach ($args as $sortField => $sortDir) {
            foreach ($rowset as $offset => $row) {
                $sortArray[$sortField][$offset] = $row[$sortField];
            }
            $sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', SORT_STRING ,';
        }
        if (empty($sortArray) || empty($sortRule)) {
            return $rowset;
        }
        eval('array_multisort(' . $sortRule . '$rowset);');
        return $rowset;
    }

    /*
     * 判断是否存在数组中，判断$key和$value
     *  @param array $val  判断的值
     *  @param array $arrayl  数组
     *
     * @return  bolean  返回标志
     */

    static function isExistsArray($val, $array) {
        if (in_array($val, $array))
            return true;
        if (array_key_exists($val, $array))
            return true;
        return false;
    }

    /* 数组转换对象
     * @param array 转换的数组 支持1维
     * @ return object 返回对象
     */

    static function arrayToObject($arr) {
        if (is_array($arr)) {
            $obj = (object) ($arr);
        }else
            $obj=null;
        return $obj;
    }

    /* 对象转数组
     * @param  object  转换的对象 支持1维
     * @ return array 返回对象
     */

    //
    static function objectToArray($obj) {
        if (is_object($obj)) {
            $arr = (array) ($obj);
        }else
            $arr=null;
        return $arr;
    }

    /* 二维数组的合并，如果键值不同取合集，如果有键值相同  a2中数值会替换a1
     * @param array  目标数组1
     * @param array  替换数组2
     * @return array 新的合并数组
     *
     * $a1['id']=array('id'=>'a','id2'=>'b','id3'=>'c')
     * $a1['id2']=array('id'=>'a','id2'=>'b')
     * $a1['id3']=array('id'=>'a','id2'=>'b')
     *
     * $a2['id']=array('id'=>'a1','id3'=>'b1')
     * $a2['id2']=array('id'=>'a0','id4'=>'b')
     * $a2['id4']=array('id'=>'a','id4'=>'b')
     *
     * $a4=array2To1array($a1,$a2)
     * dump
     * $a4['id']=array('id'=>'a1','id2'=>'b','id3'=>'b1')
     * $a4['id2']=array('id'=>'a0','id2'=>'b','id4'=>'b')
     * $a4['id3']=array('id'=>'a','id2'=>'b')
     * $a4['id4']=array('id'=>'a','id4'=>'b')
     *
     * PHP中两个数组合并可以使用+或者array_merge，但之间还是有区别的，而且这些区别如果了解不清楚项目中会要命的
     * 主要区别是两个或者多个数组中如果出现相同键名，键名分为字符串或者数字，需要注意
     * 1）键名为数字时，array_merge()不会覆盖掉原来的值，但＋合并数组则会把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉（不是覆盖）
      2）键名为字符时，＋仍然把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉，但array_merge()此时会覆盖掉前面相同键名的值
     */

    static function array2_merge($a1, $a2) {
        foreach ($a2 as $key => $val) {
            if (array_key_exists($key, $a1)) {
                $a2[$key]+=$a1[$key];
            }
        }
        $c_array = $a2 + $a1;
        return $c_array;
    }

    /* 批量设置{增加/修改}二维数组中的键值的值
     * @param string  $key  键值
     * @param string  $val  替换的值
     * @param array   $array 数组
     * @return array  $rule  规则默认为0,强制全部替换.
     */

    static function setArray2Val($key, $val, $array, $rule=0) {

        foreach ($array as $k => $value) {
            switch ($rule) {
                case 0;   //默认替换规则，存在就替换，不存在就自动增加；
                    $array[$k][$key] = $val;
                    break;
                case 1:  // 规则，存在就替换，不存在就不替换；
                    if (array_key_exists($key, $value)) {
                        $array[$k][$key] = $val;
                    }
            }
        }
        return $array;
    }

    /* 批量设置二维数组的值
     * @param string  $key  键值
     * @param  array  $array_val  替换的值数组形式
     * @param array   $array 数组
     * @param array  $rule  规则默认为0,强制全部替换.
     *
     *
     */ 
    static function setArray2Mult($key, $array_val, $array, $rule=0) {
        $i = 0;
        if (key($array_val) === 0) {      //如果没有键值则依次替换
            foreach ($array as $k => $val) {
                if ($array_val[$i]) {
                    $array[$k][$key] = $array_val[$i];
                    $i++;
                }
            }
        } else {                         //如何有键值
            foreach ($array_val as $k => $val) {
                if (array_key_exists($k, $array)) {
                    if (is_array($val))
                    { $val = self::array2_merge($array[$k][$key], $val); }//如果是数组 则合并组合新的数组
                    $array[$k][$key] = $val;
                }else {
                    if ($rule == 0)
                        $array[$k] = array($key => $val); //默认规则是0 如果不存在就增加新键值
                }
            }
        }
        return $array;
    }

    /*
     * 判断数组是否二维数组
     * @para    array  $array
     * @return  Boole
     *          true 一维
     *          false 二维
     */

    static function is1Array($array) {
        return count($array) == count($array, 1);
    }

    /*
     *
     * 把列反转的数组数据合并到数组中，支持数组2维数组。
     *
      $arr_3['value']=array(
      'code'=>'2001',
      'username'=>'丝袜',
      );
      $arr_3['type']=array(
      'code'=>'input',
      'username'=>'input',
      );
      $arr_3['name']=array(
      'code'=>'code'
      );

     *  arrayAddTo($arr_3,'input')
     *   $arr_4['input']=array(
      'code'=>array("value"=>"2001",'type'=>"input","name"=>"code"),
      'username'=>array("value"=>"丝袜",'type'=>"input"),
      );
     */

    function arrayAddTo($array, $key) {
        $ret = array();
        foreach ($array as $k => $v) {
            $ret = self::array2_merge($ret, self::_arrSetVal($v, $k));
        }
        $ret2[$key] = $ret;
        //  var_dump($ret2);
        return $ret2;
    }

    function _arrSetVal($array, $key) {
        foreach ($array as $k => $v) {
            $ret[$k] = array($key => $v);
        }
        return $ret;
    }

    /*
     * 支持1维数组
     * $rows=array(
      'id'=>'序号',
      'name'=>'姓名',
      'loginname'=>'登录名',
      );
     * getColsOnKey($rows , 'title')
     * $return=array(
     *
     * 
     */

    static function getColsOnKey($arr, $key) {
        $ret = array();
        foreach ($arr as $k => $v) {
            //  if (isset($row[$col])) { $ret[$key] = $row[$col]; }
            $ret[$k] = array($key => $v);
        }
        return $ret;
    }

    /**
     * @var在数组指定位置 插入 key
     * @param array            $array_Source
     * @param num              $index
     * @param num              $len    default 0
     * @param string or array  $insert_key 
     * @return array 
     * $a[]="1";
      $a[]="2";
      $a[]="3";
      $a[]="4";
      var_dump($a)
     */
    static function insertArray($array_Source, $index, $insert_key, $len=0) {

        array_splice($array_Source, $index, $len, $insert_key);
        return $array_Source;
    }

    /*
     * 多维数组排列
     * @exmple
     * $data=array(   
      array(
      'name'=>'Alice',
      'key'=>'2fc4ab3d639e5400efdfc73bc27e83f1',
      'age'=>20
      ),
      array(
      'name'=>'Claudia',
      'key'=>'831c2b79c1f19af39c7e3321e11e5f5e',
      'age'=>18
      ),
      array(
      'name'=>'Beatrice',
      'key'=>'6f8512a2066b8f35a27a495ce1228c76',
      'age'=>100
      ),
      array(
      'name'=>'Denise',
      'age'=>25,
      'key'=>'ef6de3b178bf9f69a9fef72e4ee7bbe9'
      )
      );

      $arr1 = sortdata($data, "name","asc");
      echo "<pre>";
      var_dump($arr1);
      echo "</pre>";

      $arr2 = sortdata($data, "age","desc");
      echo "<pre>";
      var_dump($arr2);
      echo "</pre>";

     */

    static function sortData($data, $col, $order) {
        if (count($data))
            $temp_array[key($data)] = array_shift($data);

        foreach ($data as $key => $val) {
            $offset = 0;
            $found = false;
            foreach ($temp_array as $tmp_key => $tmp_val) {
                if (!$found and strtolower($val[$col]) > strtolower($tmp_val[$col])) {
                    $temp_array = array_merge((array) array_slice($temp_array, 0, $offset), array($key => $val), array_slice($temp_array, $offset)
                    );
                    $found = true;
                }
                $offset++;
            }
            if (!$found) {
                $temp_array = array_merge($temp_array, array($key => $val));
            }
        }
        if (strtolower($order) == "asc") {
            $array = array_reverse($temp_array);
        } else {
            $array = $temp_array;
        }
        return $array;
    }

}

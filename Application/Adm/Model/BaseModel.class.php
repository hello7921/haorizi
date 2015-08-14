<?php
/**
 * 基础类
 */
namespace Adm\Model;
use Think\Model\RelationModel;

class baseModel extends RelationModel {
    var $attach_fields = array('img', 'extimg');
    var $editor_fields=array('info');
    protected function _after_find(&$result, $options) {
        parent::_after_find($result,$options);
        if (method_exists($this, '_parse_item')) {
            $result = $this->_parse_item($result);
        }
        $result = $this->parse($result);
    }
    protected function _after_getField(&$result, $options) {
        parent::_after_getField($result,$options);
        if(!is_array($result) &&in_array($options['field'],$this->attach_fields)){
            $result=attach($result, $this->name,true);
        }
    }
    function _after_select(&$result, $options) {
        parent::_after_select($result,$options);
        foreach ($result as $key => $val) {
            if (method_exists($this, '_parse_item')) {
                $result[$key] = $this->_parse_item($val);
            }
            $result[$key] = $this->parse($result[$key]);
        }
    }

    function parse($info) {
    foreach ($this->attach_fields as $val) {
        if (array_key_exists($val, $info)) {
            $info['_'.$val] = attach($info[$val], $this->name,true);
        }
    }
    foreach($this->editor_fields as $val){
        if(array_key_exists($val,$info)){
            $info[$val]=parse_editor_info($info[$val]);
        }
    }
    return $info;
}
}